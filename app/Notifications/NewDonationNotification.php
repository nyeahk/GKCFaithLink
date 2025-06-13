<?php

namespace App\Notifications;

use App\Models\Donation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class NewDonationNotification extends Notification
{
    use Queueable;

    protected $donation;

    public function __construct(Donation $donation)
    {
        $this->donation = $donation;
        Log::info('NewDonationNotification created for donation #' . $donation->id);
    }

    public function via($notifiable)
    {
        // Log which channels we're using
        Log::info('Sending notification via: database, mail to ' . $notifiable->email);
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $donorName = $this->donation->user ? $this->donation->user->name : 'Anonymous';
        $amount = number_format($this->donation->amount, 2);
        $paymentMethod = ucfirst(str_replace('_', ' ', $this->donation->payment_method));
        $purpose = ucfirst($this->donation->purpose ?? 'General donation');
        $refNumber = $this->donation->reference_number ?: 'N/A';
        
        Log::info("Preparing email notification for treasurer about donation #{$this->donation->id}");
        
        return (new MailMessage)
            ->subject("New Donation of ₱{$amount} Received")
            ->greeting("Hello {$notifiable->name},")
            ->line("A new donation has been submitted and requires your review.")
            ->line("Amount: ₱{$amount}")
            ->line("Payment Method: {$paymentMethod}")
            ->line("Purpose: {$purpose}")
            ->line("Donor: {$donorName}")
            ->line("Reference Number: {$refNumber}")
            ->action('Review Donation', route('treasurer.donations.show', $this->donation->id))
            ->line('Please review this donation at your earliest convenience.')
            ->line('Thank you for your service!');
    }

    public function toArray($notifiable)
    {
        $donorName = $this->donation->user ? $this->donation->user->name : 'Anonymous';
        $amount = number_format($this->donation->amount, 2);
        
        return [
            'donation_id' => $this->donation->id,
            'amount' => $this->donation->amount,
            'message' => "New donation of ₱{$amount} received",
            'description' => "From: {$donorName} via " . ucfirst(str_replace('_', ' ', $this->donation->payment_method)),
            'url' => route('treasurer.donations.show', $this->donation->id)
        ];
    }
    
    /**
     * Handle a notification failure.
     *
     * @param  mixed  $notifiable
     * @param  \Exception  $exception
     * @return void
     */
    public function failed($notifiable, \Exception $exception)
    {
        Log::error("Notification failed for treasurer {$notifiable->id}: " . $exception->getMessage());
    }
} 







