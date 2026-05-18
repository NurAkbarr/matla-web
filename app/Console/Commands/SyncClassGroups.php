<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ClassGroup;
use App\Models\User; // Mahasiswa model (role mahasiswa)
use App\Models\ProgramStudi;

class SyncClassGroups extends Command
{
    protected $signature = 'classgroup:sync';
    protected $description = 'Sinkronisasi tabel class_groups berdasarkan kombinasi prodi dan angkatan mahasiswa';

    public function handle(): int
    {
        // Ambil semua kombinasi unik prodi (nama) dan angkatan dari mahasiswa
        $distinct = User::where('role', 'mahasiswa')
            ->whereNotNull('angkatan')
            ->where('angkatan', '!=', '')
            ->select('education->program_studi as prodi_name', 'angkatan')
            ->distinct()
            ->get();

        $syncedCount = 0;

        foreach ($distinct as $row) {
            $prodiName = $row->prodi_name;
            $angkatan = $row->angkatan;

            if (empty($prodiName)) {
                continue;
            }

            // Dapatkan program studi berdasarkan nama
            $prodi = ProgramStudi::where('nama', $prodiName)->first();
            if (!$prodi) {
                // fallback to active prodi matching singkatan if any
                $prodi = ProgramStudi::where('singkatan', $prodiName)->first();
            }

            if ($prodi) {
                $groupName = "{$prodi->nama} – Angkatan {$angkatan}";

                ClassGroup::updateOrCreate(
                    ['prodi_id' => $prodi->id, 'angkatan' => $angkatan],
                    ['name' => $groupName]
                );
                $syncedCount++;
            }
        }

        $this->info("Sinkronisasi class groups selesai. Berhasil mensinkronkan {$syncedCount} kelompok kelas.");
        return self::SUCCESS;
    }
}
