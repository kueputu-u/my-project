@extends('layouts.app')

@section('title', 'Luxury Rooms & Suites - Air Hotel')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-primary to-blue-600 py-20">
    <div class="container mx-auto px-4 text-center text-white">
        <h1 class="text-5xl font-bold mb-6">Luxury Rooms & Suites</h1>
        <p class="text-xl text-blue-100 max-w-2xl mx-auto">
            Discover our exquisite collection of accommodations, each designed to provide 
            the perfect blend of comfort, style, and sophistication.
        </p>
    </div>
</section>

<!-- Search and Filter -->
<section class="py-12 bg-white shadow-lg relative -mt-10 mx-4 rounded-2xl">
    <div class="container mx-auto px-4">
        <div class="bg-white rounded-2xl p-8 shadow-xl border border-gray-100">
            <form action="{{ route('rooms.index') }}" method="GET" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Check-in Date</label>
                        <input type="date" 
                               name="check_in" 
                               value="{{ old('check_in', $checkIn ?? '') }}"
                               class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-primary transition-colors duration-200">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Check-out Date</label>
                        <input type="date" 
                               name="check_out" 
                               value="{{ old('check_out', $checkOut ?? '') }}"
                               class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-primary transition-colors duration-200">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Guests</label>
                        <select name="guests" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-primary transition-colors duration-200">
                            <option value="">Any</option>
                            <option value="1" {{ (old('guests', $guests ?? '') == '1') ? 'selected' : '' }}>1 Guest</option>
                            <option value="2" {{ (old('guests', $guests ?? '') == '2') ? 'selected' : '' }}>2 Guests</option>
                            <option value="3" {{ (old('guests', $guests ?? '') == '3') ? 'selected' : '' }}>3 Guests</option>
                            <option value="4" {{ (old('guests', $guests ?? '') == '4') ? 'selected' : '' }}>4 Guests</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Room Type</label>
                        <select name="room_type" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-primary transition-colors duration-200">
                            <option value="">All Types</option>
                            <option value="Standard" {{ (old('room_type', $roomType ?? '') == 'Standard') ? 'selected' : '' }}>Standard</option>
                            <option value="Deluxe" {{ (old('room_type', $roomType ?? '') == 'Deluxe') ? 'selected' : '' }}>Deluxe</option>
                            <option value="Suite" {{ (old('room_type', $roomType ?? '') == 'Suite') ? 'selected' : '' }}>Suite</option>
                            <option value="Executive" {{ (old('room_type', $roomType ?? '') == 'Executive') ? 'selected' : '' }}>Executive</option>
                            <option value="Presidential" {{ (old('room_type', $roomType ?? '') == 'Presidential') ? 'selected' : '' }}>Presidential</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Price Range</label>
                        <select name="price_range" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-primary transition-colors duration-200">
                            <option value="">Any Price</option>
                            <option value="0-2000000" {{ (old('price_range', $priceRange ?? '') == '0-2000000') ? 'selected' : '' }}>Under Rp 2M</option>
                            <option value="2000000-5000000" {{ (old('price_range', $priceRange ?? '') == '2000000-5000000') ? 'selected' : '' }}>Rp 2M - 5M</option>
                            <option value="5000000-10000000" {{ (old('price_range', $priceRange ?? '') == '5000000-10000000') ? 'selected' : '' }}>Rp 5M - 10M</option>
                            <option value="10000000-999999999" {{ (old('price_range', $priceRange ?? '') == '10000000-999999999') ? 'selected' : '' }}>Over Rp 10M</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-col lg:flex-row justify-between items-center space-y-4 lg:space-y-0">
                    <div class="flex items-center space-x-4">
                        <label class="text-sm font-semibold text-gray-700">Sort by:</label>
                        <select name="sort" class="border-2 border-gray-200 rounded-xl px-4 py-2 focus:outline-none focus:border-primary transition-colors duration-200" onchange="this.form.submit()">
                            <option value="price_asc" {{ (old('sort', $sort ?? '') == 'price_asc') ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_desc" {{ (old('sort', $sort ?? '') == 'price_desc') ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="name_asc" {{ (old('sort', $sort ?? '') == 'name_asc') ? 'selected' : '' }}>Name: A to Z</option>
                            <option value="name_desc" {{ (old('sort', $sort ?? '') == 'name_desc') ? 'selected' : '' }}>Name: Z to A</option>
                        </select>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('rooms.index') }}" 
                           class="bg-gray-100 text-gray-700 px-6 py-3 rounded-xl hover:bg-gray-200 transition-all duration-300 font-semibold hover-lift">
                            <i class="fas fa-times mr-2"></i>Clear Filters
                        </a>
                        <button type="submit" 
                                class="bg-primary text-white px-8 py-3 rounded-xl hover:bg-blue-700 transition-all duration-300 font-semibold shadow-lg shadow-blue-500/25 hover-lift">
                            <i class="fas fa-search mr-2"></i>Search Rooms
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Results Info -->
@if(request()->hasAny(['check_in', 'check_out', 'room_type', 'price_range']))
<section class="py-8 bg-blue-50">
    <div class="container mx-auto px-4">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-blue-100">
            <div class="flex flex-col lg:flex-row justify-between items-center">
                <div>
                    <h3 class="font-semibold text-blue-800 text-lg mb-2">Search Results</h3>
                    <div class="flex flex-wrap gap-4 text-sm text-blue-600">
                        @if(request('check_in') && request('check_out'))
                        <span class="bg-blue-100 px-3 py-1 rounded-full">
                            <i class="fas fa-calendar mr-1"></i>
                            {{ \Carbon\Carbon::parse(request('check_in'))->format('M d') }} - {{ \Carbon\Carbon::parse(request('check_out'))->format('M d, Y') }}
                        </span>
                        @endif
                        @if(request('guests'))
                        <span class="bg-blue-100 px-3 py-1 rounded-full">
                            <i class="fas fa-user mr-1"></i>
                            {{ request('guests') }} Guest(s)
                        </span>
                        @endif
                        @if(request('room_type'))
                        <span class="bg-blue-100 px-3 py-1 rounded-full">
                            <i class="fas fa-bed mr-1"></i>
                            {{ request('room_type') }}
                        </span>
                        @endif
                    </div>
                </div>
                <div class="mt-4 lg:mt-0">
                    <span class="bg-primary text-white px-4 py-2 rounded-full font-semibold">
                        {{ $rooms->count() }} Room(s) Available
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Rooms Grid -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        @if($rooms->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
            @foreach($rooms as $room)
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden hover-lift group animate-slide-up">
                <div class="relative overflow-hidden">
                    <div class="h-64 bg-gradient-to-br from-blue-400 to-purple-600 flex items-center justify-center">
                        @if($room->featured_image_url)
                            <img src="{{ $room->featured_image_url }}" 
                                 alt="{{ $room->type }}" 
                                 class="h-full w-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="text-center text-white">
                                <i class="fas fa-bed text-6xl mb-2"></i>
                                <p class="text-lg font-semibold">Room Image</p>
                            </div>
                        @endif
                    </div>
                    <div class="absolute top-4 right-4">
                        <span class="bg-white/90 text-primary px-3 py-1 rounded-full text-sm font-semibold backdrop-blur-sm">
                            Room {{ $room->room_number }}
                        </span>
                    </div>
                    @if(request()->has('check_in') && request()->has('check_out'))
                        @php
                            $isAvailable = $room->isAvailable(request('check_in'), request('check_out'));
                        @endphp
                        <div class="absolute top-4 left-4">
                            <span class="px-3 py-1 rounded-full text-sm font-semibold backdrop-blur-sm {{ $isAvailable ? 'bg-green-500/90 text-white' : 'bg-red-500/90 text-white' }}">
                                {{ $isAvailable ? 'Available' : 'Booked' }}
                            </span>
                        </div>
                    @endif
                </div>
                
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-xl font-bold text-gray-800">{{ $room->type }} Room</h3>
                        <div class="text-2xl font-bold text-primary">
                            Rp {{ number_format($room->price, 0, ',', '.') }}
                        </div>
                    </div>
                    
                    <p class="text-gray-600 mb-6 line-clamp-2">{{ $room->description }}</p>
                    
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-4 text-gray-500">
                            <div class="flex items-center">
                                <i class="fas fa-user-friends mr-2"></i>
                                <span class="text-sm">2</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-wifi"></i>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-tv"></i>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-snowflake"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex space-x-3">
                        <a href="{{ route('rooms.show', $room) }}" 
                           class="flex-1 bg-gray-100 text-gray-700 py-3 rounded-xl hover:bg-gray-200 transition-all duration-300 font-semibold text-center group-hover:shadow-lg">
                            <i class="fas fa-eye mr-2"></i>View Details
                        </a>
                        @if(request()->has('check_in') && request()->has('check_out') && $room->isAvailable(request('check_in'), request('check_out')))
                            <a href="{{ route('reservations.create', $room) }}?check_in={{ request('check_in') }}&check_out={{ request('check_out') }}&guests={{ request('guests', 1) }}" 
                               class="flex-1 bg-gradient-to-r from-primary to-blue-600 text-white py-3 rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-300 font-semibold text-center group-hover:shadow-lg group-hover:shadow-blue-500/25">
                                <i class="fas fa-calendar-plus mr-2"></i>Book Now
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-16">
            <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-bed text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-2xl font-semibold text-gray-600 mb-4">No Rooms Available</h3>
            <p class="text-gray-500 mb-8 max-w-md mx-auto">
                @if(request()->hasAny(['check_in', 'check_out', 'room_type', 'price_range']))
                We couldn't find any rooms matching your search criteria. Please try different dates or filters.
                @else
                Currently, all our rooms are being prepared for our valued guests. Please check back later.
                @endif
            </p>
            <a href="{{ route('rooms.index') }}" 
               class="bg-primary text-white px-8 py-3 rounded-xl hover:bg-blue-700 transition-all duration-300 font-semibold inline-flex items-center space-x-2 hover-lift">
                <i class="fas fa-redo"></i>
                <span>Reset Search</span>
            </a>
        </div>
        @endif
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-r from-primary to-blue-600">
    <div class="container mx-auto px-4 text-center text-white">
        <h2 class="text-3xl font-bold mb-4">Need Help Choosing?</h2>
        <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
            Our hospitality experts are available 24/7 to help you find the perfect accommodation for your stay.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="tel:+15551234567" 
               class="bg-white text-primary px-8 py-3 rounded-xl hover:bg-gray-100 transition-all duration-300 font-semibold hover-lift">
                <i class="fas fa-phone mr-2"></i>Call Now
            </a>
            <a href="mailto:info@airhotel.com" 
               class="border-2 border-white text-white px-8 py-3 rounded-xl hover:bg-white hover:text-primary transition-all duration-300 font-semibold hover-lift">
               <i class="fas fa-envelope mr-2"></i>Email Us
            </a>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkInInput = document.querySelector('input[name="check_in"]');
        const checkOutInput = document.querySelector('input[name="check_out"]');
        const searchForm = document.querySelector('form');

        // Set min dates
        const today = new Date().toISOString().split('T')[0];
        if (checkInInput) checkInInput.min = today;
        
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        if (checkOutInput) checkOutInput.min = tomorrow.toISOString().split('T')[0];

        // Update min date for check-out when check-in changes
        if (checkInInput && checkOutInput) {
            checkInInput.addEventListener('change', function() {
                if (this.value) {
                    const nextDay = new Date(this.value);
                    nextDay.setDate(nextDay.getDate() + 1);
                    checkOutInput.min = nextDay.toISOString().split('T')[0];
                    
                    if (checkOutInput.value && checkOutInput.value < checkOutInput.min) {
                        checkOutInput.value = '';
                    }
                }
            });
        }

        // Form validation
        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                const checkIn = checkInInput?.value;
                const checkOut = checkOutInput?.value;
                
                if ((checkIn && !checkOut) || (!checkIn && checkOut)) {
                    e.preventDefault();
                    alert('Please fill both check-in and check-out dates, or leave both empty.');
                    return false;
                }
                
                if (checkIn && checkOut) {
                    const checkInDate = new Date(checkIn);
                    const checkOutDate = new Date(checkOut);
                    
                    if (checkOutDate <= checkInDate) {
                        e.preventDefault();
                        alert('Check-out date must be after check-in date.');
                        return false;
                    }
                }
            });
        }
    });
</script>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection