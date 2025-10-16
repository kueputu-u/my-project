@extends('layouts.app')

@section('title', $room->type . ' Room - Air Hotel')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="md:flex">
            <div class="md:w-1/2">
                <div class="h-64 md:h-full bg-gray-300 flex items-center justify-center">
                    @if($room->image)
                        <img src="{{ asset('storage/' . $room->image) }}" alt="{{ $room->type }}" class="h-full w-full object-cover">
                    @else
                        <i class="fas fa-bed text-6xl text-gray-400"></i>
                    @endif
                </div>
            </div>
            <div class="md:w-1/2 p-8">
                <div class="flex justify-between items-start mb-4">
                    <h1 class="text-3xl font-bold">{{ $room->type }} Room</h1>
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full">Room {{ $room->room_number }}</span>
                </div>
                
                <p class="text-gray-600 mb-6 text-lg">{{ $room->description }}</p>
                
                <div class="space-y-4 mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-user-friends text-gray-400 mr-3"></i>
                        <span>Up to {{ $room->type === 'Single' ? 1 : 2 }} guests</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-wifi text-gray-400 mr-3"></i>
                        <span>Free WiFi</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-tv text-gray-400 mr-3"></i>
                        <span>Flat-screen TV</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-snowflake text-gray-400 mr-3"></i>
                        <span>Air Conditioning</span>
                    </div>
                </div>

                <div class="border-t pt-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-3xl font-bold text-blue-600">Rp {{ number_format($room->price, 0, ',', '.') }}</span>
                            <span class="text-gray-600">/night</span>
                        </div>
                        @auth
                            <a href="{{ route('reservations.create', $room) }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-300">
                                Book This Room
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-300">
                                Login to Book
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Back to Rooms -->
    <div class="mt-6">
        <a href="{{ route('rooms.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back to All Rooms
        </a>
    </div>
</div>
@endsection