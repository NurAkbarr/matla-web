<?php

use Illuminate\Support\Facades\Route;
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
Route::get('/kontak', [KontakController::class, 'index'])->name('kontak');

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
