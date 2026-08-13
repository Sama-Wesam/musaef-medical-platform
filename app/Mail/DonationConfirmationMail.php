<?php

namespace App\Mail;

use App\Models\Donation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class DonationConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $donation;

    /**
     * Create a new message instance.
     */
    public function __construct(Donation $donation)
    {
        // التحميل المسبق للعلاقات لتفادي مشكلة N+1 والأخطاء في الطوابير
        $donation->loadMissing(['donor.user', 'hospital.user']);
        $this->donation = $donation;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'تأكيد التبرع بالدم - شكراً لبطولتك في منصة مسعف!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $hospitalUser = $this->donation->hospital->facility_name
            ?? $this->donation->hospital->user->name
            ?? 'المستشفى';

        $donationDate = $this->donation->donation_date
            ? Carbon::parse($this->donation->donation_date)->format('Y-m-d')
            : now()->format('Y-m-d');

        return new Content(
            view: 'emails.donation_confirmation',
            with: [
                'donorName'    => $this->donation->donor->user->name ?? 'بطلنا',
                'hospitalName' => $hospitalUser,
                'units'        => $this->donation->units_donated ?? 1,
                'date'         => $donationDate,
                'pointsEarned' => $this->donation->points_earned ?? 50,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
