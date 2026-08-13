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
        // Eager Loading للعلاقات الهامة
        $bloodRequest->loadMissing(['hospital.user', 'bloodType']);

        $this->bloodRequest = $bloodRequest;
        $this->donorUser = $donorUser;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $hospitalName = $this->bloodRequest->hospital->facility_name
            ?? $this->bloodRequest->hospital->user->name
            ?? 'مسعف';

        return new Envelope(
            subject: '🚨 نداء طوارئ عاجل من: ' . $hospitalName,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $hospitalName = $this->bloodRequest->hospital->facility_name
            ?? $this->bloodRequest->hospital->user->name
            ?? 'المستشفى';

        $lat = $this->bloodRequest->hospital->latitude ?? $this->bloodRequest->latitude;
        $lng = $this->bloodRequest->hospital->longitude ?? $this->bloodRequest->longitude;

        // رابط الخريطة والملاحة المباشر نحو المستشفى (Deep Linking)
        $googleMapsUrl = ($lat && $lng)
            ? "https://www.google.com/maps/dir/?api=1&destination={$lat},{$lng}"
            : url('/emergency/' . $this->bloodRequest->id);

        $emergencyLevelRaw = is_object($this->bloodRequest->emergency_level) && method_exists($this->bloodRequest->emergency_level, 'value')
            ? $this->bloodRequest->emergency_level->value
            : (string) $this->bloodRequest->emergency_level;

        $levelText = match(strtolower($emergencyLevelRaw)) {
            'critical', 'حرج' => 'حرج جداً (طارئ للغاية)',
            'high', 'عالي'     => 'عالي الخطورة',
            default           => 'متوسط / عادي',
        };

        return new Content(
            view: 'emails.emergency',
            with: [
                'donorName'     => $this->donorUser->name ?? 'أخي/أختي المتبرع/ة',
                'bloodType'     => $this->bloodRequest->bloodType->name ?? $this->bloodRequest->blood_type ?? 'غير محدد',
                'hospitalName'  => $hospitalName,
                'units'         => $this->bloodRequest->units_required ?? $this->bloodRequest->units_needed ?? 1,
                'level'         => $levelText,
                'url'           => url('/emergency/' . $this->bloodRequest->id),
                'googleMapsUrl' => $googleMapsUrl,
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
