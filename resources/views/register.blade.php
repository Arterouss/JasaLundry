<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - MyLaundry</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { display: flex; height: 100vh; background-color: #fff; }

        /* Left Panel */
        .left-panel {
            width: 50%; background-color: #0F4A75; color: white; padding: 40px 60px;
            display: flex; flex-direction: column; justify-content: space-between;
        }
        .brand { display: flex; align-items: center; gap: 15px; margin-bottom: 30px; }
        .brand-icon { width: 48px; height: 48px; background-color: white; border-radius: 12px; display: flex; align-items: center; justify-content: center; }
        .brand-icon svg { width: 24px; height: 24px; fill: #0F4A75; }
        .brand-name { font-size: 24px; font-weight: 600; }
        .hero-text h1 { font-size: 36px; font-weight: 700; line-height: 1.3; margin-bottom: 20px; }
        .hero-text p { color: #A9C2D5; font-size: 16px; line-height: 1.5; margin-bottom: 30px; max-width: 90%; }
        .feature-list { list-style: none; display: flex; flex-direction: column; gap: 12px; }
        .feature-list li { display: flex; align-items: center; gap: 10px; font-size: 15px; color: #E2E8F0; }
        .feature-list li::before { content: '•'; font-size: 20px; color: #E2E8F0; }
        .footer-text { font-size: 13px; color: #A9C2D5; }

        /* Right Panel */
        .right-panel {
            width: 50%; display: flex; align-items: center; justify-content: center; padding: 40px; overflow-y: auto;
        }
        .login-container { width: 100%; max-width: 420px; padding: 20px 0; }
        .login-header { margin-bottom: 30px; }
        .login-header h2 { font-size: 32px; font-weight: 600; color: #1a1a1a; margin-bottom: 8px; }
        .login-header p { color: #666; font-size: 15px; }

        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 13px; color: #0F4A75; margin-bottom: 6px; font-weight: 500; }
        .form-control {
            width: 100%; padding: 12px 16px; background-color: #fff; border: 1px solid #ccc;
            border-radius: 8px; color: #333; font-size: 14px; outline: none; transition: border-color 0.3s;
        }
        .form-control::placeholder { color: #999; }
        .form-control:focus { border-color: #0F4A75; }

        .form-options { display: flex; align-items: center; margin-bottom: 20px; font-size: 13px; }
        .checkbox-group { display: flex; align-items: center; gap: 8px; color: #444; }
        .checkbox-group input { accent-color: #0F4A75; width: 16px; height: 16px; }

        .btn { width: 100%; padding: 14px; border-radius: 8px; font-size: 15px; font-weight: 500; cursor: pointer; text-align: center; text-decoration: none; display: inline-block; border: none; }
        .btn-primary { background-color: #fff; color: #1a1a1a; border: 1px solid #ccc; transition: background 0.3s; }
        .btn-primary:hover { background-color: #f8f9fa; }
        
        .divider { display: flex; align-items: center; text-align: center; margin: 20px 0; color: #999; font-size: 13px; }
        .divider::before, .divider::after { content: ''; flex: 1; border-bottom: 1px solid #ddd; }
        .divider::before { margin-right: 15px; }
        .divider::after { margin-left: 15px; }

        .btn-google { background-color: white; color: #1a1a1a; border: 1px solid #ccc; display: flex; align-items: center; justify-content: center; gap: 10px; }
        .btn-google:hover { background-color: #f8f9fa; }
        .btn-google svg { width: 18px; height: 18px; }

        .register-text { text-align: center; margin-top: 20px; font-size: 14px; color: #666; }
        .register-text a { color: #3b82f6; text-decoration: none; font-weight: 500; }

        @media (max-width: 900px) {
            .left-panel { display: none; }
            .right-panel { width: 100%; }
        }
    </style>
</head>
<body>
    <div class="left-panel">
        <div>
            <div class="brand">
                <div class="brand-icon">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19.5 8.5L18.5 20.5C18.5 21.3 17.8 22 17 22H7C6.2 22 5.5 21.3 5.5 20.5L4.5 8.5C4.5 7.7 5.2 7 6 7H18C18.8 7 19.5 7.7 19.5 8.5Z" fill="none" stroke="#0F4A75" stroke-width="2" stroke-linejoin="round"/>
                        <path d="M9 13H15" stroke="#0F4A75" stroke-width="2" stroke-linecap="round"/>
                        <path d="M10 17H14" stroke="#0F4A75" stroke-width="2" stroke-linecap="round"/>
                        <path d="M18.7 7L17.5 3.5C17.2 2.6 16.4 2 15.5 2H8.5C7.6 2 6.8 2.6 6.5 3.5L5.3 7" fill="none" stroke="#0F4A75" stroke-width="2" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="brand-name">MyLaundry</div>
            </div>
            <div class="hero-text">
                <h1>Laundry Bersih,<br>Hidup Lebih Mudah</h1>
                <p>Pesan layanan laundry kapan saja, pantau status cucian Anda secara real-time</p>
                <ul class="feature-list">
                    <li>Penjemputan & pengantaran ke rumah</li>
                    <li>Notifikasi status cucian real-time</li>
                    <li>Berbagai pilihan jenis layanan</li>
                    <li>Pembayaran mudah & aman</li>
                </ul>
            </div>
        </div>
        <div class="footer-text">&copy; 2026 LaundryKu - Bersih & Terpercaya</div>
    </div>

    <div class="right-panel">
        <div class="login-container">
            <div class="login-header">
                <h2>Buat Akun</h2>
                <p>Daftar untuk mulai menggunakan MyLaundry</p>
            </div>

            <form action="{{ url('/login') }}" method="GET">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" class="form-control" placeholder="Masukkan Nama Lengkap">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control" placeholder="Masukkan Email">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" class="form-control" placeholder="Masukkan Password">
                </div>
                <div class="form-group">
                    <label>Konfirmasi Password</label>
                    <input type="password" class="form-control" placeholder="Ulangi Password">
                </div>

                <div class="form-options">
                    <label class="checkbox-group">
                        <input type="checkbox" checked>
                        Saya setuju dengan syarat dan ketentuan
                    </label>
                </div>

                <a href="{{ url('/login') }}" class="btn btn-primary" style="display: block; line-height: normal;">Daftar</a>
            </form>

            <div class="divider">atau</div>

            <button type="button" class="btn btn-google">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Masuk dengan Google
            </button>

            <div class="register-text">
                Sudah Punya Akun? <a href="{{ url('/login') }}">Login Sekarang</a>
            </div>
        </div>
    </div>
</body>
</html>
