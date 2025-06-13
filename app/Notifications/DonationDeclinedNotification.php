<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Donation;

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
        $declineReason = $this->donation->verification_notes ?? $this->donation->treasurer_response ?? 'No reason provided';
        
        return (new MailMessage)
            ->subject('Donation Status Update')
            ->line('Your donation has been declined.')
            ->line("Amount: ₱" . number_format($this->donation->amount, 2))
            ->line("Reason: {$declineReason}")
            ->line('Please contact support if you have any questions.');
    }

    public function toArray($notifiable)
    {
        $declineReason = $this->donation->verification_notes ?? $this->donation->treasurer_response ?? 'No reason provided';
        
        return [
            'donation_id' => $this->donation->id,
            'amount' => $this->donation->amount,
            'message' => 'Your donation of ₱' . number_format($this->donation->amount, 2) . ' has been declined.',
            'description' => $declineReason,
            'url' => route('member.donations.show', $this->donation->id)
        ];
    }
}



