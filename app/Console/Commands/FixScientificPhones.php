<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class FixScientificPhones extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:scientific-phones';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perbaiki nomor HP dan NIM mahasiswa yang tersimpan dalam format notasi ilmiah/eksponen (E+)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Memulai pengecekan dan perbaikan nomor HP & NIM...');

        // 1. Perbaiki data Masita Binti Mustofa
        $masita = User::where(function ($q) {
            $q->where('name', 'LIKE', '%Masita Binti Mustofa%')
              ->orWhere('name', 'LIKE', '%Masita%Mustofa%');
        })->first();

        if ($masita) {
            $masita->phone = '601125185140';
            $masita->saveQuietly();
            $this->info("✓ Berhasil memperbarui nomor HP Masita Binti Mustofa menjadi: 601125185140");
        } else {
            $this->warn("- Mahasiswa 'Masita Binti Mustofa' tidak ditemukan di database saat ini.");
        }

        // 2. Perbaiki nomor HP dengan format scientific (E+)
        $usersPhone = User::where(function ($q) {
            $q->where('phone', 'LIKE', '%E+%')
              ->orWhere('phone', 'LIKE', '%e+%')
              ->orWhere('phone', 'LIKE', '%E-%')
              ->orWhere('phone', 'LIKE', '%e-%');
        })->get();

        $countPhone = 0;
        foreach ($usersPhone as $u) {
            if ($masita && $u->id === $masita->id) continue;

            $oldPhone = $u->getRawOriginal('phone');
            $newPhone = User::sanitizeScientificNumber($oldPhone);
            $u->phone = $newPhone;
            $u->saveQuietly();
            $countPhone++;
            $this->line("  ✓ [Phone] {$u->name}: '{$oldPhone}' -> '{$newPhone}'");
        }

        // 3. Perbaiki NIM dengan format scientific (E+)
        $usersNim = User::where(function ($q) {
            $q->where('nim', 'LIKE', '%E+%')
              ->orWhere('nim', 'LIKE', '%e+%')
              ->orWhere('nim', 'LIKE', '%E-%')
              ->orWhere('nim', 'LIKE', '%e-%');
        })->get();

        $countNim = 0;
        foreach ($usersNim as $u) {
            $oldNim = $u->getRawOriginal('nim');
            $newNim = User::sanitizeScientificNumber($oldNim);
            $u->nim = $newNim;
            $u->saveQuietly();
            $countNim++;
            $this->line("  ✓ [NIM] {$u->name}: '{$oldNim}' -> '{$newNim}'");
        }

        $this->info("Selesai! {$countPhone} nomor HP dan {$countNim} NIM berhasil diperbaiki.");

        return Command::SUCCESS;
    }
}
