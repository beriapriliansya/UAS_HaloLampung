<?php

namespace App\Mail;

use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Subscriber $subscriber;

    public function __construct(Subscriber $subscriber)
    {
        $this->subscriber = $subscriber;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Konfirmasi Langganan Newsletter ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.newsletter.confirmation',
            with: [
                'confirmationUrl' => route('newsletter.confirm', $this->subscriber->token),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}