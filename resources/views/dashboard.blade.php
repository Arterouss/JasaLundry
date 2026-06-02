<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pelanggan - MyLaundry</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background-color: #f5f7fa; color: #333; padding: 40px 0; }
        .container { max-width: 900px; margin: 0 auto; background: white; padding: 40px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }

        /* Top Header */
        .header-card { background-color: #0F4A75; color: white; border-radius: 12px; padding: 30px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .header-text p { font-size: 16px; color: #A9C2D5; margin-bottom: 5px; }
        .header-text h1 { font-size: 28px; font-weight: 600; }
        .profile-pic { width: 60px; height: 60px; background-color: #ccc; border-radius: 50%; }

        /* Notification */
        .notification-card { background-color: #bbd8f0; border-radius: 12px; padding: 15px 20px; display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
        .notif-left { display: flex; align-items: center; gap: 15px; }
        .notif-icon { width: 40px; height: 40px; background-color: #559bd6; border-radius: 8px; display: flex; align-items: center; justify-content: center; }
        .notif-icon svg { width: 20px; height: 20px; fill: white; }
        .notif-text h3 { font-size: 15px; font-weight: 600; color: #0F4A75; }
        .notif-text p { font-size: 13px; color: #555; }
        .notif-time { font-size: 13px; color: #555; }

        /* Action Bar */
        .action-bar { display: flex; justify-content: flex-end; margin-bottom: 20px; }
        .btn-pesan { background-color: #bbd8f0; color: #0F4A75; font-weight: 600; text-decoration: none; padding: 10px 20px; border-radius: 8px; display: inline-flex; align-items: center; gap: 8px; transition: background 0.3s; }
        .btn-pesan:hover { background-color: #a3c9e8; }

        /* Stats */
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px; }
        .stat-card { background-color: #bbd8f0; text-align: center; padding: 30px 20px; border-radius: 12px; color: #0F4A75; }
        .stat-card h2 { font-size: 40px; font-weight: 700; margin-bottom: 5px; }
        .stat-card p { font-size: 14px; font-weight: 500; }

        /* Timeline Card */
        .timeline-card { background-color: #e5eff8; border-radius: 12px; padding: 25px; border: 1px solid #bbd8f0; }
        .timeline-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .timeline-title { display: flex; align-items: center; gap: 15px; }
        .timeline-title-text h3 { color: #0F4A75; font-size: 16px; margin-bottom: 3px; }
        .timeline-title-text p { font-size: 13px; color: #666; }
        .badge-proses { background-color: #bbd8f0; color: #0F4A75; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }

        /* Timeline steps */
        .timeline-steps { display: flex; justify-content: space-between; position: relative; margin: 0 20px; }
        .timeline-steps::before { content: ''; position: absolute; top: 12px; left: 0; right: 0; height: 2px; background-color: #bbd8f0; z-index: 1; }
        .step { position: relative; z-index: 2; display: flex; flex-direction: column; align-items: center; gap: 10px; }
        .step-circle { width: 26px; height: 26px; background-color: white; border: 2px solid #bbd8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
        .step.active .step-circle { background-color: #0F4A75; border-color: #0F4A75; }
        .step.active .step-circle::after { content: '✓'; color: white; font-size: 14px; }
        .step-label { font-size: 12px; color: #555; }
        .step.active .step-label { color: #0F4A75; font-weight: 600; }
        
        .navbar { display: flex; justify-content: space-between; margin-bottom: 20px; align-items: center; }
        .nav-links a { margin-left: 20px; text-decoration: none; color: #0F4A75; font-weight: 500; font-size: 14px; }
        .nav-links a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <div class="navbar">
            <h2 style="color: #0F4A75;">MyLaundry</h2>
            <div class="nav-links">
                <a href="{{ url('/profile') }}">Profile</a>
                <a href="{{ url('/kelola-pesanan') }}">Kelola Pesanan (Admin)</a>
                <a href="{{ url('/login') }}" style="color: #e53e3e;">Logout</a>
            </div>
        </div>

        <div class="header-card">
            <div class="header-text">
                <p>Selamat Pagi</p>
                <h1>Nama_User</h1>
            </div>
            <a href="{{ url('/profile') }}">
                <div class="profile-pic"></div>
            </a>
        </div>

        <div class="notification-card">
            <div class="notif-left">
                <div class="notif-icon">
                    <svg viewBox="0 0 24 24"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
                </div>
                <div class="notif-text">
                    <h3>Nama_Proses</h3>
                    <p>Kode_pesanan</p>
                </div>
            </div>
            <div class="notif-time">5 menit lalu</div>
        </div>

        <div class="action-bar">
            <a href="{{ url('/pesan') }}" class="btn-pesan">
                + Buat Pesanan
            </a>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h2>10</h2>
                <p>Total Pesanan</p>
            </div>
            <div class="stat-card">
                <h2>10</h2>
                <p>Sedang Proses</p>
            </div>
            <div class="stat-card">
                <h2>10</h2>
                <p>Siap Diambil</p>
            </div>
        </div>

        <div class="timeline-card">
            <div class="timeline-header">
                <div class="timeline-title">
                    <div class="notif-icon">
                        <svg viewBox="0 0 24 24"><path d="M19 8l-4 4h3c0 3.31-2.69 6-6 6-1.01 0-1.97-.25-2.8-.7l-1.46 1.46C8.97 19.54 10.43 20 12 20c4.42 0 8-3.58 8-8h3l-4-4zM6 12c0-3.31 2.69-6 6-6 1.01 0 1.97.25 2.8.7l1.46-1.46C15.03 4.46 13.57 4 12 4c-4.42 0-8 3.58-8 8H1l4 4 4-4H6z"/></svg>
                    </div>
                    <div class="timeline-title-text">
                        <h3>#Kode_Pesanan</h3>
                        <p>Keterangan</p>
                    </div>
                </div>
                <div class="badge-proses">• Diproses</div>
            </div>

            <div class="timeline-steps">
                <div class="step active">
                    <div class="step-circle"></div>
                    <div class="step-label">Diterima</div>
                </div>
                <div class="step active">
                    <div class="step-circle"></div>
                    <div class="step-label">Dijemput</div>
                </div>
                <div class="step active">
                    <div class="step-circle"></div>
                    <div class="step-label">Dicuci</div>
                </div>
                <div class="step">
                    <div class="step-circle"></div>
                    <div class="step-label">Siap</div>
                </div>
                <div class="step">
                    <div class="step-circle"></div>
                    <div class="step-label">Diantar</div>
                </div>
                <div class="step">
                    <div class="step-circle"></div>
                    <div class="step-label">Selesai</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
