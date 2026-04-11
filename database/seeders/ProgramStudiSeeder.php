<?php

namespace Database\Seeders;

use App\Models\ProgramStudi;
use Illuminate\Database\Seeder;

class ProgramStudiSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama' => 'Pendidikan Agama Islam',       'singkatan' => 'PAI', 'jenjang' => 'S1', 'icon' => '📖', 'akreditasi' => 'Unggul',     'deskripsi' => 'Program studi yang mencetak pendidik Islam profesional, berkarakter Qur\'ani, dan mampu mengintegrasikan ilmu agama dengan ilmu modern.',                    'is_active' => true, 'urutan' => 1],
            ['nama' => 'Hukum Keluarga Islam',          'singkatan' => 'HKI', 'jenjang' => 'S1', 'icon' => '⚖️', 'akreditasi' => 'Baik Sekali', 'deskripsi' => 'Mempelajari hukum perdata Islam meliputi perkawinan, kewarisan, dan wakaf sesuai syariat dan perundangan nasional.',                                             'is_active' => true, 'urutan' => 2],
            ['nama' => 'Ekonomi Syariah',               'singkatan' => 'ES',  'jenjang' => 'S1', 'icon' => '💰', 'akreditasi' => 'Baik Sekali', 'deskripsi' => 'Mengkaji sistem ekonomi berbasis prinsip-prinsip Islam, meliputi perbankan syariah, pasar modal, dan kewirausahaan Islami.',                                  'is_active' => true, 'urutan' => 3],
            ['nama' => 'Komunikasi & Penyiaran Islam',  'singkatan' => 'KPI', 'jenjang' => 'S1', 'icon' => '📡', 'akreditasi' => 'Baik',        'deskripsi' => 'Program studi yang menyiapkan praktisi media dan dakwah yang mampu menyampaikan pesan Islam secara efektif di era digital.',                             'is_active' => true, 'urutan' => 4],
            ['nama' => 'Manajemen Pendidikan Islam',    'singkatan' => 'MPI', 'jenjang' => 'S2', 'icon' => '🏫', 'akreditasi' => 'Baik',        'deskripsi' => 'Jenjang magister yang mengkaji pengelolaan lembaga pendidikan Islam secara profesional, efektif, dan berbasis nilai Islami.',                            'is_active' => true, 'urutan' => 5],
            ['nama' => 'Ilmu Al-Quran dan Tafsir',      'singkatan' => 'IAT', 'jenjang' => 'S1', 'icon' => '🕌', 'akreditasi' => 'Unggul',     'deskripsi' => 'Mendalami ilmu Al-Quran, metode tafsir klasik dan kontemporer, serta penerapannya dalam kehidupan modern.',                                              'is_active' => true, 'urutan' => 6],
        ];

        foreach ($data as $d) {
            ProgramStudi::firstOrCreate(['singkatan' => $d['singkatan'], 'jenjang' => $d['jenjang']], $d);
        }
    }
}
