<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AuthCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $authCode;

    /**
     * Create a new message instance.
     */
    public function __construct(string $authCode)
    {
        $this->authCode = $authCode;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'SliverBird Verification Code',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.auth-code',
            with: [
                'authCode' => $this->authCode,
            ],
        );
    }

   
    public function attachments(): array
    {
        return [];
    }
}