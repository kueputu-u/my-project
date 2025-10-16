@extends('layouts.app')

@section('title', 'My Reservations - Air Hotel')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">My Reservations</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if($reservations->count() > 0)
        <div class="grid grid-cols-1 gap-6">
            @foreach($reservations as $reservation)
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 
                @if($reservation->status == 'confirmed') border-green-500
                @elseif($reservation->status == 'pending') border-yellow-500
                @else border-red-500 @endif">
                <div class="flex flex-col md:flex-row md:justify-between md:items-start">
                    <div class="flex-1">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="text-xl font-semibold">{{ $reservation->room->type }} Room</h3>
                                <p class="text-gray-600">Room {{ $reservation->room->room_number }}</p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-sm font-medium
                                @if($reservation->status == 'confirmed') bg-green-100 text-green-800
                                @elseif($reservation->status == 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($reservation->status) }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <p class="text-sm text-gray-500">Check-in</p>
                                <p class="font-semibold">{{ $reservation->check_in->format('F d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Check-out</p>
                                <p class="font-semibold">{{ $reservation->check_out->format('F d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Duration</p>
                                <p class="font-semibold">
                                    {{ $reservation->check_in->diffInDays($reservation->check_out) }} nights
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Guests</p>
                                <p class="font-semibold">{{ $reservation->guests }} guest(s)</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Total Price</p>
                                <p class="text-xl font-bold text-blue-600">Rp {{ number_format($reservation->total_price, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        @if($reservation->special_requests)
                        <div class="mt-4">
                            <p class="text-sm text-gray-500">Special Requests</p>
                            <p class="font-semibold">{{ $reservation->special_requests }}</p>
                        </div>
                        @endif
                    </div>

                    <div class="mt-4 md:mt-0 md:ml-6 flex space-x-2">
                        <a href="{{ route('reservations.show', $reservation) }}" 
                           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-300">
                            <i class="fas fa-eye mr-1"></i>View Details
                        </a>
                        
                        @if($reservation->status == 'pending')
                        <form action="{{ route('reservations.cancel', $reservation) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition duration-300"
                                    onclick="return confirm('Are you sure you want to cancel this reservation?')">
                                <i class="fas fa-times mr-1"></i>Cancel
                            </button>
                        </form>
                        @endif
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-gray-200">
                    <p class="text-sm text-gray-500">
                        Booked on: {{ $reservation->created_at->format('F d, Y \\a\\t h:i A') }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="min-h-[60vh] flex items-center justify-center">
            <div class="text-center py-12">
                <i class="fas fa-calendar-times text-6xl text-gray-400 mb-6"></i>
                <h3 class="text-2xl font-semibold text-gray-600 mb-4">No reservations yet</h3>
                <p class="text-gray-500 mb-8 text-lg">Start planning your stay with us!</p>
                <a href="{{ route('rooms.index') }}" class="bg-blue-600 text-white px-8 py-4 rounded-lg hover:bg-blue-700 transition duration-300 text-lg font-semibold">
                    Browse Available Rooms
                </a>
            </div>
        </div>
    @endif
</div>
@endsection