<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('finance_wallets', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., Cash, BCA, GoPay
            $table->string('type')->default('cash'); // cash, bank, ewallet, credit
            $table->bigInteger('balance')->default(0); // Current balance in this wallet
            $table->string('icon')->nullable(); // e.g., 💳, 🏦, 💵
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_wallets');
    }
};
