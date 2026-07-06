<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FinanceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // Income
            ['name' => 'Gaji Bulanan', 'type' => 'income', 'icon' => '💵'],
            ['name' => 'Project/Freelance', 'type' => 'income', 'icon' => '💻'],
            ['name' => 'Bonus/Hadiah', 'type' => 'income', 'icon' => '🎁'],
            ['name' => 'Pendapatan Lain', 'type' => 'income', 'icon' => '💰'],
            
            // Expense
            ['name' => 'Makan & Minum', 'type' => 'expense', 'icon' => '🍔'],
            ['name' => 'Transportasi', 'type' => 'expense', 'icon' => '🚗'],
            ['name' => 'Kosan/Tempat Tinggal', 'type' => 'expense', 'icon' => '🏠'],
            ['name' => 'Tagihan & Internet', 'type' => 'expense', 'icon' => '📱'],
            ['name' => 'Belanja/Shopping', 'type' => 'expense', 'icon' => '🛍️'],
            ['name' => 'Hiburan/Entertain', 'type' => 'expense', 'icon' => '🍿'],
            ['name' => 'Kesehatan', 'type' => 'expense', 'icon' => '💊'],
            ['name' => 'Pengeluaran Lain', 'type' => 'expense', 'icon' => '💸'],
        ];

        foreach ($categories as $category) {
            \App\Models\FinanceCategory::create($category);
        }
    }
}
