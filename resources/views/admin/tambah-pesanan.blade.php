<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pesanan - MyLaundry Admin</title>
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
        .form-control { width: 100%; padding: 12px 16px; background-color: #fff; border: 1px solid #e2e8f0; border-radius: 8px; color: #333; font-size: 13px; outline: none; }
        .form-control:focus { border-color: #0F4A75; }
        
        .summary-section { margin-top: 30px; margin-bottom: 30px; background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px dashed #cbd5e1; }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 13px; font-weight: 600; color: #333; }
        
        .btn-submit { width: 100%; padding: 12px; background-color: #2b6cb0; color: white; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; text-align: center; text-decoration: none; display: block; }
        .btn-submit:hover { background-color: #1e4e8c; }
        
        .back-link { display: inline-block; margin-bottom: 20px; color: white; text-decoration: none; font-size: 14px; }
        .back-link:hover { text-decoration: underline; }

        .error-message { color: #e53e3e; font-size: 11px; margin-top: 5px; font-weight: 500; }
    </style>
</head>
<body>
    <div>
        <a href="{{ route('admin.dashboard') }}" class="back-link">&larr; Kembali</a>
        <div class="container">
            <div class="header-section">
                <div class="header-title">
                    <h2>Tambah Pesanan Baru</h2>
                    <p>Pendaftaran cucian langsung di outlet (Walk-In)</p>
                </div>
            </div>

            <div class="form-card">
                @if ($errors->any())
                    <div style="background-color: #fff5f5; color: #c53030; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 13px;">
                        <ul style="margin: 0; padding-left: 20px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.orders.storeWalkIn') }}" method="POST">
    @csrf
    
    <div class="form-group">
        <label for="customer_name">Nama Pelanggan</label>
        <input type="text" name="customer_name" id="customer_name" class="form-control" 
               value="{{ old('customer_name') }}" placeholder="Masukkan nama pelanggan" required>
        @error('customer_name') <div class="error-message">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
        <label for="service_id">Jenis Layanan</label>
        <select name="service_id" id="service_id" class="form-control" style="cursor: pointer;" required>
            <option value="">-- Pilih Layanan Laundry --</option>
            @foreach($services as $service)
                <option value="{{ $service->id }}" 
                        data-price="{{ $service->price_per_kg }}" 
                        data-minutes="{{ $service->estimated_minutes }}"
                        {{ old('service_id') == $service->id ? 'selected' : '' }}>
                    {{ $service->name }} (Rp {{ number_format($service->price_per_kg, 0, ',', '.') }}/Kg)
                </option>
            @endforeach
        </select>
        @error('service_id') <div class="error-message">{{ $service_id }}</div> @enderror
    </div>

    <div class="form-group">
        <label for="perfume_id">Pilihan Parfum</label>
        <select name="perfume_id" id="perfume_id" class="form-control" style="cursor: pointer;" required>
            <option value="">-- Pilih Aroma Parfum --</option>
            @foreach($perfumes as $perfume)
                <option value="{{ $perfume->id }}" {{ old('perfume_id') == $perfume->id ? 'selected' : '' }}>
                    {{ $perfume->name }}
                </option>
            @endforeach
        </select>
        @error('perfume_id') <div class="error-message">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
        <label for="weight">Input Berat Timbangan (Kg)</label>
        <input type="number" step="0.1" min="0.1" max="5" name="weight" id="weight" class="form-control" 
               value="{{ old('weight') }}" placeholder="Contoh: 3.5" required>
        <small style="color: #718096; font-size: 11px; display: block; margin-top: 4px;">Maksimal berat yang diizinkan adalah 5 Kg.</small>
        @error('weight') <div class="error-message">{{ $message }}</div> @enderror
    </div>

    <div class="summary-section">
        <div class="summary-row">
            <span>Harga / Kg</span>
            <span id="text-price-per-kg">Rp 0</span>
        </div>
        <div class="summary-row" style="border-top: 1px solid #e2e8f0; padding-top: 10px; margin-top: 5px; font-size: 15px; color: #2b6cb0;">
            <span>Total Harga</span>
            <span id="text-total-price">Rp 0</span>
        </div>
    </div>

    <button type="submit" class="btn-submit">Simpan Pesanan</button>
</form>
                </form>
            </div>
        </div>
    </div>

    <script>
    // Ambil elemen input form
    const serviceSelect = document.getElementById('service_id');
    const weightInput = document.getElementById('weight');
    
    // Ambil elemen teks tempat menampilkan hasil kalkulasi
    const textPricePerKg = document.getElementById('text-price-per-kg');
    const textDuration = document.getElementById('text-duration');
    const textTotalPrice = document.getElementById('text-total-price');

    function calculateForm() {
        // Keamanan: Cek jika elemen select belum mendeteksi opsi yang dipilih
        if (!serviceSelect || serviceSelect.selectedIndex === -1) return;

        const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
        
        // Ambil data dari atribut 'data-price' dan 'data-minutes' di option
        const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        const minutes = parseInt(selectedOption.getAttribute('data-minutes')) || 0;
        
        // Ambil nilai berat, ganti tanda koma (,) menjadi titik (.) agar terbaca sebagai desimal di JS
        let weightVal = weightInput.value.replace(',', '.');
        const weight = parseFloat(weightVal) || 0;

        // 1. Hitung Total Harga (Berat x Harga per Kg)
        const total = price * weight;

        // 2. Hitung Estimasi Waktu (Menit diubah ke Jam dengan pembulatan ke atas)
        let durationText = "-";
        if (minutes > 0) {
            const hours = Math.ceil(minutes / 60);
            durationText = `+${hours} Jam`;
        }

        // 3. Cetak ke HTML dengan format mata uang Rupiah Indonesia (id-ID)
        textPricePerKg.innerText = price > 0 ? 'Rp ' + price.toLocaleString('id-ID') : 'Rp 0';
        textDuration.innerText = durationText;
        textTotalPrice.innerText = total > 0 ? 'Rp ' + total.toLocaleString('id-ID') : 'Rp 0';
    }

    // Pasang Event Listener agar kalkulasi berjalan SETIAP kali ada ketikan atau perubahan
    serviceSelect.addEventListener('change', calculateForm);
    weightInput.addEventListener('input', calculateForm);
    weightInput.addEventListener('change', calculateForm);

    // Jalankan kalkulasi sekali saat halaman pertama kali dimuat (menangani old value)
    window.addEventListener('DOMContentLoaded', calculateForm);
</script>
</body>
</html>