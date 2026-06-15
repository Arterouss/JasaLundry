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
        .sidebar-profile { width: 45px; height: 45px; background-color: #ccc; border-radius: 50%; border: 2px solid #bbd8f0; background-size: cover; background-position: center; }

        /* Main Content */
        .main-wrapper { flex-grow: 1; padding: 30px; overflow-y: auto; background-color: #f5f7fa; display: flex; justify-content: center; }
        .container { width: 100%; max-width: 900px; background: white; padding: 30px 40px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); height: fit-content; margin-bottom: 40px; }

        .header-section { margin-bottom: 25px; }
        .header-title h2 { font-size: 24px; font-weight: 600; color: #0F4A75; margin-bottom: 5px; }
        .header-title p { color: #666; font-size: 14px; }
        
        .filters { display: flex; gap: 10px; margin-bottom: 25px; flex-wrap: wrap; }
        .filter-link { background: white; border: 1px solid #0F4A75; color: #0F4A75; padding: 6px 16px; border-radius: 20px; font-size: 13px; font-weight: 500; cursor: pointer; transition: 0.3s; text-decoration: none; }
        .filter-link.active { background-color: #0F4A75; color: white; }

        /* Order Card */
        .order-card { background-color: #e5eff8; border-radius: 12px; padding: 20px; border: 1px solid #bbd8f0; margin-bottom: 35px; }
        .order-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px; }
        .order-title { display: flex; align-items: center; gap: 15px; }
        
        .notif-icon { width: 40px; height: 40px; background-color: transparent; border: 1px solid #0F4A75; border-radius: 8px; display: flex; align-items: center; justify-content: center; }
        .notif-icon svg { width: 24px; height: 24px; fill: #0F4A75; }
        
        .order-title-text h3 { color: #0F4A75; font-size: 15px; font-weight: 600; }
        .order-title-text p { font-size: 12px; color: #666; margin-top: 2px; }
        .badge-status { padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; }
        .badge-paid { background-color: #c6f6d5; color: #22543d; }
        .badge-unpaid { background-color: #fde8e8; color: #c81e1e; }

        /* Timeline steps */
        .timeline-steps { display: flex; justify-content: space-between; position: relative; margin: 0 10px; padding-bottom: 10px; }
        .timeline-steps::before { content: ''; position: absolute; top: 12px; left: 0; right: 0; height: 2px; background-color: #bbd8f0; z-index: 1; }
        .step { position: relative; z-index: 2; display: flex; flex-direction: column; align-items: center; gap: 10px; }
        .step-circle { width: 26px; height: 26px; background-color: white; border: 2px solid #bbd8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
        .step.active .step-circle { background-color: #0F4A75; border-color: #0F4A75; }
        .step.active .step-circle::after { content: '✓'; color: white; font-size: 13px; font-weight: bold; }
        .step-label { font-size: 11px; color: #555; position: absolute; top: 35px; white-space: nowrap; }
        .step.active .step-label { color: #0F4A75; font-weight: 600; }
        
        /* Pagination Link Overriding Custom */
        .pagination-wrapper { display: flex; justify-content: flex-end; margin-top: 30px; }
        .pagination-wrapper nav svg { width: 20px; height: 20px; }
    </style>
</head>
<body>
    
    <div class="sidebar">
        <div class="sidebar-top">
            <div class="sidebar-logo">
                <svg viewBox="0 0 24 24"><path d="M19.5 8.5L18.5 20.5C18.5 21.3 17.8 22 17 22H7C6.2 22 5.5 21.3 5.5 20.5L4.5 8.5C4.5 7.7 5.2 7 6 7H18C18.8 7 19.5 7.7 19.5 8.5Z" fill="none" stroke="#0F4A75" stroke-width="2" stroke-linejoin="round"/><path d="M9 13H15" stroke="#0F4A75" stroke-width="2" stroke-linecap="round"/><path d="M10 17H14" stroke="#0F4A75" stroke-width="2" stroke-linecap="round"/><path d="M18.7 7L17.5 3.5C17.2 2.6 16.4 2 15.5 2H8.5C7.6 2 6.8 2.6 6.5 3.5L5.3 7" fill="none" stroke="#0F4A75" stroke-width="2" stroke-linejoin="round"/></svg>
            </div>
            
            <a href="{{ route('customer.dashboard') }}" class="nav-item">
                <svg viewBox="0 0 24 24"><path d="M4 13h6c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v8c0 .55.45 1 1 1zm0 8h6c.55 0 1-.45 1-1v-4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v4c0 .55.45 1 1 1zm10 0h6c.55 0 1-.45 1-1v-8c0-.55-.45-1-1-1h-6c-.55 0-1 .45-1 1v8c0 .55.45 1 1 1zM13 4v4c0 .55.45 1 1 1h6c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1h-6c-.55 0-1 .45-1 1z"/></svg>
                Beranda
            </a>
            
            <a href="{{ route('customer.orders.history') }}" class="nav-item active">
                <svg viewBox="0 0 24 24"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/><path d="M12.5 7H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>
                Riwayat
            </a>
            
            <a href="{{ route('customer.orders.create') }}" class="nav-item">
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
            <div class="header-section">
                <div class="header-title">
                    <h2>Riwayat Transaksi</h2>
                    <p>Pantau status pengerjaan pakaian aktif dan riwayat transaksimu</p>
                </div>
            </div>

            <div class="filters">
                <a href="{{ route('customer.orders.history') }}" class="filter-link {{ !request('status') ? 'active' : '' }}">Semua</a>
                <a href="{{ route('customer.orders.history', ['status' => 'menunggu']) }}" class="filter-link {{ request('status') == 'menunggu' ? 'active' : '' }}">Menunggu</a>
                <a href="{{ route('customer.orders.history', ['status' => 'proses']) }}" class="filter-link {{ request('status') == 'proses' ? 'active' : '' }}">Proses</a>
                <a href="{{ route('customer.orders.history', ['status' => 'siap']) }}" class="filter-link {{ request('status') == 'siap' ? 'active' : '' }}">Siap</a>
                <a href="{{ route('customer.orders.history', ['status' => 'diantar']) }}" class="filter-link {{ request('status') == 'diantar' ? 'active' : '' }}">Diantar</a>
            </div>

            @forelse($orders as $order)
                @php
                    // Ambil nilai string database dari objek Enum
                    $currentStatusString = $order->status instanceof \App\Enums\OrderStatus ? $order->status->value : $order->status;

                    // STRUKTUR TIMELINE DINAMIS SESUAI LAYANAN ANTAR JEMPUT
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

                    // Ambil posisi nomor index untuk menentukan tanda centang (✓)
                    $currentStepIndex = array_search($currentStatusString, array_column($steps, 'key'));
                    if ($currentStepIndex === false) { $currentStepIndex = 0; }
                @endphp

                <div class="order-card">
                    <div class="order-header">
                        <div class="order-title">
                            <div class="notif-icon">
                                <svg viewBox="0 0 24 24"><path d="M19.5 8.5L18.5 20.5C18.5 21.3 17.8 22 17 22H7C6.2 22 5.5 21.3 5.5 20.5L4.5 8.5C4.5 7.7 5.2 7 6 7H18C18.8 7 19.5 7.7 19.5 8.5Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M9 13H15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M10 17H14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                            </div>
                            <div class="order-title-text">
                                <h3>#INV-{{ $order->id }}</h3>
                                <p>{{ $order->service->name ?? 'Layanan Umum' }} - {{ $order->weight ? number_format($order->weight, 1) . ' kg' : 'Belum ditimbang' }}</p>
                                <p style="font-size: 11px; color: #888;">Tanggal Order: {{ $order->created_at->format('d M Y, H:i') }} WIB</p>
                            </div>
                        </div>
                        
                        <div class="badge-status {{ $order->payment_status === 'paid' ? 'badge-paid' : 'badge-unpaid' }}">
                            {{ $order->payment_status === 'paid' ? 'Sudah dibayar' : 'Belum dibayar' }}
                        </div>
                    </div>

                    <div class="timeline-steps">
                        @foreach($steps as $index => $step)
                            <div class="step {{ $currentStepIndex >= $index ? 'active' : '' }}">
                                <div class="step-circle"></div>
                                <div class="step-label">{{ $step['label'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 60px 20px; color: #718096; background: #edf2f7; border-radius: 8px;">
                    <p style="font-weight: 500; font-size: 15px;">Tidak ada riwayat transaksi ditemukan untuk kategori ini.</p>
                </div>
            @endforelse

            <div class="pagination-wrapper">
                {{ $orders->links() }}
            </div>
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