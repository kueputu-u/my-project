@extends('layouts.app')

@section('title', 'Luxury Hotel Accommodation')

@section('content')
    <!-- Hero Section -->
    <section class="relative h-screen flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-black/50 z-10"></div>
            <div class="w-full h-full bg-gradient-to-r from-blue-900/80 to-purple-900/80"></div>
        </div>
        
        <div class="relative z-20 text-center text-white px-4 max-w-4xl animate-fade-in">
            <h1 class="text-5xl md:text-7xl font-bold mb-6 leading-tight">
                Experience 
                <span class="bg-gradient-to-r from-secondary to-yellow-300 bg-clip-text text-transparent">Luxury</span> 
                Redefined
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-gray-200 leading-relaxed">
                Discover unparalleled comfort and breathtaking views at Air Hotel. 
                Where every stay becomes an unforgettable memory.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('rooms.index') }}" 
                   class="bg-primary text-white px-8 py-4 rounded-lg hover:bg-blue-700 transition-all duration-300 font-semibold text-lg shadow-2xl shadow-blue-500/30 hover-lift">
                    <i class="fas fa-calendar-plus mr-2"></i>Book Your Stay
                </a>
                <a href="#explore" 
                   class="border-2 border-white text-white px-8 py-4 rounded-lg hover:bg-white hover:text-dark transition-all duration-300 font-semibold text-lg hover-lift">
                    <i class="fas fa-play-circle mr-2"></i>Explore More
                </a>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-20 animate-bounce">
            <a href="#explore" class="text-white">
                <i class="fas fa-chevron-down text-2xl"></i>
            </a>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div class="animate-slide-up">
                    <div class="text-4xl font-bold text-primary mb-2">50+</div>
                    <div class="text-gray-600">Luxury Rooms</div>
                </div>
                <div class="animate-slide-up" style="animation-delay: 0.1s;">
                    <div class="text-4xl font-bold text-primary mb-2">10k+</div>
                    <div class="text-gray-600">Happy Guests</div>
                </div>
                <div class="animate-slide-up" style="animation-delay: 0.2s;">
                    <div class="text-4xl font-bold text-primary mb-2">24/7</div>
                    <div class="text-gray-600">Room Service</div>
                </div>
                <div class="animate-slide-up" style="animation-delay: 0.3s;">
                    <div class="text-4xl font-bold text-primary mb-2">5★</div>
                    <div class="text-gray-600">Rating</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Rooms -->
    <section id="explore" class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-dark mb-4">Featured Rooms</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Indulge in our carefully curated selection of luxury accommodations, 
                    each designed to provide the ultimate comfort experience.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($rooms as $room)
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden hover-lift group animate-slide-up">
                    <div class="relative overflow-hidden">
                        <div class="h-64 bg-gradient-to-br from-blue-400 to-purple-600 flex items-center justify-center">
                            @if($room->featured_image_url)
                                <img src="{{ $room->featured_image_url }}" 
                                     alt="{{ $room->type }}" 
                                     class="h-full w-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <i class="fas fa-bed text-white text-6xl"></i>
                            @endif
                        </div>
                        <div class="absolute top-4 right-4">
                            <span class="bg-white/90 text-primary px-3 py-1 rounded-full text-sm font-semibold backdrop-blur-sm">
                                Room {{ $room->room_number }}
                            </span>
                        </div>
                        <div class="absolute bottom-4 left-4">
                            <span class="bg-primary text-white px-3 py-1 rounded-full text-sm font-semibold">
                                {{ $room->type }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-3">{{ $room->type }} Room</h3>
                        <p class="text-gray-600 mb-4 line-clamp-2">{{ $room->description }}</p>
                        
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center text-gray-500">
                                    <i class="fas fa-user-friends mr-1"></i>
                                    <span class="text-sm">2</span>
                                </div>
                                <div class="flex items-center text-gray-500">
                                    <i class="fas fa-wifi mr-1"></i>
                                </div>
                                <div class="flex items-center text-gray-500">
                                    <i class="fas fa-tv mr-1"></i>
                                </div>
                            </div>
                            <div class="text-2xl font-bold text-primary">
                                Rp {{ number_format($room->price, 0, ',', '.') }}
                            </div>
                        </div>
                        
                        <a href="{{ route('rooms.show', $room) }}" 
                           class="w-full bg-gradient-to-r from-primary to-blue-600 text-white py-3 rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-300 font-semibold text-center block group-hover:shadow-lg group-hover:shadow-blue-500/25">
                            View Details
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('rooms.index') }}" 
                   class="inline-flex items-center space-x-2 bg-dark text-white px-8 py-4 rounded-lg hover:bg-gray-800 transition-all duration-300 font-semibold hover-lift">
                    <span>Explore All Rooms</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Amenities Section -->
    <section id="amenities" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-dark mb-4">World-Class Amenities</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Enjoy our premium facilities and services designed to make your stay exceptional
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center group animate-slide-up">
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-swimming-pool text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Infinity Pool</h3>
                    <p class="text-gray-600">Stunning rooftop pool with panoramic city views</p>
                </div>

                <div class="text-center group animate-slide-up" style="animation-delay: 0.1s;">
                    <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-teal-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-spa text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Luxury Spa</h3>
                    <p class="text-gray-600">Rejuvenating treatments and wellness therapies</p>
                </div>

                <div class="text-center group animate-slide-up" style="animation-delay: 0.2s;">
                    <div class="w-20 h-20 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-utensils text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Fine Dining</h3>
                    <p class="text-gray-600">Award-winning restaurants and 24/7 room service</p>
                </div>

                <div class="text-center group animate-slide-up" style="animation-delay: 0.3s;">
                    <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-dumbbell text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Fitness Center</h3>
                    <p class="text-gray-600">State-of-the-art equipment with personal trainers</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 gradient-bg">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold text-white mb-6">Ready for an Unforgettable Experience?</h2>
            <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                Book your stay today and discover why Air Hotel is the choice for discerning travelers worldwide.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('rooms.index') }}" 
                   class="bg-white text-primary px-8 py-4 rounded-lg hover:bg-gray-100 transition-all duration-300 font-semibold text-lg shadow-2xl hover-lift">
                    <i class="fas fa-calendar-check mr-2"></i>Book Now
                </a>
                <a href="tel:+15551234567" 
                   class="border-2 border-white text-white px-8 py-4 rounded-lg hover:bg-white hover:text-primary transition-all duration-300 font-semibold text-lg hover-lift">
                   <i class="fas fa-phone mr-2"></i>Call Us
                </a>
            </div>
        </div>
    </section>
@endsection