@extends('layouts.app')

@section('title', 'Admin Dashboard - Air Hotel')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Admin Dashboard</h1>
    
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-bed text-blue-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-600">Total Rooms</h3>
                    <p class="text-2xl font-bold">{{ $totalRooms }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-calendar-check text-green-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-600">Total Reservations</h3>
                    <p class="text-2xl font-bold">{{ $totalReservations }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-600">Pending Reservations</h3>
                    <p class="text-2xl font-bold">{{ $pendingReservations }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-2xl font-bold mb-6">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('admin.rooms.index') }}" class="bg-blue-600 text-white p-4 rounded-lg text-center hover:bg-blue-700 transition duration-300">
                <i class="fas fa-bed text-2xl mb-2"></i>
                <p>Manage Rooms</p>
            </a>
            <a href="{{ route('admin.reservations.index') }}" class="bg-green-600 text-white p-4 rounded-lg text-center hover:bg-green-700 transition duration-300">
                <i class="fas fa-calendar-alt text-2xl mb-2"></i>
                <p>Manage Reservations</p>
            </a>
            <a href="{{ route('admin.rooms.create') }}" class="bg-purple-600 text-white p-4 rounded-lg text-center hover:bg-purple-700 transition duration-300">
                <i class="fas fa-plus text-2xl mb-2"></i>
                <p>Add New Room</p>
            </a>
            <a href="{{ route('home') }}" class="bg-gray-600 text-white p-4 rounded-lg text-center hover:bg-gray-700 transition duration-300">
                <i class="fas fa-home text-2xl mb-2"></i>
                <p>View Website</p>
            </a>
        </div>
    </div>

    <!-- Recent Reservations -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-6">Recent Reservations</h2>
        @if($recentReservations->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="py-3 px-4 text-left">Guest</th>
                        <th class="py-3 px-4 text-left">Room</th>
                        <th class="py-3 px-4 text-left">Check-in</th>
                        <th class="py-3 px-4 text-left">Check-out</th>
                        <th class="py-3 px-4 text-left">Status</th>
                        <th class="py-3 px-4 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentReservations as $reservation)
                    <tr class="border-b">
                        <td class="py-3 px-4">{{ $reservation->user->name }}</td>
                        <td class="py-3 px-4">Room {{ $reservation->room->room_number }}</td>
                        <td class="py-3 px-4">{{ $reservation->check_in->format('M d, Y') }}</td>
                        <td class="py-3 px-4">{{ $reservation->check_out->format('M d, Y') }}</td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 rounded-full text-xs 
                                @if($reservation->status == 'confirmed') bg-green-100 text-green-800
                                @elseif($reservation->status == 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($reservation->status) }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <a href="{{ route('reservations.show', $reservation) }}" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-gray-600 text-center py-4">No reservations found.</p>
        @endif
    </div>
</div>
@endsection