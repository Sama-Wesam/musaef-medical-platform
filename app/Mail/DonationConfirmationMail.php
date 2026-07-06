<?php

namespace App\Mail;

use App\Models\Donation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DonationConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $donation;

    /**
     * Create a new message instance.
     */
    public function __construct(Donation $donation)
    {
        $this->donation = $donation;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'تأكيد التبرع بالدم - شكراً لبطولتك!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.donation_confirmation', // يجب إنشاء resources/views/emails/donation_confirmation.blade.php
            with: [
                'donorName' => $this->donation->donor->user->name ?? 'بطلنا',
                'hospitalName' => $this->donation->hospital->user->name ?? 'المستشفى',
                'units' => $this->donation->units_donated,
                'date' => $this->donation->donation_date->format('Y-m-d'),
                'pointsEarned' => $this->donation->points_earned,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        // يمكنك لاحقاً إرفاق شهادة تقدير PDF هنا إذا أردت إضافة ميزة قوية
        return [];
    }
}