<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = ['name', 'price_per_kg', 'estimated_minutes'];

    // Hubungan: Satu jenis layanan bisa dipakai di banyak pesanan
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}