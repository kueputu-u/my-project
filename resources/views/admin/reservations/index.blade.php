@extends('layouts.app')

@section('title', 'Manage Reservations - Admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Manage Reservations</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('admin.reservations.index') }}" 
               class="px-4 py-2 rounded {{ request('status') ? 'bg-gray-200' : 'bg-blue-600 text-white' }}">
                All
            </a>
            <a href="{{ route('admin.reservations.index', ['status' => 'pending']) }}" 
               class="px-4 py-2 rounded {{ request('status') == 'pending' ? 'bg-yellow-600 text-white' : 'bg-gray-200' }}">
                Pending
            </a>
            <a href="{{ route('admin.reservations.index', ['status' => 'confirmed']) }}" 
               class="px-4 py-2 rounded {{ request('status') == 'confirmed' ? 'bg-green-600 text-white' : 'bg-gray-200' }}">
                Confirmed
            </a>
            <a href="{{ route('admin.reservations.index', ['status' => 'cancelled']) }}" 
               class="px-4 py-2 rounded {{ request('status') == 'cancelled' ? 'bg-red-600 text-white' : 'bg-gray-200' }}">
                Cancelled
            </a>
        </div>
    </div>

<div class="bg-white rounded-lg shadow-md p-4 mb-4">
    <div class="flex justify-between items-center">
        <h3 class="text-lg font-semibold">Bulk Actions</h3>
        <div class="flex space-x-2">
            <!-- Bulk delete cancelled reservations -->
            <form action="{{ route('admin.reservations.bulkDelete') }}" method="POST" id="bulkDeleteForm">
                @csrf
                @method('DELETE')
                <button type="button" 
                        onclick="if(confirm('Are you sure you want to delete all cancelled reservations?')) { document.getElementById('bulkDeleteForm').submit(); }"
                        class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                    <i class="fas fa-trash mr-2"></i>Delete All Cancelled
                </button>
            </form>
        </div>
    </div>
</div>

    @if($reservations->count() > 0)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guest</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guests</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($reservations as $reservation)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $reservation->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $reservation->user->email }}</div>
                            <div class="text-sm text-gray-500">{{ $reservation->user->phone }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $reservation->room->type }}</div>
                            <div class="text-sm text-gray-500">Room {{ $reservation->room->room_number }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $reservation->check_in->format('M d, Y') }}</div>
                            <div class="text-sm text-gray-500">to {{ $reservation->check_out->format('M d, Y') }}</div>
                            <div class="text-sm text-gray-500">
                                {{ $reservation->check_in->diffInDays($reservation->check_out) }} nights
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $reservation->guests }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            Rp {{ number_format($reservation->total_price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($reservation->status == 'confirmed') bg-green-100 text-green-800
                                @elseif($reservation->status == 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($reservation->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('reservations.show', $reservation) }}" class="text-blue-600 hover:text-blue-900 mr-4">
                                <i class="fas fa-eye mr-1"></i>View
                            </a>
                            @if($reservation->status == 'pending')
                            <form action="{{ route('admin.reservations.confirm', $reservation) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-900 mr-4">
                                    <i class="fas fa-check mr-1"></i>Confirm
                                </button>
                            </form>
                            @endif
                            
                            @if($reservation->status == 'cancelled')
                            <form action="{{ route('admin.reservations.destroy', $reservation) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-900"
                                        onclick="return confirm('Are you sure you want to delete this cancelled reservation? This action cannot be undone.')">
                                    <i class="fas fa-trash mr-1"></i>Delete
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $reservations->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-calendar-times text-6xl text-gray-400 mb-4"></i>
            <h3 class="text-2xl font-semibold text-gray-600">No reservations found</h3>
            <p class="text-gray-500">There are no reservations matching your criteria.</p>
        </div>
    @endif
</div>
@endsection