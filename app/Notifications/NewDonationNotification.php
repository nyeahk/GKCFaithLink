<?php

namespace App\Notifications;

use App\Models\Donation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewDonationNotification extends Notification implements ShouldQueue
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
            ->subject('New Donation Received')
            ->line('A new donation has been submitted and requires your approval.')
            ->line("Amount: {$this->donation->amount}")
            ->line("Payment Method: {$this->donation->payment_method}")
            ->line("Donor: {$this->donation->user->name}")
            ->action('Review Donation', route('admin.donations.show', $this->donation))
            ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'donation_id' => $this->donation->id,
            'amount' => $this->donation->amount,
            'donor_name' => $this->donation->user->name,
            'message' => 'A new donation requires your approval.',
        ];
    }
} 