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

        if ($order->status->value === 'pesanan_selesai') {
        return redirect()->back()->withErrors(['status' => 'Pesanan yang sudah selesai tidak boleh diedit lagi!']);
    }

        // LOGIKA VALIDASI DINAMIS:
        $rules = [
            'status' => 'required|string',
        ];

        // 1. Jika status saat ini MASIH DITERIMA dan dia layanan Antar Jemput, Jarak WAJIB diisi. Berat NYA NYANTAI (nullable)
        if ($order->status === OrderStatus::DITERIMA) {
            if ($order->is_pickup_delivery) {
                $rules['distance_km'] = 'required|numeric|min:0.1|max:10';
            }
            $rules['weight'] = 'nullable|numeric|min:0.1|max:5';
        } 
        // 2. Jika admin mau memindahkan status ke DIPROSES (atau sesudahnya), BARU BERAT WAJIB DIISI
        else if (in_array($request->status, ['pesanan_diproses', 'pesanan_siap', 'pesanan_diantar', 'pesanan_selesai'])) {
            $rules['weight'] = 'required|numeric|min:0.1|max:5';
            $rules['distance_km'] = 'nullable|numeric|max:10';
        }

        // Eksekusi Validasi dengan custom pesan error
        $request->validate($rules, [
            'weight.required' => 'Gagal memproses! Berat pakaian wajib diisi saat pakaian mulai diproses.',
            'weight.max' => 'Berat pakaian melampaui batas maksimal sistem (Maksimal 5 Kg).',
            'distance_km.required' => 'Jarak pengiriman wajib diisi di awal untuk menghitung ongkir.',
            'distance_km.max' => 'Jarak pengantaran di luar jangkauan area kurir (Maksimal 10 Km).',
        ]);

        // Konversi string dari Blade ke instance Enum
        $newStatus = OrderStatus::tryFrom($request->status);
        if (!$newStatus) {
            return redirect()->back()->withErrors(['status' => 'Status laundry tidak valid.']);
        }

        // --- LOGIKA KALKULASI UPDATE ---
        // Jika status SEBELUMNYA adalah DITERIMA, kita izinkan hitung ulang harga dasar dan ongkir
        if ($order->status === OrderStatus::DITERIMA) {
            $distance_km = $order->is_pickup_delivery ? $request->distance_km : null;
            $weight = $request->weight; // bisa bernilai null jika admin belum timbang

            // 1. Hitung harga dasar layanan (jika berat sudah diisi, jika belum set 0 dulu)
            $pricePerKg = $order->service->price_per_kg ?? 5000;
            $totalLaundry = $weight ? ($weight * $pricePerKg) : 0;

            // 2. Hitung Ongkir berbasis ZONASI (Sesuai jarak_km)
            $totalOngkir = 0;
            if ($order->is_pickup_delivery && $distance_km) {
                if ($distance_km <= 2) {
                    $totalOngkir = 5000;
                } elseif ($distance_km <= 5) {
                    $totalOngkir = 10000;
                } else {
                    $totalOngkir = 20000;
                }
            }

            $grandTotal = $totalLaundry + $totalOngkir;
            $estimatedCompletion = $order->estimated_completion_time;
        } else {
            // Jika status sudah berjalan (DIJEMPUT / DIPROSES dst), kunci data kalkulasi lama, ambil input berat baru jika ada update
            $distance_km = $order->distance_km;
            $weight = $request->weight ?? $order->weight; 

            $pricePerKg = $order->service->price_per_kg ?? 5000;
            $totalLaundry = $weight * $pricePerKg;

            // hitung ongkir lama
            $totalOngkir = 0;
            if ($order->is_pickup_delivery && $distance_km) {
                if ($distance_km <= 2) { $totalOngkir = 5000; }
                elseif ($distance_km <= 5) { $totalOngkir = 10000; }
                else { $totalOngkir = 20000; }
            }

            $grandTotal = $totalLaundry + $totalOngkir;
            
            // Update estimasi waktu jika baru masuk tahap DIPROSES
            $estimatedCompletion = $order->estimated_completion_time;
            if ($order->status === OrderStatus::DIJEMPUT && $newStatus === OrderStatus::DIPROSES) {
                $estimatedMinutes = $order->service->estimated_minutes ?? 120;
                $estimatedCompletion = now()->addMinutes($estimatedMinutes);
            }
        }

        // Update data order ke database
        $order->update([
            'weight' => $weight,
            'distance_km' => $distance_km,
            'grand_total' => $grandTotal,
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

    public function createWalkIn()
    {
        // Ambil data layanan dan parfum untuk disajikan di dropdown form Blade
        $services = Service::all();
        $perfumes = Perfume::all();

        return view('admin.tambah-pesanan', compact('services', 'perfumes'));
    }
    public function storeWalkIn(Request $request)
    {
        // Validasi disesuaikan dengan input form Blade (max berat 5 Kg)
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'service_id' => 'required|exists:services,id',
            'perfume_id' => 'required|exists:perfumes,id',
            'weight' => 'required|numeric|min:0.1|max:5',
        ], [
            'customer_name.required' => 'Nama pelanggan wajib diisi.',
            'service_id.required' => 'Silakan pilih jenis layanan laundry.',
            'perfume_id.required' => 'Silakan pilih aroma parfum.',
            'weight.required' => 'Berat timbangan wajib diisi.',
            'weight.max' => 'Berat pakaian melampaui batas maksimal sistem (Maksimal 5 Kg).',
        ]);

        $service = Service::findOrFail($request->service_id);
        
        // Kalkulasi harga dasar
        $grandTotal = $request->weight * $service->price_per_kg;
        $estimatedCompletion = now()->addMinutes($service->estimated_minutes);

        // Simpan data sesuai dengan nama struktur kolom di database kamu
        $order = Order::create([
            'customer_id' => null, 
            'walk_in_name' => $request->customer_name, // Menangkap input customer_name dari form
            'service_id' => $request->service_id,
            'perfume_id' => $request->perfume_id,
            'is_pickup_delivery' => false,
            'gps_address' => null,
            'payment_method' => 'cash_on_site',
            'payment_status' => 'unpaid', 
            'weight' => $request->weight,
            'grand_total' => $grandTotal, // Menggunakan total_price sesuai struktur tabelmu
            'status' => OrderStatus::DIPROSES, // Walk-in langsung masuk tahap diproses (mulai cuci)
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