<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\InformasiController;
use App\Http\Controllers\PmbController;
use App\Http\Controllers\KontakController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\ProgramStudiController;



Route::get('/', function () {
    $brosurs = \App\Models\BrosurPmb::orderBy('order')->get();
    return view('welcome', compact('brosurs'));
});

// Authentication
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');



Route::get('/pmb', [PmbController::class, 'index'])->name('pmb');
Route::get('/pmb/register', [\App\Http\Controllers\PmbRegistrationController::class, 'create'])->name('pmb.register');
Route::post('/pmb/register', [\App\Http\Controllers\PmbRegistrationController::class, 'store'])->name('pmb.register.store')->middleware('throttle:registration');
Route::get('/pmb/success/{registration_code}', [\App\Http\Controllers\PmbRegistrationController::class, 'success'])->name('pmb.success');
Route::get('/pmb/status', [\App\Http\Controllers\PmbStatusController::class, 'index'])->name('pmb.status');
Route::post('/pmb/status', [\App\Http\Controllers\PmbStatusController::class, 'check'])->name('pmb.status.check');
Route::get('/pmb/loa/{registration_code}', [\App\Http\Controllers\PmbStatusController::class, 'printLoa'])->name('pmb.loa');
Route::get('/kontak', [KontakController::class, 'index'])->name('kontak');
Route::post('/kontak', [KontakController::class, 'store'])->name('kontak.store')->middleware('throttle:contact');

// ===== KTM Public Route (QR Scan) =====
Route::get('/p/{qr_token}', function ($qr_token) {
    $user = \App\Models\User::where('qr_token', $qr_token)
        ->where('role', 'mahasiswa')
        ->with(['profil', 'skills', 'portofolio'])
        ->firstOrFail();
    return view('mahasiswa.ktm.public', compact('user'));
})->middleware('throttle:60,1')->name('ktm.public');

/*
|--------------------------------------------------------------------------
| Maintenance endpoints (locked down)
|--------------------------------------------------------------------------
|
| Untuk production tanpa SSH (cPanel), endpoint ini boleh ada tapi HARUS:
| - POST only
| - auth + super_admin
| - token rahasia dari .env (MAINTENANCE_TOKEN)
| - HTTPS in production
|
*/
Route::middleware(['auth', 'role:super_admin', 'maintenance.gate'])->prefix('_maintenance')->group(function () {
    Route::post('/migrate', function () {
        Artisan::call('migrate', ['--force' => true]);
        return back()->with('success', 'Migration berhasil dijalankan.');
    })->middleware('throttle:1,10')->name('maintenance.migrate');

    Route::post('/clear-cache', function () {
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        return back()->with('success', 'Cache berhasil dibersihkan.');
    })->middleware('throttle:2,10')->name('maintenance.clear_cache');

    Route::post('/fix-qr-tokens', function () {
        $users = \App\Models\User::where('role', 'mahasiswa')->get();
        $count = 0;
        foreach ($users as $user) {
            if (empty($user->qr_token) || strlen($user->qr_token) < 30) {
                $user->qr_token = (string) \Illuminate\Support\Str::uuid();
                $user->save();
                $count++;
            }
        }
        return back()->with('success', "Berhasil men-generate QR Token untuk $count mahasiswa lama!");
    })->middleware('throttle:1,10')->name('maintenance.fix_qr_tokens');

    Route::post('/clear-server-cache', function () {
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        return back()->with('success', 'Server cache (view/cache/config) berhasil dibersihkan.');
    })->middleware('throttle:2,10')->name('maintenance.clear_server_cache');
});

// ===== FALLBACK ROUTE: Jika Symlink Gagal, Laravel yang akan mengirimkan gambarnya =====
Route::get('/storage/{folder}/{filename}', function ($folder, $filename) {
    $baseDir = storage_path('app/public');
    $baseReal = realpath($baseDir);
    if ($baseReal === false) {
        abort(404);
    }

    $candidate = $baseDir . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . $filename;
    $path = realpath($candidate);

    // Block directory traversal / symlink escapes
    if ($path === false || !str_starts_with($path, $baseReal . DIRECTORY_SEPARATOR)) {
        abort(404);
    }

    if (!is_file($path)) {
        abort(404);
    }

    return response()->file($path);
})->where('filename', '.*');

// Deprecated (keamanan): jangan expose endpoint maintenance via GET/public.

// ===== Mahasiswa Area =====
Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/ktm', [\App\Http\Controllers\Mahasiswa\KtmController::class, 'show'])->name('ktm');
    Route::get('/profil', [\App\Http\Controllers\Mahasiswa\ProfilController::class, 'edit'])->name('profil');
    Route::post('/profil', [\App\Http\Controllers\Mahasiswa\ProfilController::class, 'update'])->name('profil.update');
    Route::post('/skill', [\App\Http\Controllers\Mahasiswa\SkillController::class, 'store'])->name('skill.store');
    Route::delete('/skill/{id}', [\App\Http\Controllers\Mahasiswa\SkillController::class, 'destroy'])->name('skill.destroy');
    Route::post('/portofolio', [\App\Http\Controllers\Mahasiswa\PortofolioController::class, 'store'])->name('portofolio.store');
    Route::delete('/portofolio/{id}', [\App\Http\Controllers\Mahasiswa\PortofolioController::class, 'destroy'])->name('portofolio.destroy');
});

// Backend Routes
Route::prefix('backend')->name('backend.')->middleware('auth')->group(function () {
    
    // Admin & Super Admin Area
    Route::middleware(['role:super_admin,admin'])->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');

        Route::get('/admin/maintenance', function () {
            return view('backend.admin.maintenance');
        })->middleware('throttle:30,1')->name('admin.maintenance');
        
        // User Management
        Route::get('/admin/users', [\App\Http\Controllers\Backend\UserController::class, 'index'])->name('admin.users.index');
        Route::get('/admin/users/create', [\App\Http\Controllers\Backend\UserController::class, 'create'])->name('admin.users.create');
        Route::post('/admin/users', [\App\Http\Controllers\Backend\UserController::class, 'store'])->name('admin.users.store');
        Route::post('/admin/users/bulk-delete', [\App\Http\Controllers\Backend\UserController::class, 'bulkDelete'])->name('admin.users.bulk_delete');
        Route::get('/admin/users/{user}', [\App\Http\Controllers\Backend\UserController::class, 'show'])->name('admin.users.show');
        
        // Admin KTM View Route
        Route::get('/admin/users/{user}/ktm', function (\App\Models\User $user) {
            if ($user->role !== 'mahasiswa') abort(404);
            $user->load(['profil', 'skills', 'portofolio']);
            return view('mahasiswa.ktm.show', compact('user'));
        })->name('admin.users.ktm');

        Route::get('/admin/users/{user}/edit', [\App\Http\Controllers\Backend\UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/admin/users/{user}', [\App\Http\Controllers\Backend\UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/admin/users/{user}', [\App\Http\Controllers\Backend\UserController::class, 'destroy'])->name('admin.users.destroy');

        // Kotak Pesan (Inbox)
        Route::get('/admin/messages', [\App\Http\Controllers\Backend\ContactMessageController::class, 'index'])->name('admin.messages.index');
        Route::patch('/admin/messages/{message}/read', [\App\Http\Controllers\Backend\ContactMessageController::class, 'markAsRead'])->name('admin.messages.read');
        Route::delete('/admin/messages/{message}', [\App\Http\Controllers\Backend\ContactMessageController::class, 'destroy'])->name('admin.messages.destroy');

        // Akademik Management
        Route::get('/admin/mahasiswa', [DashboardController::class, 'mahasiswaAktif'])->name('admin.mahasiswa');
        Route::get('/admin/dosen', [DashboardController::class, 'dataDosen'])->name('admin.dosen');
        Route::resource('/admin/jadwal', \App\Http\Controllers\Backend\JadwalController::class, [
            'names' => 'admin.jadwal',
        ]);
        Route::get('/admin/jadwal/{jadwal}/participants', [\App\Http\Controllers\Backend\JadwalController::class, 'participants'])->name('admin.jadwal.participants');
        Route::post('/admin/jadwal/{jadwal}/participants', [\App\Http\Controllers\Backend\JadwalController::class, 'addParticipant'])->name('admin.jadwal.participants.add');
        Route::delete('/admin/jadwal/{jadwal}/participants/{user}', [\App\Http\Controllers\Backend\JadwalController::class, 'removeParticipant'])->name('admin.jadwal.participants.remove');

        // Nilai Management (Admin)
        Route::post('/admin/jadwal/{jadwal}/unlock', [\App\Http\Controllers\Backend\NilaiController::class, 'unlock'])->name('admin.jadwal.unlock');

        // Rekap Honor Dosen
        Route::get('/admin/rekap-honor', [\App\Http\Controllers\Backend\PresensiDosenController::class, 'adminIndex'])->name('admin.rekap-honor.index');
        Route::get('/admin/rekap-honor/export', [\App\Http\Controllers\Backend\PresensiDosenController::class, 'exportExcel'])->name('admin.rekap-honor.export');

        // Program Studi Management
        Route::resource('/admin/program-studi', ProgramStudiController::class, [
            'names' => 'admin.program-studi',
        ]);

        // Mahasiswa Import & Export
        Route::get('/admin/mahasiswa/export/excel', [DashboardController::class, 'exportExcel'])->name('admin.mahasiswa.export.excel');
        Route::get('/admin/mahasiswa/export/pdf', [DashboardController::class, 'exportPdf'])->name('admin.mahasiswa.export.pdf');
        Route::post('/admin/mahasiswa/import', [DashboardController::class, 'importExcel'])->name('admin.mahasiswa.import');
        
        // PMB Settings Management
        Route::get('/admin/pmb/settings', [\App\Http\Controllers\Backend\PmbSettingController::class, 'index'])->name('admin.pmb.settings');
        Route::post('/admin/pmb/settings', [\App\Http\Controllers\Backend\PmbSettingController::class, 'update'])->name('admin.pmb.settings.update');

        // PMB Brochure Management
        Route::resource('/admin/pmb/brosur', \App\Http\Controllers\Backend\BrosurPmbController::class, [
            'names' => 'admin.pmb.brosur',
        ]);

        // PMB Registration Management
        Route::get('/admin/pmb/registrations', [\App\Http\Controllers\Backend\AdminPmbController::class, 'index'])->name('admin.pmb.registrations.index');
        Route::get('/admin/pmb/registrations/export/excel', [\App\Http\Controllers\Backend\AdminPmbController::class, 'exportExcel'])->name('admin.pmb.registrations.export.excel');
        Route::get('/admin/pmb/registrations/export/pdf', [\App\Http\Controllers\Backend\AdminPmbController::class, 'exportPdf'])->name('admin.pmb.registrations.export.pdf');
        Route::get('/admin/pmb/registrations/{registration}', [\App\Http\Controllers\Backend\AdminPmbController::class, 'show'])->name('admin.pmb.registrations.show');
        Route::put('/admin/pmb/registrations/{registration}/status', [\App\Http\Controllers\Backend\AdminPmbController::class, 'updateStatus'])->name('admin.pmb.registrations.updateStatus');
        Route::post('/admin/pmb/registrations/{registration}/generate-student', [\App\Http\Controllers\Backend\AdminPmbController::class, 'generateStudent'])->name('admin.pmb.registrations.generateStudent');
        Route::delete('/admin/pmb/registrations/{registration}', [\App\Http\Controllers\Backend\AdminPmbController::class, 'destroy'])->name('admin.pmb.registrations.destroy');
        
        // Trash Management
        Route::get('/admin/pmb/trash', [\App\Http\Controllers\Backend\AdminPmbController::class, 'trash'])->name('admin.pmb.registrations.trash');
        Route::post('/admin/pmb/registrations/{id}/restore', [\App\Http\Controllers\Backend\AdminPmbController::class, 'restore'])->name('admin.pmb.registrations.restore');
        Route::delete('/admin/pmb/registrations/{id}/force', [\App\Http\Controllers\Backend\AdminPmbController::class, 'forceDelete'])->name('admin.pmb.registrations.forceDelete');
    });


    // Dosen Area
    Route::middleware(['role:dosen'])->group(function () {
        Route::get('/dosen/dashboard', [DashboardController::class, 'dosen'])->name('dosen.dashboard');
        Route::get('/dosen/jadwal', [DashboardController::class, 'jadwalDosen'])->name('dosen.jadwal');

        // Absen Mengajar
        Route::get('/dosen/presensi', [\App\Http\Controllers\Backend\PresensiDosenController::class, 'index'])->name('dosen.presensi.index');
        Route::post('/dosen/presensi', [\App\Http\Controllers\Backend\PresensiDosenController::class, 'store'])->name('dosen.presensi.store');
        Route::delete('/dosen/presensi/{id}', [\App\Http\Controllers\Backend\PresensiDosenController::class, 'destroy'])->name('dosen.presensi.destroy');

        // Grading Routes
        Route::get('/dosen/nilai', [\App\Http\Controllers\Backend\NilaiController::class, 'index'])->name('dosen.nilai.index');
        Route::get('/dosen/nilai/{jadwal}/input', [\App\Http\Controllers\Backend\NilaiController::class, 'input'])->name('dosen.nilai.input');
        Route::post('/dosen/nilai/{jadwal}/store', [\App\Http\Controllers\Backend\NilaiController::class, 'store'])->name('dosen.nilai.store');
        Route::post('/dosen/nilai/{jadwal}/settings', [\App\Http\Controllers\Backend\NilaiController::class, 'updateSettings'])->name('dosen.nilai.settings');
        Route::post('/dosen/nilai/{jadwal}/lock', [\App\Http\Controllers\Backend\NilaiController::class, 'lock'])->name('dosen.nilai.lock');
        Route::get('/dosen/nilai/{jadwal}/history', [\App\Http\Controllers\Backend\NilaiController::class, 'history'])->name('dosen.nilai.history');
        Route::get('/dosen/nilai/{jadwal}/export', [\App\Http\Controllers\Backend\NilaiController::class, 'export'])->name('dosen.nilai.export');

        // Profile Routes
        Route::get('/dosen/profile', [\App\Http\Controllers\Backend\ProfileController::class, 'index'])->name('dosen.profile.index');
        Route::post('/dosen/profile', [\App\Http\Controllers\Backend\ProfileController::class, 'update'])->name('dosen.profile.update');
    });

    // Mahasiswa Area
    Route::middleware(['role:mahasiswa'])->group(function () {
        Route::get('/mahasiswa/dashboard', [DashboardController::class, 'mahasiswa'])->name('mahasiswa.dashboard');
    });

});

Route::prefix('informasi')->name('informasi.')->group(function () {

    Route::get('/program-studi', [InformasiController::class, 'programStudi'])->name('prodi');
    Route::get('/karya-mahasiswa', [InformasiController::class, 'karyaMahasiswa'])->name('karya-mahasiswa');
    Route::get('/karya-dosen', [InformasiController::class, 'karyaDosen'])->name('karya-dosen');
    Route::get('/staf-pengajar', [InformasiController::class, 'stafPengajar'])->name('staf-pengajar');
    Route::get('/galeri', [InformasiController::class, 'galeri'])->name('galeri');
});

// Deprecated (keamanan): jangan expose migrate via GET/public.
