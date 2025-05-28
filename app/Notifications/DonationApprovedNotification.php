<?php

namespace App\Notifications;

use App\Models\Donation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DonationApprovedNotification extends Notification implements ShouldQueue
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
            ->subject('Donation Approved')
            ->line('Your donation has been approved!')
            ->line("Amount: {$this->donation->amount}")
            ->line("Message: {$this->donation->admin_response}")
            ->line('Thank you for your generosity!');
    }

    public function toArray($notifiable)
    {
        return [
            'donation_id' => $this->donation->id,
            'amount' => $this->donation->amount,
            'message' => 'Your donation of ' . $this->donation->amount . ' has been approved!',
            'description' => $this->donation->admin_response,
            'url' => route('member.donations.show', $this->donation->id)
        ];
    }
} 


