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
        
        .navbar { display: flex; justify-content: space-between; margin-bottom: 30px; align-items: center; max-width: 1000px; margin: 0 auto 30px auto; }
        .nav-links a { margin-left: 20px; text-decoration: none; color: #bbd8f0; font-weight: 500; font-size: 14px; }
        .nav-links a:hover { text-decoration: underline; }

        .container { max-width: 1000px; margin: 0 auto; display: grid; grid-template-columns: 1.5fr 1fr; gap: 30px; }
        
        /* Left Section */
        .form-section { background: white; color: #333; padding: 30px; border-radius: 12px; margin-bottom: 20px; }
        
        .section-title { font-size: 16px; font-weight: 600; color: #0F4A75; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .section-title svg { width: 18px; height: 18px; fill: #0F4A75; }

        .service-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 25px; }
        .service-card { border: 1px solid #bbd8f0; border-radius: 12px; padding: 20px; position: relative; cursor: pointer; }
        .service-card.active { border-color: #0F4A75; background-color: #f5f9fc; }
        .service-card h4 { font-size: 15px; color: #0F4A75; margin-top: 10px; margin-bottom: 5px; }
        .service-card p { font-size: 13px; color: #666; }
        .service-icon { width: 24px; height: 24px; margin-bottom: 10px; }
        .service-icon svg { width: 100%; height: 100%; fill: #bbd8f0; }
        .service-card.active .service-icon svg { fill: #0F4A75; }
        
        .check-icon { position: absolute; top: 15px; right: 15px; width: 20px; height: 20px; background-color: #0F4A75; border-radius: 50%; display: flex; align-items: center; justify-content: center; opacity: 0; }
        .service-card.active .check-icon { opacity: 1; }
        .check-icon::after { content: '✓'; color: white; font-size: 12px; }

        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
        .form-group label { display: block; font-size: 13px; color: #0F4A75; margin-bottom: 8px; font-weight: 500; }
        .form-control { width: 100%; padding: 12px 16px; background-color: #2C2C2C; border: 1px solid #2C2C2C; border-radius: 8px; color: white; font-size: 14px; outline: none; }
        .form-control:focus { border-color: #0F4A75; }
        textarea.form-control { resize: vertical; min-height: 100px; }

        /* Right Section */
        .summary-section { background-color: #bbd8f0; border-radius: 12px; padding: 30px; color: #0F4A75; height: fit-content; }
        .summary-title { font-size: 18px; font-weight: 600; margin-bottom: 20px; }
        
        .summary-label { font-size: 13px; margin-bottom: 10px; font-weight: 500; }
        .badge-list { display: flex; gap: 10px; margin-bottom: 25px; flex-wrap: wrap; }
        .badge { background-color: #92bce6; color: #0F4A75; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 500; }

        .price-breakdown { border-top: 1px solid rgba(15, 74, 117, 0.2); border-bottom: 1px solid rgba(15, 74, 117, 0.2); padding: 20px 0; margin-bottom: 20px; }
        .price-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 13px; }
        .price-row.total { font-weight: 700; font-size: 18px; margin-bottom: 0; margin-top: 10px; }

        .summary-info { background: white; border-radius: 8px; padding: 20px; margin-bottom: 25px; }
        .info-row { display: flex; gap: 10px; margin-bottom: 12px; font-size: 13px; color: #333; }
        .info-row:last-child { margin-bottom: 0; }
        .info-row svg { width: 16px; height: 16px; fill: #0F4A75; flex-shrink: 0; }

        .btn-submit { width: 100%; padding: 15px; background-color: #92bce6; color: white; border: none; border-radius: 8px; font-size: 15px; font-weight: 600; cursor: pointer; display: flex; justify-content: center; align-items: center; gap: 10px; transition: 0.3s; text-decoration: none; }
        .btn-submit:hover { background-color: #0F4A75; }
        .btn-submit svg { width: 18px; height: 18px; fill: white; }

        @media (max-width: 768px) {
            .container { grid-template-columns: 1fr; }
            .form-row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h2 style="color: #bbd8f0;">MyLaundry (Pesan)</h2>
        <div class="nav-links">
            <a href="{{ url('/dashboard') }}">Kembali ke Dashboard</a>
        </div>
    </div>

    <div class="container">
        <!-- Left Section -->
        <div class="left-col">
            <div class="form-section">
                <div class="section-title">
                    <svg viewBox="0 0 24 24"><path d="M19.5 8.5L18.5 20.5C18.5 21.3 17.8 22 17 22H7C6.2 22 5.5 21.3 5.5 20.5L4.5 8.5C4.5 7.7 5.2 7 6 7H18C18.8 7 19.5 7.7 19.5 8.5Z" fill="none" stroke="#0F4A75" stroke-width="2" stroke-linejoin="round"/><path d="M9 13H15" stroke="#0F4A75" stroke-width="2" stroke-linecap="round"/><path d="M10 17H14" stroke="#0F4A75" stroke-width="2" stroke-linecap="round"/><path d="M18.7 7L17.5 3.5C17.2 2.6 16.4 2 15.5 2H8.5C7.6 2 6.8 2.6 6.5 3.5L5.3 7" fill="none" stroke="#0F4A75" stroke-width="2" stroke-linejoin="round"/></svg>
                    Jenis Layanan
                </div>

                <div class="service-grid">
                    <div class="service-card active">
                        <div class="check-icon"></div>
                        <div class="service-icon"><svg viewBox="0 0 24 24"><path d="M19.5 8.5L18.5 20.5C18.5 21.3 17.8 22 17 22H7C6.2 22 5.5 21.3 5.5 20.5L4.5 8.5C4.5 7.7 5.2 7 6 7H18C18.8 7 19.5 7.7 19.5 8.5Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M9 13H15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></div>
                        <h4>Cuci + Kering</h4>
                        <p>Rp 5.000/kg</p>
                    </div>
                    <div class="service-card">
                        <div class="check-icon"></div>
                        <div class="service-icon"><svg viewBox="0 0 24 24"><path d="M19.5 8.5L18.5 20.5C18.5 21.3 17.8 22 17 22H7C6.2 22 5.5 21.3 5.5 20.5L4.5 8.5C4.5 7.7 5.2 7 6 7H18C18.8 7 19.5 7.7 19.5 8.5Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg></div>
                        <h4>Setrika</h4>
                        <p>Rp 5.000/kg</p>
                    </div>
                    <div class="service-card">
                        <div class="check-icon"></div>
                        <div class="service-icon"><svg viewBox="0 0 24 24"><path d="M19.5 8.5L18.5 20.5C18.5 21.3 17.8 22 17 22H7C6.2 22 5.5 21.3 5.5 20.5L4.5 8.5C4.5 7.7 5.2 7 6 7H18C18.8 7 19.5 7.7 19.5 8.5Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg></div>
                        <h4>Cuci + Setrika</h4>
                        <p>Rp 5.000/kg</p>
                    </div>
                    <div class="service-card active">
                        <div class="check-icon"></div>
                        <div class="service-icon"><svg viewBox="0 0 24 24"><path d="M19.5 8.5L18.5 20.5C18.5 21.3 17.8 22 17 22H7C6.2 22 5.5 21.3 5.5 20.5L4.5 8.5C4.5 7.7 5.2 7 6 7H18C18.8 7 19.5 7.7 19.5 8.5Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg></div>
                        <h4>Express (1 hari)</h4>
                        <p>Rp 5.000/kg</p>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Berat Cucian (kg)</label>
                        <input type="text" class="form-control" value="3">
                    </div>
                    <div class="form-group">
                        <label>Parfume Cucian</label>
                        <input type="text" class="form-control" value="Rose">
                    </div>
                </div>

                <div class="form-group">
                    <label>Catatan Tambahan</label>
                    <textarea class="form-control" placeholder="Masukan catatan...">Mana MSG nya</textarea>
                </div>
            </div>

            <div class="form-section">
                <div class="form-row">
                    <div class="form-group">
                        <label>Tanggal Jemput</label>
                        <input type="date" class="form-control" value="2026-02-30">
                    </div>
                    <div class="form-group">
                        <label>Waktu</label>
                        <input type="time" class="form-control" value="13:00">
                    </div>
                </div>
                <div class="form-group">
                    <label>Alamat Pengiriman</label>
                    <input type="text" class="form-control" value="Jl. Mendut Mendut, 20 Ngawi">
                </div>
            </div>
        </div>

        <!-- Right Section -->
        <div class="summary-section">
            <h3 class="summary-title">Ringkasan Pesanan</h3>
            
            <div class="summary-label">Layanan dipilih:</div>
            <div class="badge-list">
                <div class="badge">Cuci + Kering</div>
                <div class="badge">Express (1 hari)</div>
            </div>

            <div class="price-breakdown">
                <div class="price-row">
                    <span>Berat</span>
                    <span>3 kg</span>
                </div>
                <div class="price-row">
                    <span>Cuci + Kering</span>
                    <span>Rp 5.000/kg</span>
                </div>
                <div class="price-row">
                    <span>Express (1 hari)</span>
                    <span>Rp 5.000/kg</span>
                </div>
                <div class="price-row">
                    <span>Jemput & Antar</span>
                    <span>Rp 5.000</span>
                </div>
                <div class="price-row total">
                    <span>Total</span>
                    <span>Rp 15.000</span>
                </div>
            </div>

            <div class="summary-info">
                <div class="info-row">
                    <svg viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20a2 2 0 0 0 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10z"/></svg>
                    <span>Minggu, 30 Februari 2026</span>
                </div>
                <div class="info-row">
                    <svg viewBox="0 0 24 24"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>
                    <span>13:00 - 28:00</span>
                </div>
                <div class="info-row">
                    <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 0 1 0-5 2.5 2.5 0 0 1 0 5z"/></svg>
                    <span>Jl. Mendut Mendut, 20 Ngawi</span>
                </div>
            </div>

            <a href="{{ url('/pembayaran') }}" class="btn-submit">
                <svg viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                Buat Pesanan
            </a>
        </div>
    </div>
</body>
</html>
