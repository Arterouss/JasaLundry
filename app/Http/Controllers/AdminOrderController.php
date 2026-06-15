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
     * 1. Menampilkan Semua Pesanan Masuk di Dashboard
     */
    public function index()
    {
        $orders = Order::with(['customer', 'service', 'perfume'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.dashboard', compact('orders'));
    }

    /**
     * BARU: Menampilkan Halaman Form Edit Pesanan (edit-pesanan.blade.php)
     * Dipanggil ketika tombol edit di halaman dashboard ditekan
     */
    public function edit($id)
    {
        // Cari data pesanan tunggal berdasarkan ID, jika tidak ada lempar error 404
        $order = Order::with(['customer', 'service', 'perfume'])->findOrFail($id);

        // Lempar data pesanan ke file blade yang kamu amankan (edit-pesanan.blade.php)
        return view('admin.edit-pesanan', compact('order'));
    }

    /**
     * BARU: Menangani Perubahan Status Dropdown via AJAX/Fetch API jika diperlukan
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string'
        ]);

        $newStatus = OrderStatus::from($request->status);

        $order->update([
            'status' => $newStatus
        ]);

        if (method_exists($order, 'statusLogs')) {
            $order->statusLogs()->create([
                'status' => $newStatus->value,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status pesanan berhasil diperbarui!'
        ]);
    }

    /**
     * 2. Proses Assessment (Dipicu ketika tombol Simpan di form edit-pesanan ditekan)
     */
    /**
     * 2. Proses Assessment (Dipicu ketika tombol Simpan di form edit-pesanan ditekan)
     */
    public function assessOrder(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        // Validasi inputan admin dengan mencocokkan payload bantuan 'is_pickup_delivery' dari blade
        $request->validate([
            'weight' => $order->status === OrderStatus::DITERIMA ? 'required|numeric|min:0.1' : 'nullable',
            'distance' => 'required_if:is_pickup_delivery,true|nullable|numeric|min:0',
            'status' => 'required|string',
        ]);

        // Konversi string dari Blade ke instance Enum secara clean
        $newStatus = OrderStatus::tryFrom($request->status);
        
        if (!$newStatus) {
            return redirect()->back()->withErrors(['status' => 'Status laundry tidak valid.']);
        }

        // MITIGASI: Jika status saat ini sudah "Diproses" atau lebih tinggi, KUNCI data lama
        if ($order->status !== OrderStatus::DITERIMA) {
            $weight = $order->weight;
            $distance = $order->distance;
            $grandTotal = $order->total_price; 
            $estimatedCompletion = $order->estimated_completion_time;
        } else {
            // Jika masih berstatus 'DITERIMA', hitung otomatis tarif real time di backend
            $weight = $request->weight;
            $distance = $order->is_pickup_delivery ? $request->distance : null;

            // 1. Ambil harga dasar layanan
            $pricePerKg = $order->service->price_per_kg ?? 5000;
            $totalLaundry = $weight * $pricePerKg;

            // 2. Hitung Ongkir jika tipe antar-jemput aktif
            // Di dalam fungsi assessOrder, ganti bagian perhitungan ongkir:

$totalOngkir = 0;
if ($order->is_pickup_delivery) {
    // Logika Zonasi Backend (HARUS SAMA dengan Javascript)
    if ($distance <= 2) {
        $totalOngkir = 5000;
    } elseif ($distance <= 5) {
        $totalOngkir = 10000;
    } elseif ($distance <= 10) {
        $totalOngkir = 20000;
    } else {
        $totalOngkir = 30000;
    }
}

$grandTotal = $totalLaundry + $totalOngkir;

            // 3. Kalkulasi Estimasi Waktu Selesai
            $estimatedMinutes = $order->service->estimated_minutes ?? 120;
            $estimatedCompletion = now()->addMinutes($estimatedMinutes);
        }

        // Update data order ke database 
        $order->update([
            'weight' => $weight,
            'distance' => $distance,
            'total_price' => $grandTotal, 
            'estimated_completion_time' => $estimatedCompletion,
            'status' => $newStatus,
        ]);

        // Jika status diubah menjadi SELESAI, otomatis tandai lunas
        if ($newStatus === OrderStatus::SELESAI) {
            $order->update(['payment_status' => 'paid']);
        }

        // Catat log status tracking
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