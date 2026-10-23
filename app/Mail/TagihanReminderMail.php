<?php

namespace App\Mail;

use App\Models\Tagihan;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TagihanReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public Tagihan $tagihan;
    public User $user;

    /**
     * Create a new message instance.
     */
    public function __construct(Tagihan $tagihan, User $user)
    {
        $this->tagihan = $tagihan;
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                config('mail.from.address', 'islamicacademymatla@gmail.com'),
                'Keuangan MATLA University'
            ),
            subject: 'Pemberitahuan Tagihan Baru: ' . $this->tagihan->nama_tagihan . ' - MATLA University Portal',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.tagihan-reminder',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
