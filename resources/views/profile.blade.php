<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Saya - MyLaundry</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background-color: #1a1a1a; color: #333; padding: 40px; display: flex; flex-direction: column; align-items: center; }
        
        .navbar { display: flex; justify-content: space-between; margin-bottom: 30px; align-items: center; width: 100%; max-width: 600px; }
        .nav-links a { text-decoration: none; color: #bbd8f0; font-weight: 500; font-size: 14px; }
        .nav-links a:hover { text-decoration: underline; }

        .profile-container { background: white; width: 100%; max-width: 600px; border-radius: 12px; padding: 40px; }
        
        .header { margin-bottom: 30px; }
        .header h2 { font-size: 24px; font-weight: 600; color: #1a1a1a; margin-bottom: 5px; }
        .header p { color: #666; font-size: 14px; }

        .avatar-section { display: flex; flex-direction: column; align-items: center; margin-bottom: 30px; }
        .avatar-circle { width: 100px; height: 100px; background-color: #d1d5db; border: 4px solid #bbd8f0; border-radius: 50%; margin-bottom: 15px; display: flex; align-items: center; justify-content: center; overflow: hidden; }
        .avatar-circle svg { width: 60px; height: 60px; fill: #6b7280; margin-top: 15px; }
        .btn-upload { background-color: #2b6cb0; color: white; border: none; padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 500; cursor: pointer; }

        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; font-size: 13px; color: #0F4A75; margin-bottom: 8px; font-weight: 500; }
        .form-control { width: 100%; padding: 12px 16px; background-color: #fff; border: 1px solid #ddd; border-radius: 8px; color: #333; font-size: 14px; outline: none; transition: 0.3s; }
        .form-control:focus { border-color: #0F4A75; }

        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

        .btn-submit { width: 100%; padding: 14px; margin-top: 10px; background-color: #fff; color: #1a1a1a; border: 1px solid #ccc; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: 0.3s; }
        .btn-submit:hover { background-color: #f5f5f5; }

        @media (max-width: 600px) {
            .form-row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h2 style="color: #bbd8f0;">MyLaundry (Profile)</h2>
        <div class="nav-links">
            <a href="{{ url('/dashboard') }}">Kembali ke Dashboard</a>
        </div>
    </div>

    <div class="profile-container">
        <div class="header">
            <h2>Profile Saya</h2>
            <p>Perbarui informasi akun Anda</p>
        </div>

        <div class="avatar-section">
            <div class="avatar-circle">
                <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
            </div>
            <button class="btn-upload">Upload Foto</button>
        </div>

        <form action="#" method="POST">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" class="form-control" value="Bayu Saputra">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" value="bayu@gmail.com">
            </div>

            <div class="form-group">
                <label>Nomor HP</label>
                <input type="text" class="form-control" value="081234567890">
            </div>

            <div class="form-group">
                <label>Alamat</label>
                <input type="text" class="form-control" value="Jl. Mendut Mendut, Ngawi">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Password Baru</label>
                    <input type="password" class="form-control" placeholder="***************">
                </div>
                <div class="form-group">
                    <label>Konfirmasi Password</label>
                    <input type="password" class="form-control" placeholder="***************">
                </div>
            </div>

            <button type="button" class="btn-submit">Perbarui</button>
        </form>
    </div>
</body>
</html>
