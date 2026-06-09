<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Layanan - MyLaundry</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background-color: #1a1a1a; color: #fff; padding: 40px; }
        
        .navbar { display: flex; justify-content: space-between; margin-bottom: 30px; align-items: center; max-width: 700px; margin: 0 auto 30px auto; }
        .nav-links a { text-decoration: none; color: #bbd8f0; font-weight: 500; font-size: 14px; }
        .nav-links a:hover { text-decoration: underline; }

        .container { max-width: 700px; margin: 0 auto; }
        
        .form-section { background: white; color: #333; padding: 30px; border-radius: 12px; margin-bottom: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        
        .section-title { font-size: 16px; font-weight: 600; color: #0F4A75; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .section-title svg { width: 18px; height: 18px; fill: #0F4A75; }

        /* Grid Layanan Utama */
        .service-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px; margin-bottom: 25px; }
        .service-card { border: 1px solid #bbd8f0; border-radius: 12px; padding: 15px; position: relative; cursor: pointer; transition: 0.2s; }
        .service-card:hover { border-color: #0F4A75; }
        .service-card.active { border-color: #0F4A75; background-color: #f5f9fc; }
        .service-card h4 { font-size: 14px; color: #0F4A75; margin-top: 5px; margin-bottom: 5px; }
        .service-card p { font-size: 12px; color: #666; }
        .service-icon { width: 24px; height: 24px; margin-bottom: 5px; }
        .service-icon svg { width: 100%; height: 100%; fill: #bbd8f0; }
        .service-card.active .service-icon svg { fill: #0F4A75; }
        
        .check-icon { position: absolute; top: 12px; right: 12px; width: 18px; height: 18px; background-color: #0F4A75; border-radius: 50%; display: flex; align-items: center; justify-content: center; opacity: 0; }
        .service-card.active .check-icon { opacity: 1; }
        .check-icon::after { content: '✓'; color: white; font-size: 11px; }

        /* Toggle Option Antar Jemput */
        .toggle-card { border: 2px dashed #bbd8f0; border-radius: 12px; padding: 20px; cursor: pointer; display: flex; align-items: center; justify-content: space-between; transition: 0.3s; margin-bottom: 25px; }
        .toggle-card.active { border-style: solid; border-color: #0c85d0; background-color: #f0f8ff; }
        .toggle-info h4 { color: #0F4A75; font-size: 15px; margin-bottom: 4px; }
        .toggle-info p { color: #666; font-size: 13px; }

        /* Switch UI */
        .switch { width: 50px; height: 26px; background: #ccc; border-radius: 50px; position: relative; transition: 0.3s; }
        .switch::after { content: ''; width: 20px; height: 20px; background: white; border-radius: 50%; position: absolute; top: 3px; left: 3px; transition: 0.3s; }
        .toggle-card.active .switch { background: #0F4A75; }
        .toggle-card.active .switch::after { left: 27px; }

        /* Form Grouping & Payment Option */
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 13px; color: #0F4A75; margin-bottom: 8px; font-weight: 500; }
        
        .form-control { width: 100%; padding: 12px 16px; background-color: #f9f9f9; border: 1px solid #ddd; border-radius: 8px; color: #333; font-size: 14px; outline: none; transition: 0.2s; }
        .form-control:focus { border-color: #0F4A75; background-color: #fff; }
        select.form-control { appearance: none; background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='%230F4A75'><path d='M7 10l5 5 5-5z'/></svg>"); background-repeat: no-repeat; background-position: right 12px center; padding-right: 40px; }

        /* Payment Row Selection */
        .payment-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .payment-card { border: 1px solid #ddd; padding: 15px; border-radius: 8px; text-align: center; cursor: pointer; font-size: 14px; font-weight: 500; color: #555; transition: 0.2s; background: #fafafa; }
        .payment-card.active { border-color: #0F4A75; background: #f5f9fc; color: #0F4A75; font-weight: 600; }
        .payment-grid.disabled { opacity: 0.5; pointer-events: none; }

        /* GPS Input Wrapper */
        .gps-wrapper { display: flex; gap: 10px; }
        .btn-gps { background: #0F4A75; color: white; border: none; padding: 0 15px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 5px; font-size: 13px; transition: 0.2s; font-weight: 500; }
        .btn-gps:hover { background: #0b3757; }
        .btn-gps svg { width: 16px; height: 16px; fill: white; }
        .gps-wrapper.disabled { opacity: 0.5; pointer-events: none; }

        .btn-submit { width: 100%; padding: 15px; background-color: #0F4A75; color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; display: flex; justify-content: center; align-items: center; gap: 10px; transition: 0.3s; text-decoration: none; margin-top: 10px; }
        .btn-submit:hover { background-color: #0b3757; box-shadow: 0 4px 12px rgba(15, 74, 117, 0.3); }
        .btn-submit svg { width: 18px; height: 18px; fill: white; }
    </style>
</head>
<body>

    <div class="navbar">
        <h2 style="color: #bbd8f0;">MyLaundry (Pesan)</h2>
        <div class="nav-links">
            <a href="{{ route('customer.dashboard') }}">Kembali ke Dashboard</a>
        </div>
    </div>

    <div class="container">
        <form action="{{ route('customer.orders.store') }}" method="POST" id="orderForm">
            @csrf
            
            <input type="hidden" name="service_id" id="selected_service_id" value="1">
            <input type="hidden" name="is_pickup_delivery" id="is_pickup_delivery" value="0">
            <input type="hidden" name="payment_method" id="selected_payment_method" value="">

            <div class="form-section">
                <div class="section-title">
                    <svg viewBox="0 0 24 24"><path d="M19.5 8.5L18.5 20.5C18.5 21.3 17.8 22 17 22H7C6.2 22 5.5 21.3 5.5 20.5L4.5 8.5C4.5 7.7 5.2 7 6 7H18C18.8 7 19.5 7.7 19.5 8.5Z" fill="none" stroke="#0F4A75" stroke-width="2" stroke-linejoin="round"/><path d="M9 13H15" stroke="#0F4A75" stroke-width="2" stroke-linecap="round"/><path d="M10 17H14" stroke="#0F4A75" stroke-width="2" stroke-linecap="round"/><path d="M18.7 7L17.5 3.5C17.2 2.6 16.4 2 15.5 2H8.5C7.6 2 6.8 2.6 6.5 3.5L5.3 7" fill="none" stroke="#0F4A75" stroke-width="2" stroke-linejoin="round"/></svg>
                    Pilih Jenis Layanan Utama
                </div>

                <div class="service-grid">
                    <div class="service-card active" data-service-id="1">
                        <div class="check-icon"></div>
                        <div class="service-icon"><svg viewBox="0 0 24 24"><path d="M19.5 8.5L18.5 20.5C18.5 21.3 17.8 22 17 22H7C6.2 22 5.5 21.3 5.5 20.5L4.5 8.5C4.5 7.7 5.2 7 6 7H18C18.8 7 19.5 7.7 19.5 8.5Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M9 13H15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></div>
                        <h4>Cuci + Kering</h4>
                        <p>Rp 5.000/kg</p>
                    </div>
                    <div class="service-card" data-service-id="2">
                        <div class="check-icon"></div>
                        <div class="service-icon"><svg viewBox="0 0 24 24"><path d="M19.5 8.5L18.5 20.5C18.5 21.3 17.8 22 17 22H7C6.2 22 5.5 21.3 5.5 20.5L4.5 8.5C4.5 7.7 5.2 7 6 7H18C18.8 7 19.5 7.7 19.5 8.5Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg></div>
                        <h4>Setrika</h4>
                        <p>Rp 5.000/kg</p>
                    </div>
                    <div class="service-card" data-service-id="3">
                        <div class="check-icon"></div>
                        <div class="service-icon"><svg viewBox="0 0 24 24"><path d="M19.5 8.5L18.5 20.5C18.5 21.3 17.8 22 17 22H7C6.2 22 5.5 21.3 5.5 20.5L4.5 8.5C4.5 7.7 5.2 7 6 7H18C18.8 7 19.5 7.7 19.5 8.5Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg></div>
                        <h4>Cuci + Setrika</h4>
                        <p>Rp 5.000/kg</p>
                    </div>
                </div>

                <div class="form-group">
                    <label>Parfum Cucian</label>
                    <select name="perfume_id" class="form-control" required>
                        <option value="" disabled selected>-- Pilih Aroma Parfum --</option>
                        @foreach($perfumes as $perfume)
                            <option value="{{ $perfume->id }}">{{ $perfume->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Catatan Tambahan (Opsional)</label>
                    <textarea name="notes" class="form-control" style="background: #f9f9f9;" placeholder="Contoh: Baju putih mohon dipisah..."></textarea>
                </div>
            </div>

            <div class="form-section">
                
                <div class="toggle-card" id="pickupToggle">
                    <div class="toggle-info">
                        <h4>Layanan Antar Jemput (+Rp 5.000)</h4>
                        <p>Aktifkan jika cucian ingin kami jemput dan antar langsung ke rumahmu.</p>
                    </div>
                    <div class="switch"></div>
                </div>

                <div class="form-group">
                    <label>Alamat GPS Terkini</label>
                    <div class="gps-wrapper disabled" id="gpsWrapper">
                        <input type="text" name="gps_address" id="gps_address" class="form-control" placeholder="Aktifkan Antar Jemput lalu klik 'Ambil Lokasi'" readonly>
                        <button type="button" class="btn-gps" id="btnGetGps">
                            <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5z"/></svg>
                            Ambil Lokasi
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label>Metode Pembayaran</label>
                    <div class="payment-grid disabled" id="paymentGrid">
                        <div class="payment-card" data-method="cash_on_site">Bayar di Tempat (COD)</div>
                        <div class="payment-card" data-method="cashless">Pembayaran Digital (Cashless)</div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <svg viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                Buat Pesanan Sekarang
            </button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const serviceCards = document.querySelectorAll('.service-grid .service-card');
            const selectedServiceInput = document.getElementById('selected_service_id');
            
            const pickupToggle = document.getElementById('pickupToggle');
            const isPickupInput = document.getElementById('is_pickup_delivery');
            const gpsWrapper = document.getElementById('gpsWrapper');
            const gpsAddressInput = document.getElementById('gps_address');
            const btnGetGps = document.getElementById('btnGetGps');
            
            const paymentGrid = document.getElementById('paymentGrid');
            const paymentCards = document.querySelectorAll('.payment-card');
            const selectedPaymentInput = document.getElementById('selected_payment_method');

            // 1. Logika Klik Pilihan Jenis Layanan Utama
            serviceCards.forEach(card => {
                card.addEventListener('click', function() {
                    serviceCards.forEach(c => c.classList.remove('active'));
                    this.classList.add('active');
                    selectedServiceInput.value = this.getAttribute('data-service-id');
                });
            });

            // 2. Logika Toggle Switch Antar Jemput (Enabled / Disabled GPS & Payment)
            pickupToggle.addEventListener('click', function() {
                this.classList.toggle('active');
                const isChecked = this.classList.contains('active');
                
                isPickupInput.value = isChecked ? "1" : "0";

                if (isChecked) {
                    gpsWrapper.classList.remove('disabled');
                    paymentGrid.classList.remove('disabled');
                    gpsAddressInput.removeAttribute('readonly');
                    gpsAddressInput.placeholder = "Mencari koordinat lokasi...";
                    getRealtimeLocation(); // Trigger pencarian lokasi otomatis saat dinyalakan
                } else {
                    gpsWrapper.classList.add('disabled');
                    paymentGrid.classList.add('disabled');
                    gpsAddressInput.setAttribute('readonly', true);
                    gpsAddressInput.value = "";
                    gpsAddressInput.placeholder = "Aktifkan Antar Jemput lalu klik 'Ambil Lokasi'";
                    
                    // Reset Pilihan Payment
                    paymentCards.forEach(c => c.classList.remove('active'));
                    selectedPaymentInput.value = "";
                }
            });

            // 3. Logika Memilih Kartu Metode Pembayaran
            paymentCards.forEach(card => {
                card.addEventListener('click', function() {
                    paymentCards.forEach(c => c.classList.remove('active'));
                    this.classList.add('active');
                    selectedPaymentInput.value = this.getAttribute('data-method');
                });
            });

            // 4. Integrasi Geolocation API HTML5 untuk mengambil Garis Lintang & Bujur GPS Akurat
            function getRealtimeLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;
                            // Menyimpan string koordinat GPS yang siap dibaca oleh Admin via Google Maps
                            gpsAddressInput.value = `${lat}, ${lng} (Lokasi Terkini)`;
                        },
                        (error) => {
                            gpsAddressInput.value = "";
                            gpsAddressInput.placeholder = "Gagal mengambil lokasi otomatis. Silakan ketik manual.";
                        }
                    );
                } else {
                    alert("Browser kamu tidak mendukung deteksi lokasi otomatis GPS.");
                }
            }

            // Tombol Manual Ambil Ulang Koordinat GPS
            btnGetGps.addEventListener('click', function(e) {
                e.preventDefault();
                gpsAddressInput.value = "Memperbarui lokasi...";
                getRealtimeLocation();
            });

            // Validasi Tambahan Sebelum Form Dikirim ke Backend
            document.getElementById('orderForm').addEventListener('submit', function(e) {
                if (isPickupInput.value === "1") {
                    if (!gpsAddressInput.value.trim()) {
                        e.preventDefault();
                        alert("Harap isi alamat koordinat GPS kamu terlebih dahulu!");
                        return;
                    }
                    if (!selectedPaymentInput.value) {
                        e.preventDefault();
                        alert("Harap pilih salah satu metode pembayaran (COD / Cashless)!");
                        return;
                    }
                }
            });
        });
    </script>
</body>
</html>