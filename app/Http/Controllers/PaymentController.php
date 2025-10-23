<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;
use App\Notifications\PaymentSuccess;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function createPayment(Reservation $reservation)
    {
        // Check if reservation belongs to user
        if ($reservation->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if payment already exists
        if ($reservation->payment) {
            return redirect()->route('payment.show', $reservation->payment);
        }

        // Create payment
        $payment = $this->initializePayment($reservation);

        return redirect()->route('payment.show', $payment);
    }

    private function initializePayment(Reservation $reservation)
    {
        $transactionDetails = [
            'order_id' => 'AIRHOTEL-' . $reservation->id . '-' . time(),
            'gross_amount' => $reservation->total_price,
        ];

        $customerDetails = [
            'first_name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'phone' => Auth::user()->phone,
        ];

        $transactionData = [
            'transaction_details' => $transactionDetails,
            'customer_details' => $customerDetails,
            'expiry' => [
                'start_time' => now()->format('Y-m-d H:i:s O'),
                'unit' => 'hours',
                'duration' => 24
            ]
        ];

        $snapToken = Snap::getSnapToken($transactionData);

        $payment = Payment::create([
            'reservation_id' => $reservation->id,
            'payment_id' => $transactionDetails['order_id'],
            'amount' => $reservation->total_price,
            'snap_token' => $snapToken,
            'expired_at' => now()->addHours(24),
        ]);

        return $payment;
    }

    public function show(Payment $payment)
    {
        // Check if payment belongs to user
        if ($payment->reservation->user_id !== Auth::id()) {
            abort(403);
        }

        return view('payments.show', compact('payment'));
    }

    public function handleNotification(Request $request)
    {
        $notif = new \Midtrans\Notification();

        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $orderId = $notif->order_id;
        $fraud = $notif->fraud_status;

        $payment = Payment::where('payment_id', $orderId)->first();

        if (!$payment) {
            return response()->json(['status' => 'error', 'message' => 'Payment not found'], 404);
        }

        if ($transaction == 'capture') {
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    $payment->update(['status' => 'pending']);
                } else {
                    $payment->update([
                        'status' => 'success',
                        'payment_method' => 'credit_card',
                        'payment_data' => $notif
                    ]);
                    
                    // Update reservation status
                    $payment->reservation->update(['status' => 'confirmed']);
                }
            }
        } elseif ($transaction == 'settlement') {
            $payment->update([
                'status' => 'success',
                'payment_method' => $type,
                'payment_data' => $notif
            ]);
            
            $payment->reservation->update(['status' => 'confirmed']);
        } elseif ($transaction == 'pending') {
            $payment->update(['status' => 'pending']);
        } elseif ($transaction == 'deny') {
            $payment->update(['status' => 'failed']);
        } elseif ($transaction == 'expire') {
            $payment->update(['status' => 'expired']);
        } elseif ($transaction == 'cancel') {
            $payment->update(['status' => 'failed']);
        }

        return response()->json(['status' => 'success']);
    }

    public function success(Payment $payment)
    {
        if ($payment->reservation->user_id !== Auth::id()) {
            abort(403);
        }

        return view('payments.success', compact('payment'));

        $payment->reservation->user->notify(new PaymentSuccess($payment));
    }

    public function failed(Payment $payment)
    {
        if ($payment->reservation->user_id !== Auth::id()) {
            abort(403);
        }

        return view('payments.failed', compact('payment'));
    }
}