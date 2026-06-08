<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Perfume extends Model
{
    protected $fillable = ['name'];

    // Hubungan: Satu jenis parfum bisa dipakai di banyak pesanan
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}