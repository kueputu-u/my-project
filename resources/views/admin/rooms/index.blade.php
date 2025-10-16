@extends('layouts.app')

@section('title', 'Manage Rooms - Admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">Manage Rooms</h1>
        <a href="{{ route('admin.rooms.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-300">
            <i class="fas fa-plus mr-2"></i>Add New Room
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room Number</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Availability</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($rooms as $room)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $room->room_number }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $room->type }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">Rp {{ number_format($room->price, 0, ',', '.') }}/night</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $room->available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $room->available ? 'Available' : 'Occupied' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('admin.rooms.edit', $room) }}" class="text-blue-600 hover:text-blue-900 mr-4">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </a>
                        <a href="{{ route('rooms.show', $room) }}" class="text-green-600 hover:text-green-900 mr-4">
                            <i class="fas fa-eye mr-1"></i>View
                        </a>
                        <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this room?')">
                                <i class="fas fa-trash mr-1"></i>Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($rooms->isEmpty())
    <div class="text-center py-12">
        <i class="fas fa-bed text-6xl text-gray-400 mb-4"></i>
        <h3 class="text-2xl font-semibold text-gray-600">No rooms found</h3>
        <p class="text-gray-500 mb-4">Get started by adding your first room.</p>
        <a href="{{ route('admin.rooms.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-300">
            Add New Room
        </a>
    </div>
    @endif
</div>
@endsection