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
        Schema::table('finance_transactions', function (Blueprint $table) {
            // Add wallet_id and allow null temporarily if there are existing transactions
            $table->foreignId('finance_wallet_id')->nullable()->constrained('finance_wallets')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('finance_transactions', function (Blueprint $table) {
            $table->dropForeign(['finance_wallet_id']);
            $table->dropColumn('finance_wallet_id');
        });
    }
};
