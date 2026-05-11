<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuickInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['label' => 'Kurikulum', 'link' => '#'],
            ['label' => 'Kaldik', 'link' => '#'],
            ['label' => 'Channel WA', 'link' => '#'],
            ['label' => 'Tata Tertib', 'link' => '#'],
            ['label' => 'Panduan LMS', 'link' => '#'],
            ['label' => 'FAQ', 'link' => '#'],
            ['label' => 'Link Instagram', 'link' => '#'],
            ['label' => 'Form Pengajuan', 'link' => '#'],
            ['label' => 'Jadwal UTS/UAS', 'link' => '#'],
            ['label' => 'Sistem Bayar', 'link' => '#'],
            ['label' => 'Rek Kampus', 'link' => '#'],
        ];

        foreach ($items as $index => $item) {
            \App\Models\QuickInfo::create([
                'label' => $item['label'],
                'link' => $item['link'],
                'order' => $index,
                'is_active' => true
            ]);
        }
    }
}
