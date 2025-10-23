<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomImageController extends Controller
{
    public function store(Request $request, Room $room)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'captions.*' => 'nullable|string|max:255'
        ]);

        $images = [];

        foreach ($request->file('images') as $index => $image) {
            $path = $image->store('room-gallery', 'public');
            
            $roomImage = RoomImage::create([
                'room_id' => $room->id,
                'image_path' => $path,
                'caption' => $request->captions[$index] ?? null,
                'order' => RoomImage::where('room_id', $room->id)->max('order') + 1
            ]);

            $images[] = $roomImage;
        }

        return back()->with('success', 'Images uploaded successfully.');
    }

    public function destroy(RoomImage $roomImage)
    {
        Storage::disk('public')->delete($roomImage->image_path);
        $roomImage->delete();

        return back()->with('success', 'Image deleted successfully.');
    }

    public function setFeatured(RoomImage $roomImage)
    {
        // Remove featured status from all images in this room
        RoomImage::where('room_id', $roomImage->room_id)
                ->update(['is_featured' => false]);

        // Set this image as featured
        $roomImage->update(['is_featured' => true]);

        return back()->with('success', 'Featured image updated.');
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'images' => 'required|array'
        ]);

        foreach ($request->images as $order => $id) {
            RoomImage::where('id', $id)->update(['order' => $order]);
        }

        return response()->json(['success' => true]);
    }
}