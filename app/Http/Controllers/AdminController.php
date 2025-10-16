<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Reservation;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalRooms = Room::count();
        $totalReservations = Reservation::count();
        $pendingReservations = Reservation::where('status', 'pending')->count();
        $recentReservations = Reservation::with(['user', 'room'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalRooms',
            'totalReservations',
            'pendingReservations',
            'recentReservations'
        ));
    }
}