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
     * 1. Menampilkan Semua Pesanan Masuk
     */
    public function index()
    {
        $orders = Order::with(['customer', 'service', 'perfume'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Pastikan nama view ini sesuai dengan nama file blade kamu (admin/edit.blade.php)
        return view('admin.edit', compact('orders'));
    }

    /**
     * 2. Proses Assessment (Tombol Simpan Per Baris Tabel)
     */
    public function assessOrder(Request $request, $id)
    {
        // Temukan data order tunggal berdasarkan ID agar terhindar dari error Collection
        $order = Order::findOrFail($id);

        // Validasi inputan admin (sesuai name attribute di Blade)
        $request->validate([
            'weight' => 'required|numeric|min:0.1',
            'distance' => 'required_if:is_pickup_delivery,true|nullable|numeric|min:0',
            'status' => 'required|string',
        ]);

        // 1. Ambil harga dasar layanan pelanggan
        $pricePerKg = $order->service->price_per_kg ?? 5000;
        $totalLaundry = $request->weight * $pricePerKg;

        // 2. Hitung Ongkir jika antar-jemput
        $totalOngkir = 0;
        if ($order->is_pickup_delivery) {
            $tarifPerKm = 2000; 
            // Membaca input 'distance' dari Blade
            $totalOngkir = ($request->distance ?? 0) * $tarifPerKm;
        }

        $grandTotal = $totalLaundry + $totalOngkir;

        // 3. Kalkulasi Estimasi Waktu
        $estimatedMinutes = $order->service->estimated_minutes ?? 120;
        $estimatedCompletion = now()->addMinutes($estimatedMinutes);

        // 4. Ambil status operasional yang dipilih admin dari dropdown select
        $newStatus = OrderStatus::from($request->status);

        // Update data order ke database (Gunakan kolom total_price dan distance sesuai aplikasi kamu)
        $order->update([
            'weight' => $request->weight,
            'distance' => $order->is_pickup_delivery ? $request->distance : null,
            'total_price' => $grandTotal, // Sesuaikan jika nama kolom kamu grand_total atau total_price
            'estimated_completion_time' => $estimatedCompletion,
            'status' => $newStatus,
        ]);

        // Jika status diubah menjadi SELESAI, otomatis tandai lunas
        if ($newStatus === OrderStatus::SELESAI) {
            $order->update(['payment_status' => 'paid']);
        }

        // Catat perubahan status ke log tracking pelanggan
        if (method_exists($order, 'statusLogs')) {
            $order->statusLogs()->create([
                'status' => $newStatus->value,
            ]);
        }

        return redirect()->route('admin.dashboard')->with('success', 'Data pesanan LDR-' . $order->id . ' berhasil diperbarui!');
    }

    /**
     * 3. Input Pesanan Spontan / Walk-In oleh Admin
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
        
        $grandTotal = $request->weight * $service->price_per_kg;
        $estimatedCompletion = now()->addMinutes($service->estimated_minutes);

        $order = Order::create([
            'customer_id' => null, 
            'walk_in_name' => $request->walk_in_name,
            'service_id' => $request->service_id,
            'perfume_id' => $request->perfume_id,
            'is_pickup_delivery' => false,
            'gps_address' => null,
            'payment_method' => 'cash_on_site',
            'payment_status' => 'unpaid', 
            'weight' => $request->weight,
            'total_price' => $grandTotal,
            'status' => OrderStatus::DIPROSES, 
            'estimated_completion_time' => $estimatedCompletion,
        ]);

        if (method_exists($order, 'statusLogs')) {
            $order->statusLogs()->create([
                'status' => OrderStatus::DIPROSES->value,
            ]);
        }

        return redirect()->route('admin.dashboard')->with('success', 'Pesanan walk-in berhasil dibuat!');
    }
}