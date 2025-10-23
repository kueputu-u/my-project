@extends('layouts.app')

@section('title', 'Payment Successful - Air Hotel')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto text-center">
        <div class="bg-white rounded-lg shadow-md p-8">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check text-green-600 text-2xl"></i>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Payment Successful!</h1>
            <p class="text-gray-600 mb-6">
                Thank you for your payment. Your reservation has been confirmed.
            </p>

            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <p class="text-sm text-gray-600">Reservation ID: {{ $payment->reservation->id }}</p>
                <p class="text-sm text-gray-600">Payment ID: {{ $payment->payment_id }}</p>
                <p class="text-lg font-semibold text-green-600 mt-2">
                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                </p>
            </div>

            <div class="space-y-3">
                <a href="{{ route('reservations.show', $payment->reservation) }}" 
                   class="block bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition duration-300">
                    View Reservation Details
                </a>
                <a href="{{ route('home') }}" 
                   class="block border border-gray-300 text-gray-700 py-3 rounded-lg hover:bg-gray-50 transition duration-300">
                    Back to Home
                </a>
            </div>
        </div>
    </div>
</div>
@endsection