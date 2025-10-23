<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentSuccess extends Notification implements ShouldQueue
{
    use Queueable;

    public $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Payment Successful - Air Hotel')
                    ->greeting('Hello ' . $notifiable->name . '!')
                    ->line('Your payment has been processed successfully.')
                    ->line('Payment Details:')
                    ->line('Amount: Rp ' . number_format($this->payment->amount, 0, ',', '.'))
                    ->line('Payment ID: ' . $this->payment->payment_id)
                    ->line('Reservation: ' . $this->payment->reservation->room->type . ' Room')
                    ->action('View Reservation', route('reservations.show', $this->payment->reservation))
                    ->line('We look forward to hosting you!');
    }

    public function toArray($notifiable)
    {
        return [
            'payment_id' => $this->payment->id,
            'message' => 'Payment successful for reservation #' . $this->payment->reservation->id,
            'amount' => $this->payment->amount,
            'url' => route('reservations.show', $this->payment->reservation)
        ];
    }
}