<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'payment_id',
        'amount',
        'payment_method',
        'status',
        'snap_token',
        'expired_at',
        'payment_data'
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'payment_data' => 'array',
        'amount' => 'decimal:2'
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function isPaid()
    {
        return $this->status === 'success';
    }

    public function isExpired()
    {
        return now()->greaterThan($this->expired_at);
    }
}