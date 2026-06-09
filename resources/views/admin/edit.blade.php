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
        
        .navbar { display: flex; justify-content: space-between; margin-bottom: 30px; align-items: center; max-width: 1200px; margin: 0 auto 30px auto; }
        .nav-links a { text-decoration: none; color: #0F4A75; font-weight: 500; font-size: 14px; }
        .nav-links a:hover { text-decoration: underline; }

        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }

        .header-section { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px; }
        .header-title h2 { font-size: 24px; font-weight: 600; color: #0F4A75; margin-bottom: 5px; }
        .header-title p { color: #666; font-size: 14px; }
        
        .btn-tambah { background-color: #2b6cb0; color: white; border: none; padding: 10px 20px; border-radius: 6px; font-size: 13px; font-weight: 500; cursor: pointer; text-decoration: none; }
        
        .table-responsive { width: 100%; overflow-x: auto; margin-top: 15px; }
        table { width: 100%; border-collapse: collapse; min-width: 1000px; border-radius: 8px; overflow: hidden; border: 1px solid #e2e8f0; }
        thead { background-color: #e5eff8; }
        th { padding: 15px; text-align: left; font-size: 13px; font-weight: 600; color: #0F4A75; border-bottom: 1px solid #e2e8f0; }
        td { padding: 12px 15px; font-size: 13px; color: #444; border-bottom: 1px solid #e2e8f0; vertical-align: middle; }
        tbody tr:hover { background-color: #f8fafc; }

        .input-table { width: 80px; padding: 6px 10px; border-radius: 6px; border: 1px solid #cbd5e0; font-size: 13px; outline: none; text-align: center; }
        .input-table:focus { border-color: #0F4A75; box-shadow: 0 0 0 2px rgba(15, 74, 117, 0.1); }
        
        .status-select { padding: 6px 10px; border-radius: 6px; border: 1px solid #cbd5e0; font-size: 13px; color: #333; outline: none; background: white; font-weight: 500; }
        .status-select:focus { border-color: #0F4A75; }

        .btn-update { background-color: #0F4A75; color: white; border: none; padding: 8px 16px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; transition: 0.2s; }
        .btn-update:hover { background-color: #0b3757; }

        .text-total { font-weight: 700; color: #0F4A75; font-size: 14px; }
        .badge-delivery { background-color: #feebc8; color: #c05621; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; display: inline-block; }
        .badge-self { background-color: #e2e8f0; color: #4a5568; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; display: inline-block; }

        .alert-success { background-color: #c6f6d5; color: #22543d; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; font-weight: 500; }
    </style>
</head>
<body>
    <div class="navbar">
        <h2 style="color: #0F4A75;">MyLaundry (Admin)</h2>
        <div class="nav-links">
            <a href="#">Kembali ke Dashboard Pelanggan</a>
        </div>
    </div>

    <div class="container">
        <div class="header-section">
            <div class="header-title">
                <h2>Kelola Pesanan Pelanggan</h2>
                <p>Validasi berat riil, kalkulasi harga, dan perbarui status operasional cucian</p>
            </div>
            <a href="#" class="btn-tambah">+ Validasi Pesanan Baru</a>
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID Order</th>
                        <th>Pelanggan</th>
                        <th>Layanan Terpilih</th>
                        <th>Tipe Distribusi</th>
                        <th>Berat Riil (kg)</th>
                        <th>Jarak Riil (km)</th>
                        <th>Total Harga Akhir</th>
                        <th>Status Operasional</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr data-price-per-kg="{{ $order->service->price_per_kg ?? 5000 }}" data-delivery-active="{{ $order->is_pickup_delivery ? '1' : '0' }}">
                            <td style="font-weight: 600; color: #4a5568;">LDR-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <div style="font-weight: 500;">{{ $order->customer?->name ?? $order->walk_in_name ?? 'Walk-in Customer' }}</div>
                                <small style="color: #718096;">Minyak Parfum: {{ $order->perfume?->name ?? 'Standard' }}</small>
                            </td>
                            <td>
                                <span style="font-weight: 500; color: #2c5282;">{{ $order->service?->name ?? 'Cuci + Kering' }}</span>
                            </td>
                            <td>
                                @if($order->is_pickup_delivery)
                                    <span class="badge-delivery">Antar Jemput</span>
                                @else
                                    <span class="badge-self">Antar Sendiri</span>
                                @endif
                            </td>
                            <td>
                                <input type="number" step="0.1" min="0" name="weight" form="form-{{ $order->id }}" class="input-table input-weight" value="{{ $order->weight ?? 0 }}">
                            </td>
                            <td>
                                @if($order->is_pickup_delivery)
                                    <input type="number" step="0.1" min="0" name="distance" form="form-{{ $order->id }}" class="input-table input-distance" value="{{ $order->distance ?? $order->distance_km ?? 0 }}">
                                @else
                                    <input type="text" class="input-table" value="-" readonly style="background: #edf2f7; color: #a0aec0;">
                                @endif
                            </td>
                            <td>
                                <span class="text-total display-total">Rp {{ number_format($order->total_price ?? $order->grand_total ?? 0, 0, ',', '.') }}</span>
                            </td>
                            <td>
                                <select name="status" form="form-{{ $order->id }}" class="status-select">
                                    @foreach(\App\Enums\OrderStatus::cases() as $status)
                                        <option value="{{ $status->value }}" {{ $order->status->value === $status->value ? 'selected' : '' }}>
                                            {{ is_object($status) && method_exists($status, 'label') ? $status->label() : ucfirst(str_replace('_', ' ', $status->value)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <form id="form-{{ $order->id }}" action="{{ route('admin.orders.assess', $order->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn-update">Simpan</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align: center; color: #a0aec0; padding: 40px;">Belum ada antrean pesanan masuk dari pelanggan saat ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const weightInput = row.querySelector('.input-weight');
                const distanceInput = row.querySelector('.input-distance');
                const displayTotal = row.querySelector('.display-total');

                if (!weightInput) return; 

                const pricePerKg = parseFloat(row.getAttribute('data-price-per-kg')) || 5000;
                const isDelivery = row.getAttribute('data-delivery-active') === '1';

                function calculateRowTotal() {
                    const weight = parseFloat(weightInput.value) || 0;
                    let total = weight * pricePerKg;

                    if (isDelivery && distanceInput) {
                        const distance = parseFloat(distanceInput.value) || 0;
                        const flatDeliveryFee = 5000;
                        const pricePerKm = 2000;
                        
                        total += flatDeliveryFee + (distance * pricePerKm);
                    }

                    displayTotal.textContent = 'Rp ' + total.toLocaleString('id-ID', { minimumFractionDigits: 0 });
                }

                weightInput.addEventListener('input', calculateRowTotal);
                if (distanceInput) {
                    distanceInput.addEventListener('input', calculateRowTotal);
                }
            });
        });
    </script>
</body>
</html>