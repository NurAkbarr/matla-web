<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class NewRegistrationNotification extends Notification
{
    use Queueable;

    protected $registration;

    public function __construct($registration)
    {
        $this->registration = $registration;
    }

    public function via($notifiable)
    {
        return ['telegram']; // We will handle 'mail' later if needed, starting with Telegram
    }

    public function toTelegram($notifiable)
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        $chatId = env('TELEGRAM_CHAT_ID');

        if (!$token || !$chatId) {
            return;
        }

        $message = "🔔 *Pendaftar Baru!*\n\n";
        $message .= "🆔 *No. Reg:* `{$this->registration->registration_code}`\n";
        $message .= "👤 *Nama:* {$this->registration->full_name}\n";
        $message .= "📚 *Prodi:* {$this->registration->study_program}\n";
        $message .= "📱 *WA:* [{$this->registration->whatsapp_number}](https://wa.me/{$this->registration->whatsapp_number})\n\n";
        $message .= "🔗 [Lihat Detail di Dashboard](" . route('backend.admin.pmb.registrations.show', $this->registration->id) . ")";

        return Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'Markdown',
        ]);
    }
}
