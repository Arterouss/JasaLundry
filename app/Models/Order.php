<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    // Mengizinkan mass assignment untuk kolom-kolom ini
    protected $fillable = [
        'customer_id', 'walk_in_name', 'service_id', 'perfume_id',
        'is_pickup_delivery', 'gps_address', 'payment_method',
        'payment_status', 'weight', 'distance_km', 'grand_total',
        'status', 'estimated_completion_time'
    ];

    // Laravel 11/12 casting syntax
    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class, // Enum yang kita buat sebelumnya
            'is_pickup_delivery' => 'boolean',
            'estimated_completion_time' => 'datetime',
        ];
    }

    // Relasi ke User (Pelanggan)
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    // Relasi ke Master Layanan
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    // Relasi ke Master Parfum
    public function perfume(): BelongsTo
    {
        return $this->belongsTo(Perfume::class);
    }

    // Relasi ke Log Status (Untuk fitur ceklis riwayat)
    public function statusLogs(): HasMany
    {
        return $this->hasMany(OrderStatusLog::class)->orderBy('created_at', 'asc');
    }
}