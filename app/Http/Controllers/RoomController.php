<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::where('available', true);

        // Filter berdasarkan ketersediaan tanggal
        if ($request->has(['check_in', 'check_out']) && $request->check_in && $request->check_out) {
            $checkIn = $request->check_in;
            $checkOut = $request->check_out;
            
            // Get rooms that are available for the selected dates
            $query->whereDoesntHave('reservations', function($q) use ($checkIn, $checkOut) {
                $q->where(function($query) use ($checkIn, $checkOut) {
                    $query->whereBetween('check_in', [$checkIn, $checkOut])
                        ->orWhereBetween('check_out', [$checkIn, $checkOut])
                        ->orWhere(function($query) use ($checkIn, $checkOut) {
                            $query->where('check_in', '<=', $checkIn)
                                    ->where('check_out', '>=', $checkOut);
                        });
                })
                ->where('status', 'confirmed'); // Only consider confirmed reservations
            });
        }

        $rooms = $query->get();

        // Pass the search parameters to view to pre-fill the form
        return view('rooms.index', compact('rooms'))
            ->with('checkIn', $request->check_in)
            ->with('checkOut', $request->check_out)
            ->with('guests', $request->guests);
    }

    public function show(Room $room)
    {
        return view('rooms.show', compact('room'));
    }

    // Admin methods
    public function adminIndex()
    {
        $rooms = Room::all();
        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('admin.rooms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_number' => 'required|unique:rooms',
            'type' => 'required',
            'price' => 'required|numeric|min:0',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('room-images', 'public');
        }

        Room::create([
            'room_number' => $request->room_number,
            'type' => $request->type,
            'price' => $request->price,
            'description' => $request->description,
            'image' => $imagePath,
            'available' => $request->available ?? true,
        ]);

        return redirect()->route('admin.rooms.index')
                        ->with('success', 'Room created successfully.');
    }

    public function edit(Room $room)
    {
        return view('admin.rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $request->validate([
            'room_number' => 'required|unique:rooms,room_number,' . $room->id,
            'type' => 'required',
            'price' => 'required|numeric|min:0',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'available' => 'required|boolean',
        ]);

        $data = [
            'room_number' => $request->room_number,
            'type' => $request->type,
            'price' => $request->price,
            'description' => $request->description,
            'available' => $request->available,
        ];

        // Handle image removal
        if ($request->has('remove_image') && $request->remove_image) {
            if ($room->image) {
                Storage::disk('public')->delete($room->image);
            }
            $data['image'] = null;
        }

        // Handle new image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($room->image) {
                Storage::disk('public')->delete($room->image);
            }
            
            $data['image'] = $request->file('image')->store('room-images', 'public');
        }

        $room->update($data);

        return redirect()->route('admin.rooms.index')
                        ->with('success', 'Room updated successfully.');
    }

    public function destroy(Room $room)
    {
        // Delete image if exists
        if ($room->image) {
            Storage::disk('public')->delete($room->image);
        }

        $room->delete();

        return redirect()->route('admin.rooms.index')
                        ->with('success', 'Room deleted successfully.');
    }
}