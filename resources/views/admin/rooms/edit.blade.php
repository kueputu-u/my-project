@extends('layouts.app')

@section('title', 'Edit Room - Admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center mb-6">
            <a href="{{ route('admin.rooms.index') }}" class="text-blue-600 hover:text-blue-800 mr-4">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-3xl font-bold">Edit Room</h1>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('admin.rooms.update', $room) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="room_number" class="block text-sm font-medium text-gray-700 mb-2">Room Number *</label>
                        <input type="text" id="room_number" name="room_number" value="{{ old('room_number', $room->room_number) }}" 
                               class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                               required>
                        @error('room_number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Room Type *</label>
                        <select id="type" name="type" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" required>
                            <option value="">Select Type</option>
                            <option value="Standard" {{ old('type', $room->type) == 'Standard' ? 'selected' : '' }}>Standard</option>
                            <option value="Deluxe" {{ old('type', $room->type) == 'Deluxe' ? 'selected' : '' }}>Deluxe</option>
                            <option value="Suite" {{ old('type', $room->type) == 'Suite' ? 'selected' : '' }}>Suite</option>
                            <option value="Executive" {{ old('type', $room->type) == 'Executive' ? 'selected' : '' }}>Executive</option>
                            <option value="Presidential" {{ old('type', $room->type) == 'Presidential' ? 'selected' : '' }}>Presidential</option>
                        </select>
                        @error('type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price per Night (Rp) *</label>
                        <input type="number" id="price" name="price" value="{{ old('price', $room->price) }}" step="0.01" min="0"
                               class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                               required>
                        @error('price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="available" class="block text-sm font-medium text-gray-700 mb-2">Availability</label>
                        <select id="available" name="available" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                            <option value="1" {{ old('available', $room->available) ? 'selected' : '' }}>Available</option>
                            <option value="0" {{ !old('available', $room->available) ? 'selected' : '' }}>Not Available</option>
                        </select>
                    </div>
                </div>

                <!-- Image Upload -->
                <div class="mt-6">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Room Image</label>
                    
                    <!-- Current Image -->
                    @if($room->image)
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-2">Current Image:</p>
                        <img src="{{ asset('storage/' . $room->image) }}" 
                             alt="Room {{ $room->room_number }}" 
                             class="max-w-xs rounded-lg shadow-md mb-2">
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" id="remove_image" name="remove_image" value="1" class="rounded">
                            <label for="remove_image" class="text-sm text-red-600">Remove current image</label>
                        </div>
                    </div>
                    @endif

                    <!-- New Image Upload -->
                    <input type="file" id="image" name="image" 
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                           accept="image/*">
                    @error('image')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-sm text-gray-500 mt-1">Format: JPEG, PNG, JPG, GIF. Max: 2MB</p>
                </div>

                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                    <textarea id="description" name="description" rows="4"
                              class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                              required>{{ old('description', $room->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('admin.rooms.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600 transition duration-300">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition duration-300">
                        Update Room
                    </button>
                </div>
            </form>
        </div>

        <!-- Danger Zone -->
        <div class="bg-white rounded-lg shadow-md p-6 mt-6 border border-red-200">
            <h3 class="text-lg font-semibold text-red-700 mb-4">Danger Zone</h3>
            <p class="text-gray-600 mb-4">Once you delete a room, there is no going back. Please be certain.</p>
            <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST" 
                  onsubmit="return confirm('Are you sure you want to delete this room? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700 transition duration-300">
                    <i class="fas fa-trash mr-2"></i>Delete This Room
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // Preview image before upload
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Remove existing preview if any
                const existingPreview = document.getElementById('imagePreview');
                if (existingPreview) {
                    existingPreview.remove();
                }
                
                // Create preview
                const preview = document.createElement('div');
                preview.id = 'imagePreview';
                preview.className = 'mt-4';
                preview.innerHTML = `
                    <p class="text-sm text-gray-600 mb-2">New Image Preview:</p>
                    <img src="${e.target.result}" class="max-w-xs rounded-lg shadow-md">
                `;
                document.querySelector('form').insertBefore(preview, document.querySelector('button[type="submit"]').parentElement);
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection