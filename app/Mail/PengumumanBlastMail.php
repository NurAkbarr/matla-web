<?php

namespace App\Mail;

use App\Models\Pengumuman;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PengumumanBlastMail extends Mailable
{
    use Queueable, SerializesModels;

    public Pengumuman $pengumuman;
    public string $recipientName;
    public string $recipientEmail;

    /**
     * Create a new message instance.
     */
    public function __construct(Pengumuman $pengumuman, string $recipientName = '', string $recipientEmail = '')
    {
        $this->pengumuman = $pengumuman;
        $this->recipientName = $recipientName ?: 'Civitas Akademika';
        $this->recipientEmail = $recipientEmail;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $fromEmail = config('mail.from.address', 'islamicacademymatla@gmail.com');
        $senderName = trim((string) ($this->pengumuman->sender_name ?? 'MATLA University'));

        // Jika dipilih hanya alamat email atau kosong
        if ($senderName === '' || strtolower($senderName) === 'email_only') {
            $fromAddress = new Address($fromEmail);
        } else {
            $fromAddress = new Address($fromEmail, $senderName);
        }

        return new Envelope(
            from: $fromAddress,
            subject: '[MATLA INFO] ' . $this->pengumuman->judul,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.pengumuman_blast',
            with: [
                'pengumuman' => $this->pengumuman,
                'recipientName' => $this->recipientName,
                'recipientEmail' => $this->recipientEmail,
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

