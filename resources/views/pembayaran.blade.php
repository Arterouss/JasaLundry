<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Metode Pembayaran - MyLaundry</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background-color: #1a1a1a; color: #fff; padding: 40px; }
        
        .navbar { display: flex; justify-content: space-between; margin-bottom: 30px; align-items: center; max-width: 1000px; margin: 0 auto 30px auto; }
        .nav-links a { margin-left: 20px; text-decoration: none; color: #bbd8f0; font-weight: 500; font-size: 14px; }
        .nav-links a:hover { text-decoration: underline; }

        .container { max-width: 1000px; margin: 0 auto; display: grid; grid-template-columns: 1.5fr 1fr; gap: 30px; }
        
        /* Left Section */
        .form-section { background: white; color: #333; padding: 30px; border-radius: 12px; }
        
        .section-title { font-size: 16px; font-weight: 600; color: #0F4A75; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .section-title svg { width: 20px; height: 20px; fill: #0F4A75; }

        .payment-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 25px; }
        .payment-card { border: 1px solid #bbd8f0; border-radius: 12px; padding: 20px; position: relative; cursor: pointer; display: flex; flex-direction: column; align-items: center; text-align: center; }
        .payment-card.active { border-color: #0F4A75; background-color: #f5f9fc; }
        .payment-card h4 { font-size: 15px; color: #0F4A75; margin-bottom: 5px; }
        .payment-card p { font-size: 12px; color: #666; }
        
        .check-icon { position: absolute; top: 15px; right: 15px; width: 20px; height: 20px; background-color: #0F4A75; border-radius: 50%; display: flex; align-items: center; justify-content: center; opacity: 0; }
        .payment-card.active .check-icon { opacity: 1; }
        .check-icon::after { content: '✓'; color: white; font-size: 12px; }

        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
        .form-group label { display: block; font-size: 13px; color: #0F4A75; margin-bottom: 8px; font-weight: 500; }
        .form-control { width: 100%; padding: 12px 16px; background-color: #2C2C2C; border: 1px solid #2C2C2C; border-radius: 8px; color: white; font-size: 14px; outline: none; }
        .form-control:focus { border-color: #0F4A75; }
        textarea.form-control { resize: vertical; min-height: 100px; }

        /* Right Section */
        .summary-section { background-color: #bbd8f0; border-radius: 12px; padding: 30px; color: #0F4A75; height: fit-content; }
        .summary-title { font-size: 18px; font-weight: 600; margin-bottom: 20px; }
        
        .price-breakdown { padding-bottom: 20px; margin-bottom: 20px; }
        .price-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 13px; }
        
        .total-section { border-top: 1px solid rgba(15, 74, 117, 0.2); padding-top: 20px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; }
        .total-section h3 { font-size: 18px; font-weight: 700; }
        .total-section .price { font-size: 20px; font-weight: 700; }

        .summary-info { background: white; border-radius: 8px; padding: 20px; margin-bottom: 25px; }
        .info-row { display: flex; gap: 10px; margin-bottom: 12px; font-size: 13px; color: #333; }
        .info-row:last-child { margin-bottom: 0; }
        .info-row svg { width: 16px; height: 16px; fill: #0F4A75; flex-shrink: 0; }

        .btn-submit { width: 100%; padding: 15px; background-color: #92bce6; color: white; border: none; border-radius: 8px; font-size: 15px; font-weight: 600; cursor: pointer; text-align: center; transition: 0.3s; text-decoration: none; display: block; }
        .btn-submit:hover { background-color: #0F4A75; }

        @media (max-width: 768px) {
            .container { grid-template-columns: 1fr; }
            .form-row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h2 style="color: #bbd8f0;">MyLaundry (Pembayaran)</h2>
        <div class="nav-links">
            <a href="{{ url('/pesan') }}">Kembali</a>
        </div>
    </div>

    <div class="container">
        <!-- Left Section -->
        <div class="form-section">
            <div class="section-title">
                <svg viewBox="0 0 24 24"><path d="M21 4H3c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h18c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H3V6h18v12zm-9-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3zm0 4.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/></svg>
                Metode Pembayaran
            </div>

            <div class="payment-grid">
                <div class="payment-card">
                    <div class="check-icon"></div>
                    <svg viewBox="0 0 24 24" style="width: 30px; fill: #0F4A75; margin-bottom: 10px;"><path d="M4 10h3v7H4zM10.5 10h3v7h-3zM2 19h20v3H2zM17 10h3v7h-3zM12 1L2 6v2h20V6z"/></svg>
                    <h4>Transfer Bank</h4>
                    <p>BCA, BRI, Mandiri</p>
                </div>
                <div class="payment-card">
                    <div class="check-icon"></div>
                    <svg viewBox="0 0 24 24" style="width: 30px; fill: #0F4A75; margin-bottom: 10px;"><path d="M17 4H7V3h10v1zm4 3H3c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h18c1.1 0 2-.9 2-2V9c0-1.1-.9-2-2-2zm0 12H3V9h18v10zm-5-5.5c0 1.38-1.12 2.5-2.5 2.5S11 14.88 11 13.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5z"/></svg>
                    <h4>E-Wallet</h4>
                    <p>OVO, Dana, Gopay</p>
                </div>
                <div class="payment-card active">
                    <div class="check-icon"></div>
                    <svg viewBox="0 0 24 24" style="width: 30px; fill: #0F4A75; margin-bottom: 10px;"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>
                    <h4>Tunai</h4>
                    <p>Bayar ditempat</p>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" class="form-control" value="Nopal">
                </div>
                <div class="form-group">
                    <label>Nomor HP</label>
                    <input type="text" class="form-control" value="08123123123">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Alamat</label>
                    <input type="text" class="form-control" value="Jl. Mendut Mendut, 20 Ngawi">
                </div>
                <div class="form-group">
                    <label>Waktu Pengantara</label>
                    <input type="text" class="form-control" value="13:00 - Minggu, 30 Februari 2026">
                </div>
            </div>

            <div class="form-group">
                <label>Catatan Tambahan</label>
                <textarea class="form-control"></textarea>
            </div>
        </div>

        <!-- Right Section -->
        <div class="summary-section">
            <h3 class="summary-title">Ringkasan Pembayaran</h3>
            
            <div class="price-breakdown">
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
            </div>

            <div class="total-section">
                <h3>Total</h3>
                <div class="price">Rp 15.000</div>
            </div>

            <div class="summary-info">
                <div class="info-row">
                    <svg viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20a2 2 0 0 0 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10z"/></svg>
                    <span>Minggu, 30 Februari 2026</span>
                </div>
                <div class="info-row">
                    <svg viewBox="0 0 24 24"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>
                    <span>13:00</span>
                </div>
                <div class="info-row">
                    <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 0 1 0-5 2.5 2.5 0 0 1 0 5z"/></svg>
                    <span>Jl. Mendut Mendut, 20 Ngawi</span>
                </div>
            </div>

            <a href="{{ url('/dashboard') }}" class="btn-submit">
                Bayar Sekarang
            </a>
        </div>
    </div>
</body>
</html>
