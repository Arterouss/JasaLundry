<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - MyLaundry</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background-color: #1a1a1a; color: #333; display: flex; height: 100vh; overflow: hidden; }
        
        /* Sidebar (Same as Dashboard) */
        .sidebar { width: 100px; background-color: #0F4A75; display: flex; flex-direction: column; align-items: center; padding: 30px 0; color: white; justify-content: space-between; flex-shrink: 0; }
        .sidebar-top { display: flex; flex-direction: column; align-items: center; width: 100%; gap: 30px; }
        .sidebar-logo { width: 50px; height: 50px; background: white; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 10px; }
        .sidebar-logo svg { width: 28px; height: 28px; fill: #0F4A75; }
        
        .nav-item { display: flex; flex-direction: column; align-items: center; gap: 8px; color: #bbd8f0; text-decoration: none; font-size: 11px; cursor: pointer; transition: 0.3s; width: 100%; }
        .nav-item:hover, .nav-item.active { color: white; }
        .nav-item svg { width: 24px; height: 24px; fill: currentColor; }
        .nav-item.active svg { fill: white; }
        
        .sidebar-bottom { display: flex; flex-direction: column; align-items: center; }
        .sidebar-profile { width: 50px; height: 50px; background-color: #ccc; border-radius: 50%; cursor: pointer; }

        /* Main Content */
        .main-wrapper { flex-grow: 1; padding: 30px; overflow-y: auto; background-color: #f5f7fa; display: flex; justify-content: center; }
        .container { width: 100%; max-width: 900px; background: white; padding: 30px 40px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); height: fit-content; }

        .header-section { margin-bottom: 25px; }
        .header-title h2 { font-size: 24px; font-weight: 600; color: #0F4A75; margin-bottom: 5px; }
        .header-title p { color: #666; font-size: 14px; }
        
        .filters { display: flex; gap: 10px; margin-bottom: 25px; flex-wrap: wrap; }
        .filter-btn { background: white; border: 1px solid #0F4A75; color: #0F4A75; padding: 6px 16px; border-radius: 20px; font-size: 13px; font-weight: 500; cursor: pointer; transition: 0.3s; }
        .filter-btn.active { background-color: #0F4A75; color: white; }

        /* Order Card */
        .order-card { background-color: #e5eff8; border-radius: 12px; padding: 20px; border: 1px solid #bbd8f0; margin-bottom: 20px; }
        .order-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px; }
        .order-title { display: flex; align-items: center; gap: 15px; }
        
        .notif-icon { width: 40px; height: 40px; background-color: transparent; border: 1px solid #0F4A75; border-radius: 8px; display: flex; align-items: center; justify-content: center; }
        .notif-icon svg { width: 24px; height: 24px; fill: #0F4A75; }
        
        .order-title-text h3 { color: #0F4A75; font-size: 15px; font-weight: 600; }
        .order-title-text p { font-size: 12px; color: #666; }
        .badge-paid { background-color: #bbd8f0; color: #0F4A75; padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; }

        /* Timeline steps */
        .timeline-steps { display: flex; justify-content: space-between; position: relative; margin: 0 10px; }
        .timeline-steps::before { content: ''; position: absolute; top: 12px; left: 0; right: 0; height: 2px; background-color: #bbd8f0; z-index: 1; }
        .step { position: relative; z-index: 2; display: flex; flex-direction: column; align-items: center; gap: 10px; }
        .step-circle { width: 26px; height: 26px; background-color: white; border: 2px solid #bbd8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
        .step.active .step-circle { background-color: #0F4A75; border-color: #0F4A75; }
        .step.active .step-circle::after { content: '✓'; color: white; font-size: 14px; }
        .step-label { font-size: 11px; color: #555; position: absolute; top: 35px; white-space: nowrap; }
        .step.active .step-label { color: #0F4A75; font-weight: 600; }
        
        .pagination { display: flex; justify-content: flex-end; gap: 5px; margin-top: 20px; }
        .page-btn { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border: 1px solid #ddd; background: white; border-radius: 4px; font-size: 13px; color: #666; cursor: pointer; }
        .page-btn.active { background-color: #0F4A75; color: white; border-color: #0F4A75; }

    </style>
</head>
<body>
    
    <!-- Left Sidebar -->
    <div class="sidebar">
        <div class="sidebar-top">
            <div class="sidebar-logo">
                <svg viewBox="0 0 24 24"><path d="M19.5 8.5L18.5 20.5C18.5 21.3 17.8 22 17 22H7C6.2 22 5.5 21.3 5.5 20.5L4.5 8.5C4.5 7.7 5.2 7 6 7H18C18.8 7 19.5 7.7 19.5 8.5Z" fill="none" stroke="#0F4A75" stroke-width="2" stroke-linejoin="round"/><path d="M9 13H15" stroke="#0F4A75" stroke-width="2" stroke-linecap="round"/><path d="M10 17H14" stroke="#0F4A75" stroke-width="2" stroke-linecap="round"/><path d="M18.7 7L17.5 3.5C17.2 2.6 16.4 2 15.5 2H8.5C7.6 2 6.8 2.6 6.5 3.5L5.3 7" fill="none" stroke="#0F4A75" stroke-width="2" stroke-linejoin="round"/></svg>
            </div>
            
            <a href="{{ route('customer.dashboard') }}" class="nav-item">
                <svg viewBox="0 0 24 24"><path d="M4 13h6c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v8c0 .55.45 1 1 1zm0 8h6c.55 0 1-.45 1-1v-4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v4c0 .55.45 1 1 1zm10 0h6c.55 0 1-.45 1-1v-8c0-.55-.45-1-1-1h-6c-.55 0-1 .45-1 1v8c0 .55.45 1 1 1zM13 4v4c0 .55.45 1 1 1h6c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1h-6c-.55 0-1 .45-1 1z"/></svg>
                Beranda
            </a>
            
            <a href="{{ route('customer.riwayat') }}" class="nav-item active">
                <svg viewBox="0 0 24 24"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/><path d="M12.5 7H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>
                Riwayat
            </a>
            
            <a href="{{ route('customer.orders.create') }}" class="nav-item">
                <svg viewBox="0 0 24 24"><path d="M19 6h-2c0-2.76-2.24-5-5-5S7 3.24 7 6H5c-1.1 0-1.99.9-1.99 2L3 20c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2l.01-12c0-1.1-.89-2-1.99-2zM12 3c1.66 0 3 1.34 3 3H9c0-1.66 1.34-3 3-3zm0 10c-2.76 0-5-2.24-5-5h2c0 1.66 1.34 3 3 3s3-1.34 3-3h2c0 2.76-2.24 5-5 5z"/></svg>
                Pesan
            </a>
            
            <a href="{{ route('customer.profile') }}" class="nav-item">
                <svg viewBox="0 0 24 24"><path d="M19.14,12.94c0.04-0.3,0.06-0.61,0.06-0.94c0-0.32-0.02-0.64-0.06-0.94l2.03-1.58c0.18-0.14,0.23-0.41,0.12-0.61 l-1.92-3.32c-0.12-0.22-0.37-0.29-0.59-0.22l-2.39,0.96c-0.5-0.38-1.03-0.7-1.62-0.94L14.4,2.81c-0.04-0.24-0.24-0.41-0.48-0.41 h-3.84c-0.24,0-0.43,0.17-0.47,0.41L9.25,5.35C8.66,5.59,8.12,5.92,7.63,6.29L5.24,5.33c-0.22-0.08-0.47,0-0.59,0.22L2.73,8.87 C2.62,9.08,2.66,9.34,2.86,9.48l2.03,1.58C4.84,11.36,4.8,11.69,4.8,12s0.02,0.64,0.06,0.94l-2.03,1.58 c-0.18,0.14-0.23,0.41-0.12,0.61l1.92,3.32c0.12,0.22,0.37,0.29,0.59,0.22l2.39-0.96c0.5,0.38,1.03,0.7,1.62,0.94l0.36,2.54 c0.05,0.24,0.24,0.41,0.48,0.41h3.84c0.24,0,0.43-0.17,0.47-0.41l0.36-2.54c0.59-0.24,1.13-0.56,1.62-0.94l2.39,0.96 c0.22,0.08,0.47,0,0.59-0.22l1.92-3.32c0.12-0.22,0.07-0.49-0.12-0.61L19.14,12.94z M12,15.6c-1.98,0-3.6-1.62-3.6-3.6 s1.62-3.6,3.6-3.6s3.6,1.62,3.6,3.6S13.98,15.6,12,15.6z"/></svg>
                Pengaturan
            </a>
        </div>
        
        <div class="sidebar-bottom">
            <a href="{{ route('customer.profile') }}">
                <div class="sidebar-profile"></div>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-wrapper">
        <div class="container">
            <div class="header-section">
                <div class="header-title">
                    <h2>Riwayat Pesanan</h2>
                    <p>Riwayat pesanan yang aktif dan selesai</p>
                </div>
            </div>

            <div class="filters">
                <button class="filter-btn active">Semua(3)</button>
                <button class="filter-btn">Menunggu(0)</button>
                <button class="filter-btn">Proses(1)</button>
                <button class="filter-btn">Siap(1)</button>
                <button class="filter-btn">Diantar(1)</button>
            </div>

            <!-- Order Card 1 -->
            <div class="order-card">
                <div class="order-header">
                    <div class="order-title">
                        <div class="notif-icon">
                            <svg viewBox="0 0 24 24"><path d="M19.5 8.5L18.5 20.5C18.5 21.3 17.8 22 17 22H7C6.2 22 5.5 21.3 5.5 20.5L4.5 8.5C4.5 7.7 5.2 7 6 7H18C18.8 7 19.5 7.7 19.5 8.5Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M9 13H15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M10 17H14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                        </div>
                        <div class="order-title-text">
                            <h3>#Kode_Pesanan</h3>
                            <p>(Keterangan)</p>
                        </div>
                    </div>
                    <div class="badge-paid">Sudah dibayar</div>
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
                    <div class="step active">
                        <div class="step-circle"></div>
                        <div class="step-label">Siap</div>
                    </div>
                    <div class="step active">
                        <div class="step-circle"></div>
                        <div class="step-label">Diantar</div>
                    </div>
                    <div class="step active">
                        <div class="step-circle"></div>
                        <div class="step-label">Selesai</div>
                    </div>
                </div>
            </div>

            <!-- Order Card 2 -->
            <div class="order-card">
                <div class="order-header">
                    <div class="order-title">
                        <div class="notif-icon">
                            <svg viewBox="0 0 24 24"><path d="M19.5 8.5L18.5 20.5C18.5 21.3 17.8 22 17 22H7C6.2 22 5.5 21.3 5.5 20.5L4.5 8.5C4.5 7.7 5.2 7 6 7H18C18.8 7 19.5 7.7 19.5 8.5Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M9 13H15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M10 17H14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                        </div>
                        <div class="order-title-text">
                            <h3>#Kode_Pesanan</h3>
                            <p>(Keterangan)</p>
                        </div>
                    </div>
                    <div class="badge-paid">Sudah dibayar</div>
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
                    <div class="step active">
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

            <!-- Order Card 3 -->
            <div class="order-card">
                <div class="order-header">
                    <div class="order-title">
                        <div class="notif-icon">
                            <svg viewBox="0 0 24 24"><path d="M19.5 8.5L18.5 20.5C18.5 21.3 17.8 22 17 22H7C6.2 22 5.5 21.3 5.5 20.5L4.5 8.5C4.5 7.7 5.2 7 6 7H18C18.8 7 19.5 7.7 19.5 8.5Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M9 13H15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M10 17H14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                        </div>
                        <div class="order-title-text">
                            <h3>#Kode_Pesanan</h3>
                            <p>(Keterangan)</p>
                        </div>
                    </div>
                    <div class="badge-paid">Sudah dibayar</div>
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

            <div class="pagination">
                <button class="page-btn"><</button>
                <button class="page-btn active">1</button>
                <button class="page-btn">2</button>
                <button class="page-btn">></button>
            </div>
        </div>
    </div>
</body>
</html>
