@extends('layouts.app')

@section('title', 'Payment Failed - Air Hotel')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto text-center">
        <div class="bg-white rounded-lg shadow-md p-8">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-times text-red-600 text-2xl"></i>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Payment Failed</h1>
            <p class="text-gray-600 mb-6">
                We couldn't process your payment. Please try again or use a different payment method.
            </p>

            <div class="space-y-3">
                <a href="{{ route('payment.show', $payment) }}" 
                   class="block bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition duration-300">
                    Try Payment Again
                </a>
                <a href="{{ route('reservations.index') }}" 
                   class="block border border-gray-300 text-gray-700 py-3 rounded-lg hover:bg-gray-50 transition duration-300">
                    Back to Reservations
                </a>
            </div>
        </div>
    </div>
</div>
@endsection