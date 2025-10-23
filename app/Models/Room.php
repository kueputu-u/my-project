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
        // Validasi input tanggal
        if (empty($checkIn) || empty($checkOut)) {
            return true; // Jika tanggal kosong, anggap room available
        }

        try {
            // Pastikan tanggal valid
            $checkIn = \Carbon\Carbon::parse($checkIn);
            $checkOut = \Carbon\Carbon::parse($checkOut);
            
            // Pastikan check-out setelah check-in
            if ($checkOut->lte($checkIn)) {
                return true;
            }
            
            return !$this->reservations()
                ->where(function($query) use ($checkIn, $checkOut) {
                    $query->whereBetween('check_in', [$checkIn, $checkOut])
                        ->orWhereBetween('check_out', [$checkIn, $checkOut])
                        ->orWhere(function($query) use ($checkIn, $checkOut) {
                            $query->where('check_in', '<=', $checkIn)
                                ->where('check_out', '>=', $checkOut);
                        });
                })
                ->whereIn('status', ['confirmed', 'pending'])
                ->exists();
        } catch (\Exception $e) {
            // Jika ada error parsing tanggal, return true
            return true;
        }
    }

    public function getFeaturedImageUrlAttribute()
    {
        // Cek apakah ada featured image di room_images
        if ($this->featuredImage) {
            return $this->featuredImage->image_url;
        }
        
        // Cek apakah ada images di room_images
        if ($this->images->count() > 0) {
            return $this->images->first()->image_url;
        }
        
        // Fallback ke kolom image lama (single image)
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        
        // Jika tidak ada gambar sama sekali
        return null;
    }

    public function featuredImage()
    {
        return $this->hasOne(RoomImage::class)->where('is_featured', true);
    }

    public function images()
    {
        return $this->hasMany(RoomImage::class)->orderBy('order');
    }
}