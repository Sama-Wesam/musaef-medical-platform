<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'مرحباً بك في منصة مسعف الطبية - معاً لتسهيل التبرع بالدم ونبضٍ ممتد',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $roleName = is_object($this->user->role) && method_exists($this->user->role, 'value')
            ? $this->user->role->value
            : (string) $this->user->role;

        $translatedRole = match(strtolower($roleName)) {
            'donor'    => 'متبرع/ة بطل',
            'hospital' => 'كادر طبي / مستشفى',
            'admin'    => 'مدير النظام',
            default    => 'عضو فعال',
        };

        return new Content(
            view: 'emails.welcome',
            with: [
                'userName' => $this->user->name ?? 'عضو منصة مسعف',
                'role'     => $translatedRole,
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
