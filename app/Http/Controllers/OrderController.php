<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Service;
use App\Models\Perfume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;

class OrderController extends Controller
{
    public function __construct()
    {
        // Konfigurasi Kunci Midtrans dari .env
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = env('MIDTRANS_IS_SANITIZED', true);
        Config::$is3ds = env('MIDTRANS_IS_3DS', true);
    }

    /**
     * 1. Menampilkan Halaman Form Order (Pelanggan)
     */
    public function create()
    {
        $services = Service::all();
        
        // Mengambil semua parfum, menghitung jumlah penggunaannya di tabel orders,
        // lalu diurutkan dari yang paling banyak dipesan (Terpopuler)
        $perfumes = Perfume::withCount('orders')
                        ->orderBy('orders_count', 'desc')
                        ->get();

        // Mengirim data $services dan $perfumes ke halaman pesan.blade.php
        return view('customer.pesan', compact('services', 'perfumes'));
    }

    /**
     * 2. Menyimpan Pesanan Baru dari Pelanggan
     */
    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'perfume_id' => 'required|exists:perfumes,id',
            'is_pickup_delivery' => 'boolean',
            'gps_address' => 'required_if:is_pickup_delivery,1|nullable|string',
            'payment_method' => 'required_if:is_pickup_delivery,1|nullable|in:cash_on_site,cashless',
        ]);

        $isPickup = $request->boolean('is_pickup_delivery');

        // Set default status awal pesanan
        $status = OrderStatus::DITERIMA; 

        if (!$isPickup) {
            $paymentMethod = 'cash_on_site';
            $gpsAddress = null;
        } else {
            $paymentMethod = $request->payment_method;
            $gpsAddress = $request->gps_address;
        }

        $order = Order::create([
            'customer_id' => Auth::id(),
            'service_id' => $request->service_id,
            'perfume_id' => $request->perfume_id,
            'is_pickup_delivery' => $isPickup,
            'gps_address' => $gpsAddress,
            'payment_method' => $paymentMethod,
            'payment_status' => 'unpaid',
            'status' => $status,
        ]);

        $statusValue = is_object($status) && isset($status->value) ? $status->value : $status;

        $order->statusLogs()->create([
            'status' => $statusValue,
        ]);

        return redirect()->route('customer.dashboard')->with('success', 'Pesanan berhasil dibuat!');
    }

    /**
     * 3. Menampilkan Dashboard / Riwayat Pesanan Aktif Pelanggan
     */
    public function dashboard()
    {
        $user = Auth::user();

        // 1. Hitung-hitungan untuk widget statistik dashboard
        $totalPesanan = Order::query()->where('customer_id', $user->id)->count();
        
        $sedangProses = Order::query()->where('customer_id', $user->id)
            ->where('status', '!=', OrderStatus::SELESAI->value)
            ->count();
            
        $siapDiambil = Order::query()->where('customer_id', $user->id)
            ->where('status', '=', OrderStatus::SIAP->value)
            ->count();

        // 2. Ambil SEMUA pesanan aktif (yang belum berstatus SELESAI)
        $activeOrders = Order::with(['service', 'perfume'])
            ->where('customer_id', $user->id)
            ->where('status', '!=', OrderStatus::SELESAI)
            ->orderBy('created_at', 'desc')
            ->get();

        // Kirim variabel $activeOrders ke view dashboard
        return view('customer.dashboard', compact('user', 'totalPesanan', 'sedangProses', 'siapDiambil', 'activeOrders'));
    }

    /**
     * 6. Menampilkan Halaman Riwayat Pesanan dengan Filter Dinamis
     */
    public function history(Request $request)
    {
        $user = Auth::user();

        // Membuat instance query dasar dari Model Order khusus milik user login
        $query = Order::query()->where('customer_id', $user->id);

        // Menangkap parameter filter kategori dari URL (Request GET)
        $statusFilter = $request->get('status');

        if ($statusFilter) {
            if ($statusFilter === 'menunggu') {
                $query->whereIn('status', [\App\Enums\OrderStatus::DITERIMA, \App\Enums\OrderStatus::MENUNGGU_PEMBAYARAN]);
            } elseif ($statusFilter === 'proses') {
                $query->whereIn('status', [\App\Enums\OrderStatus::DIJEMPUT, \App\Enums\OrderStatus::DIPROSES]);
            } elseif ($statusFilter === 'siap') {
                $query->where('status', \App\Enums\OrderStatus::SIAP);
            } elseif ($statusFilter === 'diantar') {
                $query->where('status', \App\Enums\OrderStatus::DIANTAR);
            }
        }

        // Ambil data dengan pagination
        $orders = $query->orderBy('created_at', 'desc')->paginate(5);

        return view('customer.riwayat', compact('user', 'orders'));
    }

    /**
     * 4. Halaman Pembayaran (Khusus Cashless) - Menampilkan Ringkasan
     */
    public function checkout(Order $order)
    {
        // Proteksi jika ternyata sudah lunas
        if ($order->payment_status === 'paid') {
            return redirect()->route('customer.dashboard')->with('success', 'Pesanan ini sudah lunas.');
        }

        // PERBAIKAN: Me-load ulang order beserta relasi 'service' agar datanya tidak null di Blade
        $order->load(['service']);

        $user = Auth::user(); 
        return view('customer.pembayaran', compact('order', 'user'));
    }

    /**
     * Menghasilkan Snap Token untuk dikirim ke Javascript di Blade via AJAX
     */
    public function processPayment(Order $order)
    {
        // Buat parameter transaksi Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => 'LDR-' . $order->id . '-' . time(), // Token unik penangkal duplicate transaction
                'gross_amount' => (int) $order->grand_total,      // Menggunakan nominal akhir tagihan
            ],
            'item_details' => [
                [
                    'id' => $order->service_id,
                    'price' => (int) ($order->service_cost ?? $order->grand_total),
                    'quantity' => 1,
                    'name' => $order->service->name ?? 'Layanan Laundry',
                ]
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name ?? 'Pelanggan',
            ],
        ];

        try {
            // Ambil token dari Midtrans
            $snapToken = Snap::getSnapToken($params);
            
            // Kembalikan dalam bentuk response JSON agar dibaca oleh fetch di JavaScript
            return response()->json([
                'status' => 'success',
                'token' => $snapToken
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function paymentSuccessRedirect(Order $order)
        {
            // 1. Ubah status pembayaran di database menjadi paid
            $order->update([
                'payment_status' => 'paid'
            ]);

            // 2. Alihkan kembali ke dashboard dengan pesan sukses
            return redirect()->route('customer.dashboard')->with('success', 'Pembayaran Anda berhasil terkonfirmasi!');
        }
}