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
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('role');
            $table->string('nidn')->nullable()->after('avatar');
            $table->string('phone')->nullable()->after('nidn');
            $table->text('address')->nullable()->after('phone');
            $table->text('bio')->nullable()->after('address');
            $table->json('education')->nullable()->after('bio');
            $table->json('expertise')->nullable()->after('education');
            $table->json('social_links')->nullable()->after('expertise');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar', 'nidn', 'phone', 'address', 'bio', 'education', 'expertise', 'social_links']);
        });
    }
};
