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
        $services = Service::all();
        $perfumes = Perfume::all();

        // Dipastikan memanggil file 'pesan.blade.php' langsung di folder views
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

        if (!$isPickup) {
            $status = OrderStatus::DITERIMA;
            $paymentMethod = 'cash_on_site';
            $gpsAddress = null;
        } else {
            $status = OrderStatus::DITERIMA;
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
        $user = Auth::user();

        // Perbaikan Intelephense: Gunakan Order::query() di awal agar IDE paham ini adalah Eloquent Builder
        $totalPesanan = Order::query()->where('customer_id', $user->id)->count();
        
        // Perbaikan Intelephense: Menggunakan klausa where konvensional untuk mengamankan deteksi argument
        $sedangProses = Order::query()->where('customer_id', $user->id)
            ->where('status', '!=', 'selesai')
            ->count();
            
        $siapDiambil = Order::query()->where('customer_id', $user->id)
            ->where('status', 'siap')
            ->count();

        $latestOrder = Order::query()->where('customer_id', $user->id)
            ->latest()
            ->first();

        return view('customer.dashboard', compact('user', 'totalPesanan', 'sedangProses', 'siapDiambil', 'latestOrder'));
    }

    /**
     * 4. Halaman Pembayaran (Khusus Cashless)
     */
    public function checkout(Order $order)
    {
        if ($order->customer_id !== Auth::id() || 
            $order->payment_method !== 'cashless' || 
            $order->status !== OrderStatus::MENUNGGU_PEMBAYARAN) {
            abort(403, 'Akses tidak sah atau pesanan belum siap dibayar.');
        }

        return view('customer.pembayaran', compact('order'));
    }

    /**
     * 5. Proses Menyelesaikan Pembayaran Cashless
     */
    public function processPayment(Order $order)
    {
        if ($order->customer_id !== Auth::id() || $order->status !== OrderStatus::MENUNGGU_PEMBAYARAN) {
            abort(403);
        }

        $order->update([
            'payment_status' => 'paid',
            'status' => OrderStatus::DIPROSES,
        ]);

        $order->statusLogs()->create([
            'status' => OrderStatus::DIPROSES->value,
        ]);

        return redirect()->route('customer.dashboard')->with('success', 'Pembayaran berhasil! Cucianmu mulai diproses.');
    }
}