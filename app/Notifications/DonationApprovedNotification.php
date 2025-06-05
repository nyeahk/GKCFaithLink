<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Donation;

class DonationApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $donation;

    public function __construct(Donation $donation)
    {
        $this->donation = $donation;
        $this->afterCommit(); // Ensure notification is sent after database transaction is committed
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $amount = number_format($this->donation->amount, 2);
        $notes = $this->donation->notes ?? 'No additional notes provided.';
        $verificationNotes = $this->donation->verification_notes ?? 'No verification notes provided.';
        
        return (new MailMessage)
            ->subject('Donation Approved')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your donation has been approved. Thank you for your contribution!')
            ->line("Amount: ₱{$amount}")
            ->line("Your Notes: {$notes}")
            ->line("Verification Notes: {$verificationNotes}")
            ->action('View Donation Details', route('member.donations.show', $this->donation->id))
            ->line('Thank you for your generosity and support for our church community.');
    }

    public function toArray($notifiable)
    {
        return [
            'donation_id' => $this->donation->id,
            'amount' => $this->donation->amount,
            'message' => 'Your donation of ₱' . number_format($this->donation->amount, 2) . ' has been approved!',
            'description' => $this->donation->verification_notes ?? 'No verification notes provided.',
            'notes' => $this->donation->notes ?? 'No additional notes provided.',
            'url' => route('member.donations.show', $this->donation->id)
        ];
    }
}


