@extends('layouts.app')

@section('title', 'Our Rooms - Air Hotel')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold mb-8">Our Rooms</h1>
    
    <!-- Search and Filter -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <form action="{{ route('rooms.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Check-in</label>
                <input type="date" 
                       name="check_in" 
                       value="{{ old('check_in', $checkIn ?? '') }}"
                       min="{{ date('Y-m-d') }}"
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                       required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Check-out</label>
                <input type="date" 
                       name="check_out" 
                       value="{{ old('check_out', $checkOut ?? '') }}"
                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                       required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Guests</label>
                <select name="guests" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                    <option value="1" {{ (old('guests', $guests ?? '') == '1') ? 'selected' : '' }}>1 Guest</option>
                    <option value="2" {{ (old('guests', $guests ?? '') == '2') ? 'selected' : '' }}>2 Guests</option>
                    <option value="3" {{ (old('guests', $guests ?? '') == '3') ? 'selected' : '' }}>3 Guests</option>
                    <option value="4" {{ (old('guests', $guests ?? '') == '4') ? 'selected' : '' }}>4 Guests</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-300">
                    Check Availability
                </button>
            </div>
        </form>

        @if($errors->any())
            <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Results Info -->
    @if(request()->has('check_in') && request()->has('check_out'))
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-semibold text-blue-800">Search Results</h3>
                <p class="text-blue-600 text-sm">
                    Check-in: {{ \Carbon\Carbon::parse(request('check_in'))->format('M d, Y') }} • 
                    Check-out: {{ \Carbon\Carbon::parse(request('check_out'))->format('M d, Y') }} • 
                    Guests: {{ request('guests', 1) }}
                </p>
            </div>
            <div class="text-sm text-blue-600">
                {{ $rooms->count() }} room(s) available
            </div>
        </div>
    </div>
    @endif

    <!-- Rooms Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
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
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-xl font-semibold">{{ $room->type }} Room</h3>
                    <span class="bg-green-100 text-green-800 text-sm px-2 py-1 rounded">Room {{ $room->room_number }}</span>
                </div>
                <p class="text-gray-600 mb-4">{{ $room->description }}</p>
                
                <!-- Availability Badge -->
                @if(request()->has('check_in') && request()->has('check_out'))
                    @php
                        $isAvailable = $room->isAvailable(request('check_in'), request('check_out'));
                    @endphp
                    <div class="mb-3">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold 
                            {{ $isAvailable ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $isAvailable ? 'Available' : 'Not Available' }}
                        </span>
                    </div>
                @endif

                <div class="flex justify-between items-center">
                    <span class="text-2xl font-bold text-blue-600">Rp {{ number_format($room->price, 0, ',', '.') }}/night</span>
                    @if(request()->has('check_in') && request()->has('check_out') && $room->isAvailable(request('check_in'), request('check_out')))
                        <a href="{{ route('reservations.create', $room) }}?check_in={{ request('check_in') }}&check_out={{ request('check_out') }}&guests={{ request('guests', 1) }}" 
                           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-300">
                            Book Now
                        </a>
                    @else
                        <a href="{{ route('rooms.show', $room) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-300">
                            View Details
                        </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($rooms->isEmpty())
    <div class="text-center py-12">
        <i class="fas fa-bed text-6xl text-gray-400 mb-4"></i>
        <h3 class="text-2xl font-semibold text-gray-600">
            @if(request()->has('check_in') && request()->has('check_out'))
                No rooms available for selected dates
            @else
                No rooms available
            @endif
        </h3>
        <p class="text-gray-500 mb-4">
            @if(request()->has('check_in') && request()->has('check_out'))
                Please try different dates or contact us for availability.
            @else
                Please check back later or contact us for availability.
            @endif
        </p>
        @if(request()->has('check_in') && request()->has('check_out'))
            <a href="{{ route('rooms.index') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-300">
                View All Rooms
            </a>
        @endif
    </div>
    @endif
</div>

<script>
    // Set min date for check-out based on check-in
    document.addEventListener('DOMContentLoaded', function() {
        const checkInInput = document.querySelector('input[name="check_in"]');
        const checkOutInput = document.querySelector('input[name="check_out"]');

        checkInInput.addEventListener('change', function() {
            if (this.value) {
                const nextDay = new Date(this.value);
                nextDay.setDate(nextDay.getDate() + 1);
                checkOutInput.min = nextDay.toISOString().split('T')[0];
                
                // If check-out is before new min date, clear it
                if (checkOutInput.value && checkOutInput.value < checkOutInput.min) {
                    checkOutInput.value = '';
                }
            }
        });

        // Initialize min dates
        const today = new Date().toISOString().split('T')[0];
        checkInInput.min = today;
        
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        checkOutInput.min = tomorrow.toISOString().split('T')[0];
    });
</script>
@endsection