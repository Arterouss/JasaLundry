<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Service;
use App\Models\Perfume;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    /**
     * 1. Menampilkan Semua Pesanan Masuk di Dashboard Admin
     */
    public function index()
    {
        $orders = Order::with(['customer', 'service', 'perfume'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.dashboard', compact('orders'));
    }

    /**
     * 2. Proses Assessment oleh Admin (Input Berat, Jarak, & Kalkulasi)
     */
    public function assessOrder(Request $request, Order $order)
    {
        // Validasi inputan admin
        $request->validate([
            'weight' => 'required|numeric|min:0.1',
            'distance_km' => 'required_if:is_pickup_delivery,true|nullable|numeric|min:0',
        ]);

        // 1. Ambil harga dasar dari layanan yang dipilih pelanggan
        $pricePerKg = $order->service->price_per_kg;
        $totalLaundry = $request->weight * $pricePerKg;

        // 2. Hitung Ongkir jika pesanannya antar-jemput
        $totalOngkir = 0;
        if ($order->is_pickup_delivery) {
            $tarifPerKm = 2000; // Kamu bisa ubah nominal tarif per km di sini
            $totalOngkir = $request->distance_km * $tarifPerKm;
        }

        $grandTotal = $totalLaundry + $totalOngkir;

        // 3. Kalkulasi Estimasi Waktu (Menggunakan Batas Atas dari Master Service)
        // Rumus: Waktu Sekarang + Menit Standar Layanan
        $estimatedMinutes = $order->service->estimated_minutes;
        $estimatedCompletion = now()->addMinutes($estimatedMinutes);

        // 4. Tentukan Status Selanjutnya berdasarkan Aturan Main kamu
        if ($order->is_pickup_delivery && $order->payment_method === 'cashless') {
            // Jika antar jemput + cashless, status PENDING dulu menunggu dibayar pelanggan
            $nextStatus = OrderStatus::MENUNGGU_PEMBAYARAN;
        } else {
            // Jika drop-off atau bayar di tempat, bisa langsung masuk antrean PROSES
            $nextStatus = OrderStatus::DIPROSES;
        }

        // Update data order
        $order->update([
            'weight' => $request->weight,
            'distance_km' => $order->is_pickup_delivery ? $request->distance_km : null,
            'grand_total' => $grandTotal,
            'estimated_completion_time' => $estimatedCompletion,
            'status' => $nextStatus,
        ]);

        // Catat perubahan status ke log tracking pelanggan
        $order->statusLogs()->create([
            'status' => $nextStatus->value,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Kalkulasi order berhasil disimpan!');
    }

    /**
     * 3. Update Status Manual oleh Admin (Logika Ceklis Berkelanjutan)
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        // Cari value Enum yang sesuai dengan input string dari form admin
        $newStatus = OrderStatus::from($request->status);

        $order->update([
            'status' => $newStatus,
        ]);

        // Jika admin mengubah status menjadi SELESAI, otomatis set payment_status jadi lunas (terutama untuk cash_on_site)
        if ($newStatus === OrderStatus::SELESAI) {
            $order->update(['payment_status' => 'paid']);
        }

        // Catat ke log status agar di sisi pelanggan tercentang
        $order->statusLogs()->create([
            'status' => $newStatus->value,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Status pesanan berhasil diperbarui!');
    }

    /**
     * 4. Spesial: Input Pesanan Spontan / Walk-In oleh Admin
     */
    public function storeWalkIn(Request $request)
    {
        $request->validate([
            'walk_in_name' => 'required|string|max:255',
            'service_id' => 'required|exists:services,id',
            'perfume_id' => 'required|exists:perfumes,id',
            'weight' => 'required|numeric|min:0.1',
        ]);

        $service = Service::findOrFail($request->service_id);
        
        // Kalkulasi harga murni (Tanpa Ongkir karena datang langsung)
        $grandTotal = $request->weight * $service->price_per_kg;
        $estimatedCompletion = now()->addMinutes($service->estimated_minutes);

        // Pelanggan spontan langsung bayar di tempat (cash_on_site) dan langsung masuk proses
        $order = Order::create([
            'customer_id' => null, // Tidak terikat akun user manapun
            'walk_in_name' => $request->walk_in_name,
            'service_id' => $request->service_id,
            'perfume_id' => $request->perfume_id,
            'is_pickup_delivery' => false,
            'gps_address' => null,
            'payment_method' => 'cash_on_site',
            'payment_status' => 'unpaid', // Nanti lunas saat admin set status "Selesai"
            'weight' => $request->weight,
            'grand_total' => $grandTotal,
            'status' => OrderStatus::DIPROSES, // Langsung gas diproses
            'estimated_completion_time' => $estimatedCompletion,
        ]);

        // Catat log awal langsung di status DIPROSES
        $order->statusLogs()->create([
            'status' => OrderStatus::DIPROSES->value,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Pesanan walk-in berhasil dibuat!');
    }
}