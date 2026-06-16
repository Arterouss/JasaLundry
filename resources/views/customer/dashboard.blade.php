<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pelanggan - MyLaundry</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background-color: #1a1a1a; color: #333; display: flex; height: 100vh; overflow: hidden; }
        
        /* Sidebar */
        .sidebar { width: 100px; background-color: #0F4A75; display: flex; flex-direction: column; align-items: center; padding: 30px 0; color: white; justify-content: space-between; flex-shrink: 0; }
        .sidebar-top { display: flex; flex-direction: column; align-items: center; width: 100%; gap: 30px; }
        .sidebar-logo { width: 50px; height: 50px; background: white; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 10px; }
        .sidebar-logo svg { width: 28px; height: 28px; fill: #0F4A75; }
        
        .nav-item { display: flex; flex-direction: column; align-items: center; gap: 8px; color: #bbd8f0; text-decoration: none; font-size: 11px; cursor: pointer; transition: 0.3s; width: 100%; }
        .nav-item:hover, .nav-item.active { color: white; }
        .nav-item svg { width: 24px; height: 24px; fill: currentColor; }
        
        .sidebar-bottom { display: flex; flex-direction: column; align-items: center; }
        .sidebar-profile { width: 50px; height: 50px; background-color: #ccc; border-radius: 50%; cursor: pointer; }

        /* Main Content */
        .main-wrapper { flex-grow: 1; padding: 40px; overflow-y: auto; background-color: #1a1a1a; display: flex; justify-content: center; align-items: flex-start; min-height: 100vh; }
        .container { width: 100%; max-width: 1000px; background: white; padding: 40px; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.3); height: auto; margin-bottom: 40px; }

        /* Top Header */
        .header-card { background-color: #0F4A75; color: white; border-radius: 12px; padding: 30px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .header-text p { font-size: 16px; color: #A9C2D5; margin-bottom: 5px; }
        .header-text h1 { font-size: 28px; font-weight: 600; }
        .profile-pic { width: 70px; height: 70px; background-color: #ccc; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #0F4A75; font-size: 24px; }

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
        .btn-pesan { background-color: #0F4A75; color: white; font-weight: 600; text-decoration: none; padding: 12px 24px; border-radius: 8px; display: inline-flex; align-items: center; gap: 8px; transition: background 0.3s; }
        .btn-pesan:hover { background-color: #0b3656; }

        /* Stats */
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px; }
        .stats-card { background-color: #bbd8f0; text-align: center; padding: 30px 20px; border-radius: 12px; color: #0F4A75; }
        .stats-card h2 { font-size: 40px; font-weight: 700; margin-bottom: 5px; }
        .stats-card p { font-size: 14px; font-weight: 500; }

        /* Timeline Card */
        .timeline-card { background-color: #e5eff8; border-radius: 12px; padding: 25px; border: 1px solid #bbd8f0; margin-bottom: 20px; }
        .timeline-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
        .timeline-title { display: flex; align-items: center; gap: 15px; }
        .timeline-title-text h3 { color: #0F4A75; font-size: 16px; margin-bottom: 3px; }
        .timeline-title-text p { font-size: 13px; color: #666; }
        
        /* Tombol Bayar / Status */
        .btn-bayar { background-color: #22c55e; color: white; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; transition: 0.3s; display: inline-block; }
        .btn-bayar:hover { background-color: #16a34a; }
        .btn-disabled { background-color: #94a3b8; color: #f1f5f9; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: not-allowed; text-decoration: none; pointer-events: none; display: inline-block; }
        .btn-paid-success { background-color: #0F4A75; color: white; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: default; text-decoration: none; display: inline-block; }

        /* Timeline steps */
        .timeline-steps { display: flex; justify-content: space-between; position: relative; margin: 0 10px; padding-bottom: 20px; }
        .timeline-steps::before { content: ''; position: absolute; top: 12px; left: 0; right: 0; height: 2px; background-color: #cbd5e1; z-index: 1; }
        .step { position: relative; z-index: 2; display: flex; flex-direction: column; align-items: center; gap: 10px; }
        
        /* Default Lingkaran */
        .step-circle { width: 26px; height: 26px; background-color: white; border: 2px solid #cbd5e1; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: 0.3s; }
        .step-label { font-size: 12px; color: #64748b; position: absolute; top: 35px; white-space: nowrap; }
        
        /* Active Style */
        .step.active .step-circle { background-color: #0F4A75; border-color: #0F4A75; }
        .step.active .step-circle::after { content: '✓'; color: white; font-size: 13px; font-weight: bold; }
        .step.active .step-label { color: #0F4A75; font-weight: 600; }
        
        .demo-nav { display: flex; justify-content: flex-end; margin-bottom: 15px; align-items: center; gap: 15px; }
        .demo-nav a { text-decoration: none; color: #666; font-size: 13px; font-weight: 500; }
        .demo-nav button { background: none; border: none; color: #e53e3e; font-size: 13px; font-weight: 500; cursor: pointer; }
    </style>
</head>
<body>
    
    <div class="sidebar">
        <div class="sidebar-top">
            <div class="sidebar-logo">
                <svg viewBox="0 0 24 24"><path d="M19.5 8.5L18.5 20.5C18.5 21.3 17.8 22 17 22H7C6.2 22 5.5 21.3 5.5 20.5L4.5 8.5C4.5 7.7 5.2 7 6 7H18C18.8 7 19.5 7.7 19.5 8.5Z" fill="none" stroke="#0F4A75" stroke-width="2" stroke-linejoin="round"/><path d="M9 13H15" stroke="#0F4A75" stroke-width="2" stroke-linecap="round"/><path d="M10 17H14" stroke="#0F4A75" stroke-width="2" stroke-linecap="round"/><path d="M18.7 7L17.5 3.5C17.2 2.6 16.4 2 15.5 2H8.5C7.6 2 6.8 2.6 6.5 3.5L5.3 7" fill="none" stroke="#0F4A75" stroke-width="2" stroke-linejoin="round"/></svg>
            </div>
            
            <a href="{{ route('customer.dashboard') }}" class="nav-item active">
                <svg viewBox="0 0 24 24"><path d="M4 13h6c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v8c0 .55.45 1 1 1zm0 8h6c.55 0 1-.45 1-1v-4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v4c0 .55.45 1 1 1zm10 0h6c.55 0 1-.45 1-1v-8c0-.55-.45-1-1-1h-6c-.55 0-1 .45-1 1v8c0 .55.45 1 1 1zM13 4v4c0 .55.45 1 1 1h6c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1h-6c-.55 0-1 .45-1 1z"/></svg>
                Beranda
            </a>
            
            <a href="{{ route('customer.history') }}" class="nav-item">
                <svg viewBox="0 0 24 24"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/><path d="M12.5 7H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>
                Riwayat
            </a>
            
            <a href="{{ route('customer.create') }}" class="nav-item">
                <svg viewBox="0 0 24 24"><path d="M19 6h-2c0-2.76-2.24-5-5-5S7 3.24 7 6H5c-1.1 0-1.99.9-1.99 2L3 20c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2l.01-12c0-1.1-.89-2-1.99-2zM12 3c1.66 0 3 1.34 3 3H9c0-1.66 1.34-3 3-3zm0 10c-2.76 0-5-2.24-5-5h2c0 1.66 1.34 3 3 3s3-1.34 3-3h2c0 2.76-2.24 5-5 5z"/></svg>
                Pesan
            </a>
        </div>
        
        <div class="sidebar-bottom" style="position: relative; display: inline-block;">
            <button onclick="toggleDropdown(event)" style="background: none; border: none; padding: 0; cursor: pointer; outline: none;">
                <div class="sidebar-profile" style="background-image: url('https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0F4A75&color=fff'); background-size: cover; width: 45px; height: 45px; border-radius: 50%;"></div>
            </button>

            <div id="profileDropdown" style="display: none; position: absolute; bottom: 55px; left: 0; background-color: white; min-width: 160px; box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2); border-radius: 8px; z-index: 999; overflow: hidden; border: 1px solid #e2e8f0;">
                <a href="{{ route('customer.profile') }}" style="color: #333; padding: 12px 16px; text-decoration: none; display: block; font-size: 13px; transition: 0.2s;" onmouseover="this.style.backgroundColor='#f1f5f9'" onmouseout="this.style.backgroundColor='transparent'">
                    ⚙️ Setting Profile
                </a>
                <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 0;">
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color: #e53e3e; padding: 12px 16px; text-decoration: none; display: block; font-size: 13px; transition: 0.2s; font-weight: 500;" onmouseover="this.style.backgroundColor='#fff5f5'" onmouseout="this.style.backgroundColor='transparent'">
                    🚪 Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>

    <div class="main-wrapper">
        <div class="container">
            <div class="demo-nav">
                @if($user->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}">Ke Halaman Admin</a>
                @endif
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </div>

            <div class="header-card">
                <div class="header-text">
                    <p>Selamat Datang,</p>
                    <h1>{{ $user->name }}</h1>
                </div>
                <div class="profile-pic" style="background-color: white;">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
            </div>

            <div class="notification-card">
                <div class="notif-left">
                    <div class="notif-icon">
                        <svg viewBox="0 0 24 24"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
                    </div>
                    <div class="notif-text">
                        @if($activeOrders->isNotEmpty())
                            <h3>Kamu Memiliki {{ $activeOrders->count() }} Pesanan Aktif</h3>
                            <p>Pantau progres laundry kamu di bagian timeline di bawah.</p>
                        @else
                            <h3>Belum Ada Pesanan Aktif</h3>
                            <p>Silakan klik tombol di bawah untuk membuat orderan baru.</p>
                        @endif
                    </div>
                </div>
                <div class="notif-time">Sekarang</div>
            </div>

            <div class="action-bar">
                <a href="{{ route('customer.create') }}" class="btn-pesan">+ Buat Pesanan</a>
            </div>

            <div class="stats-grid">
                <div class="stats-card">
                    <h2>{{ $totalPesanan }}</h2>
                    <p>Total Pesanan</p>
                </div>
                <div class="stats-card">
                    <h2>{{ $sedangProses }}</h2>
                    <p>Sedang Proses</p>
                </div>
                <div class="stats-card">
                    <h2>{{ $siapDiambil }}</h2>
                    <p>Siap Diambil</p>
                </div>
            </div>

            @if($activeOrders->isNotEmpty())
                @foreach($activeOrders as $order)
                    <div class="timeline-card">
                        <div class="timeline-header">
                            <div class="timeline-title">
                                <div class="notif-icon" style="background-color: transparent; border: 1px solid #0F4A75; border-radius: 8px;">
                                    <svg viewBox="0 0 24 24" style="fill: #0F4A75;"><path d="M19.5 8.5L18.5 20.5C18.5 21.3 17.8 22 17 22H7C6.2 22 5.5 21.3 5.5 20.5L4.5 8.5C4.5 7.7 5.2 7 6 7H18C18.8 7 19.5 7.7 19.5 8.5Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M9 13H15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M10 17H14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M18.7 7L17.5 3.5C17.2 2.6 16.4 2 15.5 2H8.5C7.6 2 6.8 2.6 6.5 3.5L5.3 7" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>
                                </div>
                                <div class="timeline-title-text">
                                    <h3>#INV-{{ $order->id }}</h3>
                                    <p>Tipe: {{ $order->is_pickup_delivery ? 'Antar Jemput' : 'Ambil Sendiri' }} ({{ ucfirst($order->payment_method) }})</p>
                                </div>
                            </div>

                            <div>
    @if($order->payment_status === 'paid')
        <span class="btn-paid-success">✓ Lunas</span>

    @elseif($order->payment_method === 'cashless')
        @php
            $statusRaw = $order->status instanceof \App\Enums\OrderStatus ? $order->status->value : $order->status;
        @endphp

        @if(in_array($statusRaw, ['pesanan_diterima', 'pesanan_dijemput']) && $order->grand_total <= 0)
            <a href="#" class="btn-disabled" title="Tombol terkunci. Menunggu admin mengisi berat timbangan.">Menunggu Timbangan</a>
        
        @else
            <a href="{{ route('customer.orders.checkout', $order->id) }}" class="btn-bayar">
                Bayar Sekarang (Rp {{ number_format($order->grand_total, 0, ',', '.') }})
            </a>
        @endif

    @else
        <a href="#" class="btn-disabled" title="Pembayaran COD dilakukan langsung saat penyerahan pakaian.">Bayar di Tempat (COD)</a>
    @endif
</div>
                        </div>

                        @php
                            // 1. Ambil nilai string database dari Enum
                            $currentStatusString = $order->status instanceof \App\Enums\OrderStatus 
                                ? $order->status->value 
                                : $order->status;

                            // 2. Tentukan Jalur Timeline secara dinamis per item
                            if ($order->is_pickup_delivery) {
                                $steps = [
                                    ['key' => 'pesanan_diterima', 'label' => 'Diterima'],
                                    ['key' => 'pesanan_dijemput', 'label' => 'Dijemput'],
                                    ['key' => 'pesanan_diproses', 'label' => 'Diproses'],
                                    ['key' => 'pesanan_siap',     'label' => 'Siap'],
                                    ['key' => 'pesanan_diantar',  'label' => 'Diantar'],
                                    ['key' => 'pesanan_selesai',  'label' => 'Selesai']
                                ];
                            } else {
                                $steps = [
                                    ['key' => 'pesanan_diterima', 'label' => 'Diterima'],
                                    ['key' => 'pesanan_diproses', 'label' => 'Diproses'],
                                    ['key' => 'pesanan_siap',     'label' => 'Siap'],
                                    ['key' => 'pesanan_selesai',  'label' => 'Selesai']
                                ];
                            }

                            // 3. Hitung indeks status aktif saat ini
                            $currentStepIndex = array_search($currentStatusString, array_column($steps, 'key'));
                            if ($currentStepIndex === false) {
                                $currentStepIndex = 0;
                            }
                        @endphp

                        <div class="timeline-steps">
                            @foreach($steps as $index => $step)
                                <div class="step {{ $currentStepIndex >= $index ? 'active' : '' }}">
                                    <div class="step-circle"></div>
                                    <div class="step-label">{{ $step['label'] }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @else
                <div class="timeline-card" style="text-align: center; color: #666; padding: 40px;">
                    Belum ada jejak riwayat pelacakan pengiriman pakaian untuk saat ini.
                </div>
            @endif
        </div>
    </div>

<script>
    function toggleDropdown(event) {
        event.stopPropagation();
        var dropdown = document.getElementById("profileDropdown");
        if (dropdown.style.display === "none" || dropdown.style.display === "") {
            dropdown.style.display = "block";
        } else {
            dropdown.style.display = "none";
        }
    }

    window.onclick = function(event) {
        var dropdown = document.getElementById("profileDropdown");
        if (dropdown && dropdown.style.display === "block") {
            dropdown.style.display = "none";
        }
    }
</script>
</body>
</html>