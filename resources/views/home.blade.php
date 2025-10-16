@extends('layouts.app')

@section('title', 'Air Hotel - Luxury Accommodation')

@section('content')
<!-- Hero Section -->
<section class="bg-blue-700 text-white py-20">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-5xl font-bold mb-6">Welcome to Air Hotel</h1>
        <p class="text-xl mb-8">Experience luxury and comfort with breathtaking views</p>
        <a href="{{ route('rooms.index') }}" class="bg-white text-blue-700 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-300">
            Book Your Stay
        </a>
    </div>
</section>

<!-- Featured Rooms -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Featured Rooms</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($rooms as $room)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300">
                <div class="h-48 bg-gray-300 flex items-center justify-center">
                    @if($room->image)
                        <img src="{{ asset('storage/' . $room->image) }}" alt="{{ $room->type }}" class="h-full w-full object-cover">
                    @else
                        <i class="fas fa-bed text-4xl text-gray-400"></i>
                    @endif
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-2">{{ $room->type }} Room</h3>
                    <p class="text-gray-600 mb-4">{{ Str::limit($room->description, 100) }}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-2xl font-bold text-blue-600">Rp {{ number_format($room->price, 0, ',', '.') }}/night</span>
                        <a href="{{ route('rooms.show', $room) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-300">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-8">
            <a href="{{ route('rooms.index') }}" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-300">
                View All Rooms
            </a>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-16 bg-gray-100">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Why Choose Air Hotel?</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-wifi text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Free WiFi</h3>
                <p class="text-gray-600">Stay connected with high-speed internet access</p>
            </div>
            <div class="text-center">
                <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-swimming-pool text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Swimming Pool</h3>
                <p class="text-gray-600">Relax in our luxurious outdoor swimming pool</p>
            </div>
            <div class="text-center">
                <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-utensils text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Fine Dining</h3>
                <p class="text-gray-600">Experience exquisite culinary delights</p>
            </div>
        </div>
    </div>
</section>
@endsection