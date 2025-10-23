<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationConfirmed extends Notification implements ShouldQueue
{
    use Queueable;

    public $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Reservation Confirmed - Air Hotel')
                    ->greeting('Hello ' . $notifiable->name . '!')
                    ->line('Your reservation has been confirmed.')
                    ->line('Reservation Details:')
                    ->line('Room: ' . $this->reservation->room->type . ' - Room ' . $this->reservation->room->room_number)
                    ->line('Check-in: ' . $this->reservation->check_in->format('F d, Y'))
                    ->line('Check-out: ' . $this->reservation->check_out->format('F d, Y'))
                    ->line('Total: Rp ' . number_format($this->reservation->total_price, 0, ',', '.'))
                    ->action('View Reservation', route('reservations.show', $this->reservation))
                    ->line('Thank you for choosing Air Hotel!');
    }

    public function toArray($notifiable)
    {
        return [
            'reservation_id' => $this->reservation->id,
            'message' => 'Your reservation has been confirmed.',
            'url' => route('reservations.show', $this->reservation)
        ];
    }
}