<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Service;
use App\Models\Perfume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * 1. Menampilkan Halaman Form Order (Pelanggan)
     */
    public function create()
    {
        // Mengambil data master untuk dropdown di form
        $services = Service::all();
        $perfumes = Perfume::all();

        return view('customer.orders.create', compact('services', 'perfumes'));
    }

    /**
     * 2. Menyimpan Pesanan Baru dari Pelanggan
     */
    public function store(Request $request)
    {
        // Validasi Bersyarat (Kondisional)
        // Jika is_pickup_delivery dicentang (true), maka gps_address dan payment_method WAJIB diisi.
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'perfume_id' => 'required|exists:perfumes,id',
            'is_pickup_delivery' => 'boolean',
            'gps_address' => 'required_if:is_pickup_delivery,1|nullable|string',
            'payment_method' => 'required_if:is_pickup_delivery,1|nullable|in:cash_on_site,cashless',
        ]);

        $isPickup = $request->boolean('is_pickup_delivery');

        // Logika Status Awal & Metode Pembayaran berdasarkan opsi Antar-Jemput
        if (!$isPickup) {
            // JIKA Drop-off (bawa sendiri), status langsung DITERIMA dan pembayaran otomatis di tempat
            $status = OrderStatus::DITERIMA;
            $paymentMethod = 'cash_on_site';
            $gpsAddress = null;
        } else {
            // JIKA Antar-Jemput, status awal DITERIMA (nanti admin yang ubah ke DIJEMPUT)
            $status = OrderStatus::DITERIMA;
            $paymentMethod = $request->payment_method;
            $gpsAddress = $request->gps_address;
        }

        // Simpan data order ke database
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

        // Catat Log Status Awal untuk Fitur Ceklis Riwayat
        $order->statusLogs()->create([
            'status' => $status->value,
        ]);

        return redirect()->route('customer.dashboard')->with('success', 'Pesanan berhasil dibuat!');
    }

    /**
     * 3. Menampilkan Dashboard / Riwayat Pesanan Aktif Pelanggan
     */
    public function dashboard()
    {
        // Ambil semua pesanan milik pelanggan yang sedang login beserta log statusnya
        $orders = Order::with(['service', 'perfume', 'statusLogs'])
            ->where('customer_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customer.dashboard', compact('orders'));
    }

    /**
     * 4. Halaman Pembayaran (Khusus Cashless yang berstatus MENUNGGU_PEMBAYARAN)
     */
    public function checkout(Order $order)
    {
        // Keamanan: Pastikan order ini milik pelanggan yang login, metodenya cashless, dan statusnya pas
        if ($order->customer_id !== Auth::id() || 
            $order->payment_method !== 'cashless' || 
            $order->status !== OrderStatus::MENUNGGU_PEMBAYARAN) {
            abort(403, 'Akses tidak sah atau pesanan belum siap dibayar.');
        }

        return view('customer.orders.checkout', compact('order'));
    }

    /**
     * 5. Proses Menyelesaikan Pembayaran Cashless
     */
    public function processPayment(Order $order)
    {
        if ($order->customer_id !== Auth::id() || $order->status !== OrderStatus::MENUNGGU_PEMBAYARAN) {
            abort(403);
        }

        // Update status pembayaran jadi lunas, dan status laundry lanjut ke DIPROSES
        $order->update([
            'payment_status' => 'paid',
            'status' => OrderStatus::DIPROSES,
        ]);

        // Catat log perubahan status ke DIPROSES agar ceklis pelanggan bertambah
        $order->statusLogs()->create([
            'status' => OrderStatus::DIPROSES->value,
        ]);

        return redirect()->route('customer.dashboard')->with('success', 'Pembayaran berhasil! Cucianmu mulai diproses.');
    }
}