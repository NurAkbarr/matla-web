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
    return view('welcome');
});

// Authentication
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);


Route::get('/pmb', [PmbController::class, 'index'])->name('pmb');
Route::get('/pmb/register', [\App\Http\Controllers\PmbRegistrationController::class, 'create'])->name('pmb.register');
Route::post('/pmb/register', [\App\Http\Controllers\PmbRegistrationController::class, 'store'])->name('pmb.register.store');
Route::get('/pmb/success/{registration_code}', [\App\Http\Controllers\PmbRegistrationController::class, 'success'])->name('pmb.success');
Route::get('/pmb/status', [\App\Http\Controllers\PmbStatusController::class, 'index'])->name('pmb.status');
Route::post('/pmb/status', [\App\Http\Controllers\PmbStatusController::class, 'check'])->name('pmb.status.check');
Route::get('/pmb/loa/{registration_code}', [\App\Http\Controllers\PmbStatusController::class, 'printLoa'])->name('pmb.loa');
Route::get('/kontak', [KontakController::class, 'index'])->name('kontak');
Route::post('/kontak', [KontakController::class, 'store'])->name('kontak.store');

// Backend Routes
Route::prefix('backend')->name('backend.')->middleware('auth')->group(function () {
    
    // Admin & Super Admin Area
    Route::middleware(['role:super_admin,admin'])->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
        
        // User Management
        Route::get('/admin/users', [\App\Http\Controllers\Backend\UserController::class, 'index'])->name('admin.users.index');
        Route::get('/admin/users/create', [\App\Http\Controllers\Backend\UserController::class, 'create'])->name('admin.users.create');
        Route::post('/admin/users', [\App\Http\Controllers\Backend\UserController::class, 'store'])->name('admin.users.store');
        Route::get('/admin/users/{user}', [\App\Http\Controllers\Backend\UserController::class, 'show'])->name('admin.users.show');
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
        Route::get('/admin/pmb/registrations/{registration}', [\App\Http\Controllers\Backend\AdminPmbController::class, 'show'])->name('admin.pmb.registrations.show');
        Route::put('/admin/pmb/registrations/{registration}/status', [\App\Http\Controllers\Backend\AdminPmbController::class, 'updateStatus'])->name('admin.pmb.registrations.updateStatus');
    });


    // Dosen Area
    Route::middleware(['role:dosen'])->group(function () {
        Route::get('/dosen/dashboard', [DashboardController::class, 'dosen'])->name('dosen.dashboard');
        Route::get('/dosen/jadwal', [DashboardController::class, 'jadwalDosen'])->name('dosen.jadwal');

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

Route::get('/run-migrate', function () {
    Artisan::call('migrate', ['--force' => true]);
    return 'Migration done';
});
