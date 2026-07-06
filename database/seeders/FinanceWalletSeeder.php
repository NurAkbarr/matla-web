<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FinanceWalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wallets = [
            ['name' => 'Dompet Utama (Cash)', 'type' => 'cash', 'balance' => 0, 'icon' => '💵'],
            ['name' => 'Rekening BCA', 'type' => 'bank', 'balance' => 0, 'icon' => '🏦'],
            ['name' => 'GoPay', 'type' => 'ewallet', 'balance' => 0, 'icon' => '📱'],
            ['name' => 'OVO', 'type' => 'ewallet', 'balance' => 0, 'icon' => '📱'],
        ];

        foreach ($wallets as $wallet) {
            \App\Models\FinanceWallet::create($wallet);
        }
    }
}
