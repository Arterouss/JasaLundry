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
        
        .summary-section { margin-top: 30px; margin-bottom: 30px; }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 13px; font-weight: 600; color: #333; }
        
        .btn-submit { width: 100px; padding: 10px; background-color: #2b6cb0; color: white; border: none; border-radius: 6px; font-size: 13px; font-weight: 500; cursor: pointer; text-align: center; text-decoration: none; display: block; margin: 0 auto; }
        .btn-submit:hover { background-color: #1e4e8c; }
        
        .back-link { display: inline-block; margin-bottom: 20px; color: white; text-decoration: none; font-size: 14px; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div>
        <a href="{{ url('/admin/dashboard') }}" class="back-link">&larr; Kembali</a>
        <div class="container">
            <div class="header-section">
                <div class="header-title">
                    <h2>Tambah Pesanan</h2>
                    <p>Tambah Pesanan Pelanggan</p>
                </div>
            </div>

            <div class="form-card">
                <form action="#">
                    <div class="form-group">
                        <label>Nama Pelanggan</label>
                        <input type="text" class="form-control" value="Raka">
                    </div>

                    <div class="form-group">
                        <label>Jenis Layanan</label>
                        <input type="text" class="form-control" value="Cuci Kering">
                    </div>

                    <div class="form-group">
                        <label>Input Berat</label>
                        <input type="text" class="form-control" value="20 kg">
                    </div>

                    <div class="summary-section">
                        <div class="summary-row">
                            <span>Berat</span>
                            <span>Rp 120.000</span>
                        </div>
                        <div class="summary-row">
                            <span>Total Harga</span>
                            <span>Rp 120.000</span>
                        </div>
                        <div class="summary-row">
                            <span>Estimasi Waktu</span>
                            <span>+9 Jam</span>
                        </div>
                    </div>

                    <button type="button" class="btn-submit">Tambah</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
