<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Pastikan kolom phone dan nim bertipe VARCHAR / STRING
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 50)->nullable()->change();
            $table->string('nim', 50)->nullable()->change();
        });

        // 2. Perbaiki khusus data Masita Binti Mustofa
        DB::table('users')
            ->where(function ($q) {
                $q->where('name', 'LIKE', '%Masita Binti Mustofa%')
                  ->orWhere('name', 'LIKE', '%Masita%Mustofa%');
            })
            ->update([
                'phone' => '601125185140'
            ]);

        // 3. Cari dan rapikan data mahasiswa lain yang nomor HP-nya mengandung format eksponen (E+)
        $usersWithSciPhone = DB::table('users')
            ->where(function ($q) {
                $q->where('phone', 'LIKE', '%E+%')
                  ->orWhere('phone', 'LIKE', '%e+%')
                  ->orWhere('phone', 'LIKE', '%E-%')
                  ->orWhere('phone', 'LIKE', '%e-%');
            })
            ->get();

        foreach ($usersWithSciPhone as $u) {
            $phone = trim((string)$u->phone);
            if (str_contains(strtoupper($u->name ?? ''), 'MASITA')) {
                $clean = '601125185140';
            } else {
                $clean = number_format((float)$phone, 0, '', '');
            }
            DB::table('users')->where('id', $u->id)->update(['phone' => $clean]);
        }

        // 4. Cari dan rapikan data mahasiswa yang NIM-nya mengandung format eksponen (E+)
        $usersWithSciNim = DB::table('users')
            ->where(function ($q) {
                $q->where('nim', 'LIKE', '%E+%')
                  ->orWhere('nim', 'LIKE', '%e+%')
                  ->orWhere('nim', 'LIKE', '%E-%')
                  ->orWhere('nim', 'LIKE', '%e-%');
            })
            ->get();

        foreach ($usersWithSciNim as $u) {
            $nim = trim((string)$u->nim);
            $clean = number_format((float)$nim, 0, '', '');
            DB::table('users')->where('id', $u->id)->update(['nim' => $clean]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak perlu revert karena ini data sanitization
    }
};
