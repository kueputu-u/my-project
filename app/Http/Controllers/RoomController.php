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

        // Filter by room type
        if ($request->filled('room_type')) {
            $query->where('type', $request->room_type);
        }

        // Filter by price range
        if ($request->filled('price_range')) {
            $priceRange = explode('-', $request->price_range);
            if (count($priceRange) === 2) {
                $minPrice = (float) $priceRange[0];
                $maxPrice = (float) $priceRange[1];
                $query->whereBetween('price', [$minPrice, $maxPrice]);
            }
        }

        // Filter by date availability - HANYA jika kedua tanggal diisi
        if ($request->filled('check_in') && $request->filled('check_out')) {
            $checkIn = $request->check_in;
            $checkOut = $request->check_out;
            
            // Validasi tanggal
            try {
                $checkInDate = \Carbon\Carbon::parse($checkIn);
                $checkOutDate = \Carbon\Carbon::parse($checkOut);
                
                // Hanya proses jika check-out setelah check-in
                if ($checkOutDate->gt($checkInDate)) {
                    $query->whereDoesntHave('reservations', function($q) use ($checkIn, $checkOut) {
                        $q->where(function($query) use ($checkIn, $checkOut) {
                            $query->whereBetween('check_in', [$checkIn, $checkOut])
                                ->orWhereBetween('check_out', [$checkIn, $checkOut])
                                ->orWhere(function($query) use ($checkIn, $checkOut) {
                                    $query->where('check_in', '<=', $checkIn)
                                        ->where('check_out', '>=', $checkOut);
                                });
                        })
                        ->whereIn('status', ['confirmed', 'pending']);
                    });
                }
            } catch (\Exception $e) {
                // Tangani error parsing tanggal
                // Tetap lanjutkan tanpa filter tanggal
            }
        }

        // Sort options
        $sort = $request->get('sort', 'price_asc');
        switch ($sort) {
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('type', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('type', 'desc');
                break;
            default:
                $query->orderBy('price', 'asc');
        }

        $rooms = $query->get();

        return view('rooms.index', compact('rooms'))
            ->with('checkIn', $request->check_in)
            ->with('checkOut', $request->check_out)
            ->with('guests', $request->guests)
            ->with('roomType', $request->room_type)
            ->with('priceRange', $request->price_range)
            ->with('sort', $sort);
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