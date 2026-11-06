<?php

namespace App\Console\Commands;

use App\Jobs\SendPengumumanBlastJob;
use App\Models\Pengumuman;
use Illuminate\Console\Command;

class SendScheduledPengumumanCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pengumuman:send-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim email pengumuman yang dijadwalkan dan telah melewati waktu scheduled_at';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $now = now();
        $this->info("[{$now->toDateTimeString()}] Memeriksa email pengumuman yang siap dikirim...");

        $pengumumans = Pengumuman::query()
            ->where('status', 'scheduled')
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '<=', $now)
            ->get();

        if ($pengumumans->isEmpty()) {
            $this->info("Tidak ada pengumuman terjadwal yang harus dikirim saat ini.");
            return Command::SUCCESS;
        }

        $this->info("Ditemukan {$pengumumans->count()} pengumuman terjadwal. Memulai dispatch job...");

        foreach ($pengumumans as $pengumuman) {
            $this->line("-> Memproses pengumuman ID #{$pengumuman->id}: '{$pengumuman->judul}'...");

            // Tandai status sending agar tidak terambil kembali pada iterasi menit berikutnya
            $pengumuman->update(['status' => 'sending']);

            SendPengumumanBlastJob::dispatch($pengumuman->id);
            $this->info("   [OK] Job berhasil di-dispatch ke queue.");
        }

        $this->info("Pemeriksaan pengumuman terjadwal selesai.");
        return Command::SUCCESS;
    }
}
