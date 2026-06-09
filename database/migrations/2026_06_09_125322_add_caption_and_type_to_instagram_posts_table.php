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
        Schema::table('instagram_posts', function (Blueprint $table) {
            $table->string('type')->default('image')->after('title');
            $table->text('caption')->nullable()->after('instagram_link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('instagram_posts', function (Blueprint $table) {
            $table->dropColumn(['type', 'caption']);
        });
    }
};
