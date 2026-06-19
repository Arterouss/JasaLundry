<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pesanan - MyLaundry Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background-color: #333; color: #333; padding: 40px; display: flex; justify-content: center; }
        
        .container { width: 100%; max-width: 600px; background: #e5eff8; padding: 30px 40px; border-radius: 12px; }

        .header-section { margin-bottom: 25px; }
        .header-title h2 { font-size: 20px; font-weight: 600; color: #0F4A75; margin-bottom: 5px; }
        .header-title p { color: #0F4A75; font-size: 13px; }
        
        .form-card { background: white; border-radius: 12px; padding: 30px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }

        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 12px; color: #0F4A75; margin-bottom: 8px; font-weight: 600; }
        .input-icon-wrap { position: relative; }
        .form-control { width: 100%; padding: 12px 16px; background-color: #fff; border: 1px solid #e2e8f0; border-radius: 8px; color: #333; font-size: 13px; outline: none; }
        .form-control:focus { border-color: #0F4A75; }
        
        /* Style khusus untuk readonly agar terlihat terkunci/grayed out */
        .form-control[readonly], .form-control:disabled { background-color: #f1f5f9; color: #64748b; cursor: not-allowed; border-color: #cbd5e1; }
        
        .input-icon-wrap .form-control { padding-right: 40px; }
        .input-icon-wrap svg { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; fill: #94a3b8; }
        
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

        .summary-section { margin-top: 30px; margin-bottom: 30px; background-color: #f8fafc; padding: 15px; border-radius: 8px; border: 1px dashed #cbd5e1; }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 13px; font-weight: 600; color: #475569; }
        .summary-row.total-highlight { color: #0F4A75; font-size: 15px; font-weight: 700; border-top: 1px solid #e2e8f0; padding-top: 10px; margin-bottom: 0; }
        
        .btn-submit { width: 100%; padding: 12px; background-color: #2b6cb0; color: white; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; text-align: center; text-decoration: none; display: block; transition: 0.2s; }
        .btn-submit:hover { background-color: #1e4e8c; }
        
        .back-link { display: inline-block; margin-bottom: 20px; color: white; text-decoration: none; font-size: 14px; }
        .back-link:hover { text-decoration: underline; }

        .alert-error { background-color: #fff5f5; color: #c53030; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 13px; }
        .alert-success { background-color: #f0fff4; color: #22543d; padding: 15px; border-radius: 8px; text-align: center; font-size: 13px; font-weight: 600; border: 1px solid #c6f6d5; }

        @media (max-width: 600px) {
            .form-row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div>
        <a href="{{ route('admin.dashboard') }}" class="back-link">&larr; Kembali ke Dashboard</a>
        <div class="container">
            <div class="header-section">
                <div class="header-title">
                    <h2>Assessment & Edit Pesanan</h2>
                    <p>ID Pesanan: LDR-{{ $order->id }}</p>
                </div>
            </div>

            <div class="form-card">
                @if ($errors->any())
                    <div class="alert-error">
                        <ul style="margin: 0; padding-left: 20px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.orders.assess', $order->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <input type="hidden" name="is_pickup_delivery" value="{{ $order->is_pickup_delivery ? 'true' : 'false' }}">

                    <div class="form-group">
                        <label>Nama Pelanggan</label>
                        <div class="input-icon-wrap">
                            <input type="text" class="form-control" value="{{ $order->customer->name ?? $order->walk_in_name }}" readonly>
                            <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Jenis Layanan</label>
                            <div class="input-icon-wrap">
                                <input type="text" class="form-control" value="{{ $order->service->name ?? 'Layanan Laundry' }}" readonly>
                                <svg viewBox="0 0 24 24"><path d="M19.5 8.5L18.5 20.5C18.5 21.3 17.8 22 17 22H7C6.2 22 5.5 21.3 5.5 20.5L4.5 8.5C4.5 7.7 5.2 7 6 7H18C18.8 7 19.5 7.7 19.5 8.5Z" fill="none" stroke="currentColor" stroke-width="2"/></svg>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Alamat Antar / Maps</label>
                            <div class="input-icon-wrap">
                                <input type="text" class="form-control" value="{{ $order->gps_address ?? 'Ambil di Toko (Offline/Walk-In)' }}" readonly>
                                <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/></svg>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="weight">Input Berat Real (Kg)</label>
                            @php
                                // Berat bisa diisi selama pesanan belum mulai diproses/dicuci.
                                $currentVal = $order->status->value;
                                $isWeightReadOnly = in_array($currentVal, [
                                    'pesanan_diproses', 'pesanan_siap', 'pesanan_diantar', 'pesanan_selesai'
                                ]);
                            @endphp
                            <input type="number" step="0.1" min="0.1" max="5" 
                                   name="weight" 
                                   id="weight" 
                                   class="form-control" 
                                   value="{{ old('weight', $order->weight) }}" 
                                   placeholder="Contoh: 3.5"
                                   {{ $isWeightReadOnly ? 'readonly' : '' }}>
                            
                            <small class="text-muted" style="font-size: 11px;">
                                @if($isWeightReadOnly)
                                    Berat pakaian sudah dikunci demi keamanan transaksi.
                                @else
                                    Isi berat timbangan sebelum mengubah status ke "Diproses".
                                @endif
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="distance_km">Input Jarak Pengiriman (Km)</label>
                            @php
                                // Jarak hanya bisa diisi untuk Antar Jemput saat status masih Diterima
                                $isDistanceReadOnly = ($currentVal !== 'pesanan_diterima' || !$order->is_pickup_delivery);
                            @endphp
                            <input type="number" step="0.1" min="0.1" max="10" 
                                   name="distance_km" 
                                   id="distance_km" 
                                   class="form-control" 
                                   value="{{ old('distance_km', $order->distance_km > 0 ? $order->distance_km : '') }}" 
                                   placeholder="Contoh: 4.5"
                                   {{ $isDistanceReadOnly ? 'readonly' : '' }}> 
                            <small class="text-muted" style="font-size: 11px;">Maksimal jarak kurir adalah 10 Km.</small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="status">Update Status Operasional</label>
                        @php
                            $currentStatus = $order->status->value;
                            // Tambahkan pengecekan apakah statusnya sudah selesai
                            $isOrderCompleted = ($currentStatus === 'pesanan_selesai');
                            $options = [];

                            if ($order->is_pickup_delivery) {
                                switch ($currentStatus) {
                                    case 'pesanan_diterima':
                                        $options = ['pesanan_diterima' => 'Diterima (Antrean Awal)', 'pesanan_dijemput' => 'Dijemput (Kurir dalam Perjalanan)']; break;
                                    case 'pesanan_dijemput':
                                        $options = ['pesanan_dijemput' => 'Dijemput (Kurir dalam Perjalanan)', 'pesanan_diproses' => 'Diproses (Pencucian/Pengerjaan)']; break;
                                    case 'pesanan_diproses':
                                        $options = ['pesanan_diproses' => 'Diproses (Pencucian/Pengerjaan)', 'pesanan_siap' => 'Siap (Selesai Packing)']; break;
                                    case 'pesanan_siap':
                                        $options = ['pesanan_siap' => 'Siap (Selesai Packing)', 'pesanan_diantar' => 'Diantar (Kurir Menuju Pelanggan)']; break;
                                    case 'pesanan_diantar':
                                        $options = ['pesanan_diantar' => 'Diantar (Kurir Menuju Pelanggan)', 'pesanan_selesai' => 'Selesai (Sudah Diterima & Lunas)']; break;
                                    case 'pesanan_selesai':
                                        $options = ['pesanan_selesai' => 'Selesai (Sudah Diterima & Lunas)']; break;
                                }
                            } else {
                                switch ($currentStatus) {
                                    case 'pesanan_diterima':
                                        $options = ['pesanan_diterima' => 'Diterima (Antrean Awal)', 'pesanan_diproses' => 'Diproses (Pencucian/Pengerjaan)']; break;
                                    case 'pesanan_diproses':
                                        $options = ['pesanan_diproses' => 'Diproses (Pencucian/Pengerjaan)', 'pesanan_siap' => 'Siap (Selesai Packing)']; break;
                                    case 'pesanan_siap':
                                        $options = ['pesanan_siap' => 'Siap (Selesai Packing)', 'pesanan_selesai' => 'Selesai (Sudah Diambil & Lunas)']; break;
                                    case 'pesanan_selesai':
                                        $options = ['pesanan_selesai' => 'Selesai (Sudah Diambil & Lunas)']; break;
                                }
                            }
                        @endphp

                        <select name="status" id="status" class="form-control" style="cursor: pointer;" {{ $isOrderCompleted ? 'disabled' : '' }} required>
                            @foreach($options as $value => $label)
                                <option value="{{ $value }}" {{ $currentStatus === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="summary-section">
                        <div class="summary-row">
                            <span>Biaya Laundry (<span id="label-berat">0</span> kg)</span>
                            <span id="text-biaya-laundry">Rp 0</span>
                        </div>
                        <div class="summary-row">
                            <span>Ongkos Kirim (<span id="label-jarak">0</span> km)</span>
                            <span id="text-biaya-ongkir">Rp 0</span>
                        </div>
                        <div class="summary-row total-highlight">
                            <span>Estimasi Total Biaya</span>
                            <span id="text-grand-total">Rp 0</span>
                        </div>
                    </div>

                    @if($isOrderCompleted)
                        <div class="alert-success">
                            Transaksi Selesai. Seluruh data order telah dikunci permanen.
                        </div>
                    @else
                        <button type="submit" class="btn-submit">Simpan Perubahan</button>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hargaPerKg = {{ $order->service->price_per_kg ?? 5000 }};
            const isPickup = {{ $order->is_pickup_delivery ? 'true' : 'false' }};

            const inputWeight = document.getElementById('weight');
            const inputDistance = document.getElementById('distance_km');
            
            const labelBerat = document.getElementById('label-berat');
            const labelJarak = document.getElementById('label-jarak');
            
            const textLaundry = document.getElementById('text-biaya-laundry');
            const textOngkir = document.getElementById('text-biaya-ongkir');
            const textTotal = document.getElementById('text-grand-total');

            function hitungKalkulasiOtomatis() {
                let berat = parseFloat(inputWeight.value) || 0;
                let jarak = parseFloat(inputDistance.value) || 0;
                
                let biayaOngkir = 0;
                if (jarak > 0 && jarak <= 2) {
                    biayaOngkir = 5000;
                } else if (jarak > 2 && jarak <= 5) {
                    biayaOngkir = 10000;
                } else if (jarak > 5 && jarak <= 10) {
                    biayaOngkir = 20000;
                } else if (jarak > 10) {
                    biayaOngkir = 30000;
                }

                if (!isPickup) {
                    biayaOngkir = 0;
                    jarak = 0;
                }

                let totalLaundry = berat * hargaPerKg;
                let grandTotal = totalLaundry + biayaOngkir;

                labelBerat.innerText = berat.toFixed(1);
                labelJarak.innerText = jarak.toFixed(1);

                textLaundry.innerText = 'Rp ' + totalLaundry.toLocaleString('id-ID');
                textOngkir.innerText = 'Rp ' + biayaOngkir.toLocaleString('id-ID');
                textTotal.innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
            }

            inputWeight.addEventListener('input', hitungKalkulasiOtomatis);
            inputDistance.addEventListener('input', hitungKalkulasiOtomatis);

            hitungKalkulasiOtomatis();
        });
    </script>
</body>
</html>