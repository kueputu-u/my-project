@extends('layouts.app')

@section('title', 'Payment - Air Hotel')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold mb-8">Complete Your Payment</h1>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-xl font-semibold">Reservation Details</h2>
                    <p class="text-gray-600">{{ $payment->reservation->room->type }} Room - Room {{ $payment->reservation->room->room_number }}</p>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                    <p class="text-sm text-gray-500">Due: {{ $payment->expired_at->format('M d, Y H:i') }}</p>
                </div>
            </div>

            <!-- Payment Instructions -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <h3 class="font-semibold text-yellow-800 mb-2">Payment Instructions</h3>
                <p class="text-yellow-700 text-sm">
                    Complete your payment within 24 hours. Your reservation will be automatically cancelled if payment is not completed.
                </p>
            </div>

            <!-- Midtrans Payment Button -->
            <div class="text-center">
                <button id="pay-button" class="bg-green-600 text-white px-8 py-4 rounded-lg hover:bg-green-700 transition duration-300 text-lg font-semibold">
                    <i class="fas fa-credit-card mr-2"></i>Pay Now
                </button>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('reservations.index') }}" class="text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i>Back to Reservations
            </a>
        </div>
    </div>
</div>

<!-- Midtrans Snap JS -->
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" 
        data-client-key="{{ config('midtrans.client_key') }}"></script>
<script type="text/javascript">
    document.getElementById('pay-button').onclick = function(){
        snap.pay('{{ $payment->snap_token }}', {
            onSuccess: function(result){
                window.location.href = '{{ route('payment.success', $payment) }}';
            },
            onPending: function(result){
                window.location.href = '{{ route('payment.success', $payment) }}';
            },
            onError: function(result){
                window.location.href = '{{ route('payment.failed', $payment) }}';
            },
            onClose: function(){
                // User closed the payment popup
            }
        });
    };
</script>
@endsection