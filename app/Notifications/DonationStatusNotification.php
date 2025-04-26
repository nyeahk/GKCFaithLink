<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Donation;

class DonationStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $donation;

    public function __construct(Donation $donation)
    {
        $this->donation = $donation;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $status = $this->donation->status === 'verified' ? 'verified' : 'declined';
        
        return (new MailMessage)
            ->subject("Your Donation Has Been {$status}")
            ->line("Your donation of {$this->donation->amount} has been {$status}.")
            ->line("Verification Notes: {$this->donation->verification_notes}")
            ->line("Verified by: {$this->donation->verified_by}")
            ->line("Verification Date: {$this->donation->verification_date}")
            ->action('View Donation', route('admin.donations.show', $this->donation->id))
            ->line('Thank you for your support!');
    }

    public function toArray($notifiable)
    {
        return [
            'donation_id' => $this->donation->id,
            'amount' => $this->donation->amount,
            'status' => $this->donation->status,
            'verification_notes' => $this->donation->verification_notes,
            'verified_by' => $this->donation->verified_by,
            'verification_date' => $this->donation->verification_date,
        ];
    }
} 