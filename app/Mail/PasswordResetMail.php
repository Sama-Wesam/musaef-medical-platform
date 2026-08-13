<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Route;

class PasswordResetMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $token;
    public $email;

    /**
     * Create a new message instance.
     */
    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'إعادة تعيين كلمة المرور - منصة مسعف',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // توليد الرابط مع فحص أمان لوجود الـ Route لتفادي RouteNotFoundException
        $resetUrl = Route::has('password.reset')
            ? route('password.reset', ['token' => $this->token, 'email' => $this->email])
            : url("/password/reset/{$this->token}?email=" . urlencode($this->email));

        return new Content(
            view: 'emails.password_reset',
            with: [
                'resetUrl' => $resetUrl,
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
