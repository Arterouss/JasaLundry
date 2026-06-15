<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pesanan - MyLaundry</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background-color: #f5f7fa; color: #333; padding: 40px; }
        
        .navbar { display: flex; justify-content: space-between; margin-bottom: 30px; align-items: center; max-width: 1000px; margin: 0 auto 30px auto; }
        
        .nav-links { position: relative; display: inline-block; }
        .profile-trigger { background: none; border: none; padding: 0; cursor: pointer; outline: none; display: flex; align-items: center; }
        .admin-profile-avatar { width: 40px; height: 40px; border-radius: 50%; border: 2px solid #0F4A75; background-size: cover; background-position: center; }
        
        .dropdown-menu { display: none; position: absolute; top: 50px; right: 0; background-color: white; min-width: 160px; box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.1); border-radius: 8px; z-index: 1000; overflow: hidden; border: 1px solid #e2e8f0; }
        .dropdown-menu a { color: #333; padding: 12px 16px; text-decoration: none; display: block; font-size: 13px; font-weight: 500; transition: 0.2s; text-align: left; }
        .dropdown-menu a:hover { background-color: #f1f5f9; text-decoration: none; }
        .dropdown-menu .logout-btn { color: #e53e3e; font-weight: 600; }
        .dropdown-menu .logout-btn:hover { background-color: #fff5f5; }

        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }

        .header-section { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px; }
        .header-title h2 { font-size: 24px; font-weight: 600; color: #0F4A75; margin-bottom: 5px; }
        .header-title p { color: #666; font-size: 14px; }
        
        .btn-tambah { background-color: #2b6cb0; color: white; border: none; padding: 10px 20px; border-radius: 6px; font-size: 13px; font-weight: 500; cursor: pointer; text-decoration: none; }
        
        .filters { display: flex; gap: 10px; margin-bottom: 25px; flex-wrap: wrap; }
        .filter-btn { background: white; border: 1px solid #0F4A75; color: #0F4A75; padding: 6px 16px; border-radius: 20px; font-size: 13px; font-weight: 500; cursor: pointer; transition: 0.3s; }
        .filter-btn.active { background-color: #0F4A75; color: white; }

        .table-responsive { width: 100%; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 700px; border-radius: 8px; overflow: hidden; border: 1px solid #e2e8f0; }
        thead { background-color: #e5eff8; }
        th { padding: 15px; text-align: left; font-size: 13px; font-weight: 600; color: #0F4A75; border-bottom: 1px solid #e2e8f0; }
        td { padding: 15px; font-size: 13px; color: #444; border-bottom: 1px solid #e2e8f0; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover { background-color: #f8fafc; }

        .status-container { display: flex; align-items: center; gap: 8px; }
        .status-badge { padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; text-transform: uppercase; display: inline-block; }
        
        /* Pewarnaan Badge Status Sesuai Standar Aplikasi */
        .status-diterima { background-color: #feebc8; color: #c05621; }
        .status-diproses { background-color: #e2e8f0; color: #4a5568; }
        .status-siap { background-color: #ebf8ff; color: #2b6cb0; }
        .status-selesai { background-color: #c6f6d5; color: #22543d; }

        .btn-edit { width: 28px; height: 28px; background-color: #bbd8f0; border-radius: 4px; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: 0.3s; }
        .btn-edit:hover { background-color: #0F4A75; }
        .btn-edit:hover svg { fill: white; }
        .btn-edit svg { width: 14px; height: 14px; fill: #0F4A75; transition: 0.3s; }
        
        .pagination { display: flex; justify-content: flex-end; gap: 5px; margin-top: 20px; }
        .page-btn { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border: 1px solid #ddd; background: white; border-radius: 4px; font-size: 13px; color: #666; cursor: pointer; }
        .page-btn.active { background-color: #0F4A75; color: white; border-color: #0F4A75; }

        .alert-success { background-color: #c6f6d5; color: #22543d; padding: 12px 20px; border-radius: 6px; font-size: 14px; margin-bottom: 20px; font-weight: 500; }
    </style>
</head>
<body>
    <div class="navbar">
        <h2 style="color: #0F4A75;">MyLaundry (Admin)</h2>
        
        <div class="nav-links">
            <button class="profile-trigger" onclick="toggleAdminDropdown(event)">
                <div class="admin-profile-avatar" style="background-image: url('https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=0F4A75&color=fff');"></div>
            </button>

            <div id="adminDropdown" class="dropdown-menu">
                <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 0;">
                <a href="{{ route('logout') }}" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                   class="logout-btn">
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>

    <div class="container">
        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="header-section">
            <div class="header-title">
                <h2>Kelola Pesanan</h2>
                <p>Pantau dan perbarui antrean cucian pelanggan</p>
            </div>
            <a href="{{ route('admin.orders.create') }}" class="btn-tambah">Tambah Pesanan</a>
        </div>

        <div class="filters">
            <button class="filter-btn active">Semua ({{ count($orders) }})</button>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID Pesanan</th>
                        <th>Pelanggan</th>
                        <th>Layanan</th>
                        <th>Berat</th>
                        <th>Total</th>
                        <th>Status Operasional</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td><strong>LDR-{{ $order->id }}</strong></td>
                            <td>
                                {{ $order->customer->name ?? $order->walk_in_name }}
                            </td>
                            <td>{{ $order->service->name ?? 'Layanan Umum' }}</td>
                            <td>
                                {{ $order->weight ? number_format($order->weight, 1) . ' kg' : '-' }}
                            </td>
                            <td>
                                {{ $order->total_price ? 'Rp ' . number_format($order->total_price, 0, ',', '.') : 'Belum Dihitung' }}
                            </td>
                            <td>
                                <div class="status-container">
                                    <span class="status-badge status-{{ strtolower($order->status->name ?? 'diterima') }}">
                                        {{ $order->status->value ?? 'Diterima' }}
                                    </span>
                                    
                                    <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn-edit" title="Edit Pesanan">
                                        <svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: #999; padding: 30px;">
                                Belum ada pesanan masuk saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function toggleAdminDropdown(event) {
            event.stopPropagation();
            var dropdown = document.getElementById("adminDropdown");
            dropdown.style.display = (dropdown.style.display === "block") ? "none" : "block";
        }

        window.onclick = function(event) {
            var dropdown = document.getElementById("adminDropdown");
            if (dropdown && dropdown.style.display === "block") {
                dropdown.style.display = "none";
            }
        }
    </script>
</body>
</html>