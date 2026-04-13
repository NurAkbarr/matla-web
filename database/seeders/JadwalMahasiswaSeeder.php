<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jadwal;
use App\Models\User;

class JadwalMahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jadwals = Jadwal::all();
        $mahasiswas = User::where('role', 'mahasiswa')->get();

        if ($jadwals->isEmpty() || $mahasiswas->isEmpty()) {
            return;
        }

        foreach ($jadwals as $jadwal) {
            // Pick 5-10 random students for each schedule
            $randomStudents = $mahasiswas->random(min(rand(5, 10), $mahasiswas->count()));
            
            foreach ($randomStudents as $mhs) {
                // Use syncWithoutDetaching to avoid unique constraint errors if re-run
                $jadwal->participants()->syncWithoutDetaching([$mhs->id]);
            }
        }
    }
}
