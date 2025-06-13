<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Donation;
use Illuminate\Support\Facades\Log;

class DonationStatusNotification extends Notification
{
    use Queueable;

    protected $donation;

    public function __construct(Donation $donation)
    {
        $this->donation = $donation;
        Log::info('DonationStatusNotification created for donation #' . $donation->id);
    }

    public function via($notifiable)
    {
        Log::info('Sending notification via database and mail to ' . $notifiable->email);
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $message = (new MailMessage)
            ->subject('Donation Status Update')
            ->greeting('Hello ' . $notifiable->name . ',');

        if ($this->donation->status == 'verified') {
            $message->line('Your donation of ₱' . number_format($this->donation->amount, 2) . ' has been verified.')
                ->line('Thank you for your generous contribution to our church community.');
            
            if (!empty($this->donation->verification_notes)) {
                $message->line('Treasurer\'s note: ' . $this->donation->verification_notes);
            }
        } else if ($this->donation->status == 'declined') {
            $message->line('Your donation of ₱' . number_format($this->donation->amount, 2) . ' has been declined.');
            
            if (!empty($this->donation->treasurer_response)) {
                $message->line('Reason: ' . $this->donation->treasurer_response);
            } elseif (!empty($this->donation->verification_notes)) {
                $message->line('Reason: ' . $this->donation->verification_notes);
            }
            
            $message->line('If you have any questions, please contact our church office.');
        }

        return $message->action('View Donation Details', url('/member/donations/' . $this->donation->id))
            ->line('Thank you for being part of our church family!');
    }

    public function toArray($notifiable)
    {
        $status = $this->donation->status;
        $message = $status == 'verified' 
            ? 'Your donation has been verified.' 
            : 'Your donation has been declined.';
            
        $description = '';
        
        // Add treasurer's message if available
        if ($status == 'verified' && !empty($this->donation->verification_notes)) {
            $description = 'Note: ' . $this->donation->verification_notes;
        } elseif ($status == 'declined') {
            if (!empty($this->donation->treasurer_response)) {
                $description = 'Reason: ' . $this->donation->treasurer_response;
            } elseif (!empty($this->donation->verification_notes)) {
                $description = 'Reason: ' . $this->donation->verification_notes;
            }
        }
        
        // Determine the correct URL based on user role
        $url = $notifiable->role == 3 
            ? route('member.donations.show', $this->donation->id)
            : route('treasurer.donations.show', $this->donation->id);
        
        return [
            'donation_id' => $this->donation->id,
            'amount' => $this->donation->amount,
            'status' => $status,
            'message' => $message,
            'description' => $description,
            'url' => $url
        ];
    }
} 






