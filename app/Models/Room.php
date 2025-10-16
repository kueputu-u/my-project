<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_number',
        'type',
        'price',
        'description',
        'image',
        'available'
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function isAvailable($checkIn, $checkOut)
    {
        return !$this->reservations()
            ->where(function($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in', [$checkIn, $checkOut])
                    ->orWhereBetween('check_out', [$checkIn, $checkOut])
                    ->orWhere(function($query) use ($checkIn, $checkOut) {
                        $query->where('check_in', '<=', $checkIn)
                                ->where('check_out', '>=', $checkOut);
                    });
            })
            ->where('status', 'confirmed')
            ->exists();
    }
}