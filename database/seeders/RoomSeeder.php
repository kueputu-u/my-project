<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            [
                'room_number' => '101',
                'type' => 'Standard',
                'price' => 150000,
                'description' => 'Comfortable standard room with all basic amenities. Perfect for solo travelers or couples.',
                'available' => true,
            ],
            [
                'room_number' => '102',
                'type' => 'Deluxe',
                'price' => 250000,
                'description' => 'Spacious deluxe room with premium amenities and beautiful city views.',
                'available' => true,
            ],
            [
                'room_number' => '201',
                'type' => 'Suite',
                'price' => 400000,
                'description' => 'Luxurious suite with separate living area, premium bathroom, and panoramic views.',
                'available' => true,
            ],
            [
                'room_number' => '202',
                'type' => 'Standard',
                'price' => 150000,
                'description' => 'Comfortable standard room with queen-sized bed and modern amenities.',
                'available' => true,
            ],
            [
                'room_number' => '301',
                'type' => 'Deluxe',
                'price' => 250000,
                'description' => 'Deluxe room with balcony, premium bedding, and upgraded bathroom facilities.',
                'available' => true,
            ],
            [
                'room_number' => '302',
                'type' => 'Suite',
                'price' => 450000,
                'description' => 'Executive suite with Jacuzzi, private balcony, and premium service.',
                'available' => true,
            ],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}