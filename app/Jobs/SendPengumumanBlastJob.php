<?php

namespace App\Jobs;

use App\Mail\PengumumanBlastMail;
use App\Models\Pengumuman;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendPengumumanBlastJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $pengumumanId;
    public int $tries = 3;
    public int $timeout = 1200; // 20 minutes max

    /**
     * Create a new job instance.
     */
    public function __construct(int $pengumumanId)
    {
        $this->pengumumanId = $pengumumanId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $pengumuman = Pengumuman::find($this->pengumumanId);

        if (!$pengumuman) {
            Log::warning("SendPengumumanBlastJob: Pengumuman ID {$this->pengumumanId} not found.");
            return;
        }

        // If already completed and not retrying, return
        if ($pengumuman->status === 'completed') {
            Log::info("SendPengumumanBlastJob: Pengumuman ID {$this->pengumumanId} already completed.");
            return;
        }

        // Set status to sending
        $pengumuman->update([
            'status' => 'sending',
        ]);

        $recipients = $pengumuman->resolveRecipients();
        $totalRecipients = count($recipients);

        if ($totalRecipients === 0) {
            $pengumuman->update([
                'status' => 'completed',
                'total_penerima' => 0,
                'total_berhasil' => 0,
                'total_gagal' => 0,
                'sent_at' => now(),
                'log_pengiriman' => [
                    [
                        'status' => 'info',
                        'message' => 'Tidak ditemukan target penerima email yang valid.',
                        'sent_at' => now()->format('Y-m-d H:i:s'),
                    ]
                ],
            ]);
            return;
        }

        $pengumuman->update([
            'total_penerima' => $totalRecipients,
        ]);

        $berhasil = 0;
        $gagal = 0;
        $logs = [];

        // Batch in groups of 15 to prevent Gmail SMTP threshold limits
        $batches = array_chunk($recipients, 15);
        $totalBatches = count($batches);

        foreach ($batches as $batchIndex => $batch) {
            foreach ($batch as $recipient) {
                $email = $recipient['email'];
                $name = $recipient['name'];

                try {
                    Mail::to($email)->send(new PengumumanBlastMail($pengumuman, $name, $email));
                    $berhasil++;
                    $logs[] = [
                        'email' => $email,
                        'name' => $name,
                        'status' => 'success',
                        'error' => null,
                        'sent_at' => now()->format('Y-m-d H:i:s'),
                    ];
                } catch (\Throwable $e) {
                    $gagal++;
                    Log::error("SendPengumumanBlastJob error sending to {$email}: " . $e->getMessage());
                    $logs[] = [
                        'email' => $email,
                        'name' => $name,
                        'status' => 'failed',
                        'error' => $e->getMessage(),
                        'sent_at' => now()->format('Y-m-d H:i:s'),
                    ];
                }
            }

            // Update progress in database after each batch
            $pengumuman->update([
                'total_berhasil' => $berhasil,
                'total_gagal' => $gagal,
                'log_pengiriman' => $logs,
            ]);

            // Delay 2 seconds between batches to throttle SMTP requests
            if ($batchIndex < $totalBatches - 1) {
                sleep(2);
            }
        }

        // Finalize status
        $finalStatus = ($berhasil > 0 || $totalRecipients === 0) ? 'completed' : 'failed';

        $pengumuman->update([
            'status' => $finalStatus,
            'total_berhasil' => $berhasil,
            'total_gagal' => $gagal,
            'sent_at' => now(),
            'log_pengiriman' => $logs,
        ]);
    }
}
