<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\User;

class AutoFillQuestionnaireCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fill-questionnaire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto fill google form questionnaire using users in database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Auto-Fill Questionnaire...');

        $users = User::all();
        $total = $users->count();
        $successCount = 0;
        $errorCount = 0;

        $formUrl = 'https://docs.google.com/forms/d/e/1FAIpQLSfsoHqxeEzLYDV_eXTJaXFGAODj7aYA9u0aqcLiM9rwH2t30A/formResponse';

        $fiturMembantu = [
            'LMS sangat membantu perkuliahan online',
            'Sistem pendaftaran sangat mudah',
            'Fitur jadwal dan absensi',
            'UI nya bagus dan rapi',
            'Sangat user friendly',
            'Mudah diakses dari HP',
            'LMS dan manajemen tugas',
            'Navigasi yang jelas'
        ];

        $kendala = [
            'Tidak ada',
            'Aman-aman saja',
            'Terkadang loading sedikit lama saat koneksi jelek',
            'Belum ada kendala berarti',
            'Tidak ada kendala, sudah bagus',
            'Kadang bingung di awal tapi gampang dipelajari'
        ];

        $saran = [
            'Pertahankan kinerjanya',
            'Lebih ditingkatkan lagi kecepatannya',
            'Tolong tambahkan notifikasi WhatsApp',
            'Sudah sangat bagus',
            'UI nya mungkin bisa ditambah mode gelap (dark mode)',
            'Semoga servernya selalu stabil'
        ];

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        foreach ($users as $user) {
            // Map role
            $roleStr = ucfirst(strtolower($user->role ?? 'mahasiswa'));
            if (!in_array($roleStr, ['Admin', 'Dosen', 'Mahasiswa'])) {
                $roleStr = 'Mahasiswa';
            }

            // Payloads
            $payload = [
                'entry.1173135949' => $user->name,
                'entry.113244303' => $user->email,
                'entry.1376259667' => $roleStr,
                
                // Scale 1-5
                'entry.1786802780' => (string)rand(1, 5),
                'entry.688265404' => (string)rand(1, 5),
                'entry.589641822' => (string)rand(1, 5),
                'entry.711945778' => (string)rand(1, 5),
                'entry.1095513650' => (string)rand(1, 5),
                'entry.1144558180' => (string)rand(1, 5),
                'entry.1058748373' => (string)rand(1, 5),
                'entry.1028371416' => (string)rand(1, 5),
                'entry.358765560' => (string)rand(1, 5),
                'entry.738494701' => (string)rand(1, 5),

                // Short Answers
                'entry.1840559073' => $fiturMembantu[array_rand($fiturMembantu)],
                'entry.1891876594' => $kendala[array_rand($kendala)],
                'entry.950301174' => $saran[array_rand($saran)],
                
                // Multi-page form requirement
                'pageHistory' => '0,1,2',
            ];

            try {
                $response = Http::asForm()->post($formUrl, $payload);
                if ($response->successful()) {
                    $successCount++;
                } else {
                    $errorCount++;
                    $this->error('Failed: ' . $response->status());
                    $this->error('Body: ' . $response->body());
                }
            } catch (\Exception $e) {
                $errorCount++;
                $this->error('Exception: ' . $e->getMessage());
            }

            // Sleep a bit to avoid hitting rate limit
            usleep(500000); // 0.5 sec
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Completed! Success: {$successCount}, Errors: {$errorCount}");
    }
}
