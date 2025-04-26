<?php

namespace App\Notifications;

use App\Models\Donation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DonationDeclinedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $donation;

    public function __construct(Donation $donation)
    {
        $this->donation = $donation;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Donation Status Update')
            ->line('Your donation has been declined.')
            ->line("Amount: {$this->donation->amount}")
            ->line("Reason: {$this->donation->admin_response}")
            ->line('Please contact support if you have any questions.');
    }

    public function toArray($notifiable)
    {
        return [
            'donation_id' => $this->donation->id,
            'amount' => $this->donation->amount,
            'message' => $this->donation->admin_response,
        ];
    }
} 