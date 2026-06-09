<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatusLog extends Model
{
    use HasFactory;

    // Definisikan nama tabel jika tidak menggunakan jamak (optional)
    protected $table = 'order_status_logs';

    // Daftarkan field yang boleh diisi mass-assignment (sesuaikan dengan migration kamu)
    protected $fillable = [
        'order_id',
        'status',
        'changed_by', // Biasanya ID user/admin yang mengubah status
        'notes'
    ];

    /**
     * Relasi balik ke Order (Satu log status dimiliki oleh satu Pesanan)
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}