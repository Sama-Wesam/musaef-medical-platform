<?php

namespace App\Mail;

use App\Models\BloodRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmergencyMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $bloodRequest;
    public $donorUser;

    /**
     * Create a new message instance.
     */
    public function __construct(BloodRequest $bloodRequest, User $donorUser)
    {
        $this->bloodRequest = $bloodRequest;
        $this->donorUser = $donorUser; // لمعرفة اسم المتبرع المرسل إليه
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🚨 نداء طوارئ عاجل من مستشفى: ' . ($this->bloodRequest->hospital->user->name ?? 'مسعف'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.emergency', // يجب إنشاء resources/views/emails/emergency.blade.php
            with: [
                'donorName' => $this->donorUser->name,
                'bloodType' => $this->bloodRequest->bloodType->name ?? 'غير محدد',
                'hospitalName' => $this->bloodRequest->hospital->user->name ?? '',
                'units' => $this->bloodRequest->units_required,
                'level' => $this->bloodRequest->emergency_level,
                'url' => url('/emergency/' . $this->bloodRequest->id),
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