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
        
        /* CSS Baru untuk pembungkus Profile Dropdown di Navbar */
        .nav-links { position: relative; display: inline-block; }
        .profile-trigger { background: none; border: none; padding: 0; cursor: pointer; outline: none; display: flex; align-items: center; }
        .admin-profile-avatar { width: 40px; height: 40px; border-radius: 50%; border: 2px solid #0F4A75; background-size: cover; background-position: center; }
        
        .dropdown-menu { display: none; position: absolute; top: 50px; right: 0; background-color: white; min-width: 160px; box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.1); border-radius: 8px; z-index: 1000; overflow: hidden; border: 1px solid #e2e8f0; }
        .dropdown-menu a { color: #333; padding: 12px 16px; text-decoration: none; display: block; font-size: 13px; font-weight: 500; transition: 0.2s; text-align: left; }
        .dropdown-menu a:hover { background-color: #f1f5f9; text-decoration: none; }
        .dropdown-menu .logout-btn { color: #e53e3e; font-weight: 600; }
        .dropdown-menu .logout-btn:hover { background-color: #fff5f5; }
        /* ---------------------------------------------------- */

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

        .status-select { padding: 6px 10px; border-radius: 4px; border: 1px solid #ddd; font-size: 12px; font-family: inherit; color: #333; outline: none; background: white; }
        
        .pagination { display: flex; justify-content: flex-end; gap: 5px; margin-top: 20px; }
        .page-btn { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border: 1px solid #ddd; background: white; border-radius: 4px; font-size: 13px; color: #666; cursor: pointer; }
        .page-btn.active { background-color: #0F4A75; color: white; border-color: #0F4A75; }
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
                
                <a href="#" class="logout-btn" onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
                    🚪 Logout
                </a>
                <form id="admin-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="header-section">
            <div class="header-title">
                <h2>Kelola Pesanan</h2>
                <p>Update status cucian pelanggan</p>
            </div>
            <a href="#" class="btn-tambah">Tambah Pesanan</a>
        </div>

        <div class="filters">
            <button class="filter-btn active">Semua(8)</button>
            <button class="filter-btn">Menunggu(2)</button>
            <button class="filter-btn">Proses(10)</button>
            <button class="filter-btn">Siap(1)</button>
            <button class="filter-btn">Diantar(1)</button>
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
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>LDR-123</td>
                        <td>Bayu Anggara</td>
                        <td>Cuci + Kering</td>
                        <td>50 kg</td>
                        <td>Rp. 150.000</td>
                        <td>
                            <select class="status-select" style="margin-right: 5px;">
                                <option selected>Diproses</option>
                                <option>Diterima</option>
                                <option>Siap</option>
                                <option>Diantar</option>
                            </select>
                            <a href="{{ url('/admin/pesanan/LDR-123/edit') }}" class="btn-tambah" style="background-color: #319795; padding: 6px 12px; font-size: 12px;">Kelola</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="pagination">
            <button class="page-btn"><</button>
            <button class="page-btn active">1</button>
            <button class="page-btn">2</button>
            <button class="page-btn">></button>
        </div>
    </div>

    <script>
        function toggleAdminDropdown(event) {
            event.stopPropagation(); // Mencegah event bubbling ke elemen window
            var dropdown = document.getElementById("adminDropdown");
            
            if (dropdown.style.display === "none" || dropdown.style.display === "") {
                dropdown.style.display = "block";
            } else {
                dropdown.style.display = "none";
            }
        }

        // Otomatis menutup dropdown jika admin mengklik di luar area menu
        window.onclick = function(event) {
            var dropdown = document.getElementById("adminDropdown");
            if (dropdown && dropdown.style.display === "block") {
                dropdown.style.display = "none";
            }
        }
    </script>
</body>
</html>