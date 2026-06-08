<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderStatusLog extends Model
{
    // Matikan timestamps default karena kita hanya butuh created_at yang dihandle migration
    public $timestamps = false; 

    protected $fillable = ['order_id', 'status', 'created_at'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}