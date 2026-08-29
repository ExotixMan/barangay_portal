<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Notification extends Mailable
{
    use Queueable, SerializesModels;

    public string $name;
    public string $messageText;

    public function __construct(string $name, string $messageText)
    {
        $this->name = $name;
        $this->messageText = $messageText;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Barangay Request Update',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'email.notification',
            with: [
                'name' => $this->name,
                'messageText' => $this->messageText,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
