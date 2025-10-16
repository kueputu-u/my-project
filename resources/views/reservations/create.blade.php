@extends('layouts.app')

@section('title', 'Book Room - Air Hotel')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-8">Book Your Stay</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Room Details -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                    <h3 class="text-xl font-semibold mb-4">Room Details</h3>
                    
                    <div class="h-48 bg-gray-300 rounded mb-4 flex items-center justify-center">
                        @if($room->image)
                            <img src="{{ asset('storage/' . $room->image) }}" alt="{{ $room->type }}" class="h-full w-full object-cover rounded">
                        @else
                            <i class="fas fa-bed text-4xl text-gray-400"></i>
                        @endif
                    </div>
                    
                    <h4 class="text-lg font-semibold">{{ $room->type }} Room</h4>
                    <p class="text-gray-600 mb-2">Room {{ $room->room_number }}</p>
                    <p class="text-gray-700 mb-4">{{ $room->description }}</p>
                    
                    <div class="border-t pt-4">
                        <p class="text-2xl font-bold text-blue-600">${{ $room->price }} <span class="text-sm font-normal text-gray-600">/ night</span></p>
                    </div>
                </div>
            </div>

            <!-- Booking Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold mb-6">Booking Information</h3>

                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('reservations.store', $room) }}" method="POST">
                        @csrf

                         <!-- Hidden fields untuk pre-fill data dari search -->
                        @if($checkIn)
                            <input type="hidden" name="check_in" value="{{ $checkIn }}">
                        @endif
                        @if($checkOut)
                            <input type="hidden" name="check_out" value="{{ $checkOut }}">
                        @endif
                        @if($guests)
                            <input type="hidden" name="guests" value="{{ $guests }}">
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="check_in" class="block text-sm font-medium text-gray-700 mb-2">Check-in Date *</label>
                                <input type="date" id="check_in" name="check_in" 
                                       value="{{ old('check_in') }}"
                                       min="{{ date('Y-m-d') }}"
                                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                                       required>
                            </div>

                            <div>
                                <label for="check_out" class="block text-sm font-medium text-gray-700 mb-2">Check-out Date *</label>
                                <input type="date" id="check_out" name="check_out" 
                                       value="{{ old('check_out') }}"
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                                       required>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="guests" class="block text-sm font-medium text-gray-700 mb-2">Number of Guests *</label>
                            <select id="guests" name="guests" 
                                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                                    required>
                                <option value="">Select number of guests</option>
                                <option value="1" {{ old('guests') == 1 ? 'selected' : '' }}>1 Guest</option>
                                <option value="2" {{ old('guests') == 2 ? 'selected' : '' }}>2 Guests</option>
                                <option value="3" {{ old('guests') == 3 ? 'selected' : '' }}>3 Guests</option>
                                <option value="4" {{ old('guests') == 4 ? 'selected' : '' }}>4 Guests</option>
                            </select>
                        </div>

                        <div class="mb-6">
                            <label for="special_requests" class="block text-sm font-medium text-gray-700 mb-2">Special Requests</label>
                            <textarea id="special_requests" name="special_requests" rows="4"
                                      class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                                      placeholder="Any special requests or preferences...">{{ old('special_requests') }}</textarea>
                        </div>

                        <!-- Price Calculation -->
                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <h4 class="font-semibold mb-3">Price Breakdown</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span>Room rate per night:</span>
                                    <span>Rp <span id="roomPrice">{{ number_format($room->price, 0, ',', '.') }}</span></span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Number of nights:</span>
                                    <span id="nightsCount">0</span>
                                </div>
                                <div class="border-t pt-2 flex justify-between font-semibold">
                                    <span>Total amount:</span>
                                    <span>Rp <span id="totalPrice">0</span></span>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between items-center">
                            <a href="{{ route('rooms.show', $room) }}" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-arrow-left mr-2"></i>Back to Room
                            </a>
                            <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition duration-300">
                                Confirm Booking
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkInInput = document.getElementById('check_in');
        const checkOutInput = document.getElementById('check_out');
        const roomPrice = parseFloat(document.getElementById('roomPrice').textContent.replace(/\./g, ''));
        const nightsCount = document.getElementById('nightsCount');
        const totalPrice = document.getElementById('totalPrice');

        function calculatePrice() {
            const checkIn = new Date(checkInInput.value);
            const checkOut = new Date(checkOutInput.value);
            
            if (checkIn && checkOut && checkOut > checkIn) {
                const nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));
                nightsCount.textContent = nights;
                
                // Format total price dengan separator ribuan
                const total = nights * roomPrice;
                totalPrice.textContent = total.toLocaleString('id-ID');
            } else {
                nightsCount.textContent = '0';
                totalPrice.textContent = '0';
            }
        }

        checkInInput.addEventListener('change', calculatePrice);
        checkOutInput.addEventListener('change', calculatePrice);

        // Initialize calculation if values are pre-filled
        if (checkInInput.value && checkOutInput.value) {
            calculatePrice();
        }
    });
</script>
@endsection