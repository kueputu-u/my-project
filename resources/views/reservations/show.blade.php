@extends('layouts.app')

@section('title', 'Reservation Details - Air Hotel')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold">Reservation Details</h1>
            <a href="{{ route('reservations.index') }}" class="text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i>Back to Reservations
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Header -->
            <div class="bg-blue-600 text-white p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-2xl font-bold">{{ $reservation->room->type }} Room</h2>
                        <p class="text-blue-100">Reservation #{{ $reservation->id }}</p>
                    </div>
                    <span class="px-4 py-2 rounded-full text-sm font-semibold
                        @if($reservation->status == 'confirmed') bg-green-500
                        @elseif($reservation->status == 'pending') bg-yellow-500
                        @else bg-red-500 @endif">
                        {{ ucfirst($reservation->status) }}
                    </span>
                </div>
            </div>

            <!-- Details -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Room Information -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Room Information</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Room Type:</span>
                                <span class="font-semibold">{{ $reservation->room->type }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Room Number:</span>
                                <span class="font-semibold">{{ $reservation->room->room_number }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Price per Night:</span>
                                <span class="font-semibold">Rp{{ $reservation->room->price }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Stay Details -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Stay Details</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Check-in:</span>
                                <span class="font-semibold">{{ $reservation->check_in->format('F d, Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Check-out:</span>
                                <span class="font-semibold">{{ $reservation->check_out->format('F d, Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Duration:</span>
                                <span class="font-semibold">
                                    {{ $reservation->check_in->diffInDays($reservation->check_out) }} nights
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Guests:</span>
                                <span class="font-semibold">{{ $reservation->guests }} guest(s)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Special Requests -->
                @if($reservation->special_requests)
                <div class="mt-6 pt-6 border-t">
                    <h3 class="text-lg font-semibold mb-3">Special Requests</h3>
                    <p class="text-gray-700 bg-gray-50 p-4 rounded">{{ $reservation->special_requests }}</p>
                </div>
                @endif

                <!-- Price Breakdown -->
                <div class="mt-6 pt-6 border-t">
                    <h3 class="text-lg font-semibold mb-4">Price Breakdown</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span>Room rate:</span>
                                <span>Rp {{ number_format($reservation->room->price, 0, ',', '.') }} × {{ $reservation->check_in->diffInDays($reservation->check_out) }} nights</span>
                            </div>
                            <div class="border-t pt-2 flex justify-between text-lg font-bold">
                                <span>Total Amount:</span>
                                <span class="text-blue-600">Rp {{ number_format($reservation->total_price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                @if($reservation->status == 'pending' && Auth::user()->id == $reservation->user_id)
                <div class="mt-6 pt-6 border-t">
                    <form action="{{ route('reservations.cancel', $reservation) }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700 transition duration-300"
                                onclick="return confirm('Are you sure you want to cancel this reservation?')">
                            <i class="fas fa-times mr-2"></i>Cancel Reservation
                        </button>
                    </form>
                </div>
                @endif

                <!-- Admin Actions -->
                @if(Auth::user()->isAdmin() && $reservation->status == 'pending')
                <div class="mt-6 pt-6 border-t">
                    <form action="{{ route('admin.reservations.confirm', $reservation) }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 transition duration-300">
                            <i class="fas fa-check mr-2"></i>Confirm Reservation
                        </button>
                    </form>
                </div>
                @endif
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-6 py-4 border-t">
                <p class="text-sm text-gray-600">
                    Reservation created on: {{ $reservation->created_at->format('F d, Y \\a\\t h:i A') }}
                    @if($reservation->updated_at != $reservation->created_at)
                    <br>Last updated: {{ $reservation->updated_at->format('F d, Y \\a\\t h:i A') }}
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>
@endsection