<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BulkUserAnnouncementMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $username,
        public string $mailSubject,
        public string $mailMessage,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->mailSubject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.bulk-announcement',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
