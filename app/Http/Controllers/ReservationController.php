<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ReservationConfirmed;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Auth::user()->reservations()->with('room')->get();
        return view('reservations.index', compact('reservations'));
    }

    public function create(Room $room)
    {
        // Pre-fill form dengan data dari search jika ada
        $checkIn = request('check_in');
        $checkOut = request('check_out');
        $guests = request('guests', 1);

        return view('reservations.create', compact('room', 'checkIn', 'checkOut', 'guests'));
    }

    public function store(Request $request, Room $room)
    {
        $request->validate([
            'check_in' => 'required|date|after:today',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1',
        ]);

        // Check room availability
        if (!$room->isAvailable($request->check_in, $request->check_out)) {
            return back()->with('error', 'Room is not available for the selected dates.');
        }

        // Calculate total price
        $nights = \Carbon\Carbon::parse($request->check_in)
            ->diffInDays($request->check_out);
        $totalPrice = $nights * $room->price;

        Reservation::create([
            'user_id' => Auth::id(),
            'room_id' => $room->id,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'guests' => $request->guests,
            'total_price' => $totalPrice,
            'special_requests' => $request->special_requests,
        ]);

        return redirect()->route('reservations.index')
                        ->with('success', 'Reservation created successfully.');
    }

    public function show(Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        return view('reservations.show', compact('reservation'));
    }

    public function cancel(Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id()) {
            abort(403);
        }

        $reservation->update(['status' => 'cancelled']);

        return back()->with('success', 'Reservation cancelled successfully.');
    }

    // Admin methods
    public function adminIndex(Request $request)
    {
        $query = Reservation::with(['user', 'room']);
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        $reservations = $query->latest()->paginate(10);
        
        return view('admin.reservations.index', compact('reservations'));
    }

    public function confirm(Reservation $reservation)
    {
        $reservation->update(['status' => 'confirmed']);
        
        // Send notification
        $reservation->user->notify(new ReservationConfirmed($reservation));
        
        return back()->with('success', 'Reservation confirmed successfully.');
    }

    public function destroy(Reservation $reservation)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $reservation->delete();

        return redirect()->route('admin.reservations.index')
                        ->with('success', 'Reservation deleted successfully.');
    }

    public function bulkDelete()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $deletedCount = Reservation::where('status', 'cancelled')->delete();

        return redirect()->route('admin.reservations.index')
                        ->with('success', "Successfully deleted {$deletedCount} cancelled reservations.");
    }
}