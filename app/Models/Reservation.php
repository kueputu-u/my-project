<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'room_id',
        'check_in',
        'check_out',
        'guests',
        'total_price',
        'status',
        'special_requests'
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function isPaid()
    {
        return $this->payment && $this->payment->isPaid();
    }

    public function getPaymentStatusAttribute()
    {
        if (!$this->payment) return 'unpaid';
        return $this->payment->status;
    }
}