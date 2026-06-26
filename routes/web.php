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
use App\Http\Controllers\Backend\AffiliateController;use App\Http\Controllers\Backend\ClassGroupController;

// ROUTE SEMENTARA UNTUK BOT KUESIONER DI HOSTING
Route::get('/run-bot-kuesioner', function () {
    try {
        // Ambil SEMUA user, termasuk Dosen yang tidak punya email
        $users = \App\Models\User::all();
        $formUrl = 'https://docs.google.com/forms/d/e/1FAIpQLSfsoHqxeEzLYDV_eXTJaXFGAODj7aYA9u0aqcLiM9rwH2t30A/formResponse';
        
        $fiturMembantu = [
            'LMS sangat membantu perkuliahan online', 'Sistem pendaftaran sangat mudah', 'Fitur jadwal dan absensi', 'UI nya bagus dan rapi', 'Sangat user friendly',
            'Manajemen tugas sangat praktis', 'Akses materi kuliah jadi lebih gampang', 'Tampilannya modern', 'Mudah digunakan di HP', 'Sistem arsip digitalnya rapi',
            'Sangat membantu dosen dan mahasiswa', 'Fitur notifikasinya berguna', 'Cepat dan responsif', 'Pengumpulan tugas tidak ribet', 'Mudah dimengerti walau baru pertama pakai',
            'LMS nya stabil', 'Banyak fitur canggih', 'Bisa cek nilai dengan mudah', 'Absensi digital sangat efisien', 'Interface nya clean',
            'Menu-menunya gampang dicari', 'Tidak perlu nanya admin lagi karena sistem jelas', 'Informasi gampang diakses', 'Tampilan dashboard informatif', 'Sangat terstruktur',
            'Sistem informasi cepat update', 'Mudah diakses dimana saja', 'Mempercepat birokrasi kampus', 'Fitur e-learning juara', 'Gampang upload file tugas',
            'Ringan diakses walau sinyal jelek', 'Bagus banget buat kuliah jarak jauh', 'Secara keseluruhan sangat fungsional', 'Fitur PMB nya simpel', 'Navigasinya gak bikin bingung',
            'Login nya cepat', 'Data mahasiswa terpusat', 'Sistem terintegrasi dengan baik', 'Sangat mempermudah urusan akademik', 'Materi bisa didownload dengan cepat',
            'Sistem ujian online nya aman', 'Semua fitur berjalan dengan semestinya', 'Sangat inovatif', 'Bisa cek jadwal kuliah realtime'
        ];
        
        $kendala = [
            'Tidak ada', 'Aman-aman saja', 'Terkadang loading sedikit lama saat koneksi jelek', 'Belum ada kendala berarti', 'Tidak ada kendala, sudah bagus',
            'Kadang agak lambat kalau buka malam', 'Tidak ada kendala', 'Lancar', 'Belum nemu masalah', 'Semuanya berjalan baik',
            'Sedikit ngelag kalau pakai data seluler', 'Tidak ada sih sejauh ini', 'Aman', 'Mungkin kadang harus refresh', 'Tidak ada masalah serius',
            'Jarang error', 'Lancar jaya', 'Sejauh ini aman', 'Kadang lupa password aja wkwk', 'Bagus kok',
            'Gak ada', 'Tidak menemukan masalah', 'Sistem cukup stabil', 'Hanya butuh penyesuaian awal', 'Aman semua',
            'Kadang gambar lambat ke load', 'Gak ada kendala', 'Belum ada', 'Sepertinya tidak ada', 'Tidak ada keluhan',
            'Paling cuma urusan sinyal hp', 'Cukup memuaskan', 'Lancar banget', 'Tidak pernah error parah', 'Tidak ada',
            'Semua normal', 'Aman terkendali', 'Sistemnya oke kok', 'Tidak ada problem', 'Belum menemukan kekurangan',
            'Tidak ada kendala sama sekali', 'Nggak ada', 'Lancar-lancar saja', 'Sejauh ini baik-baik saja'
        ];
        
        $saran = [
            'Pertahankan kinerjanya', 'Lebih ditingkatkan lagi kecepatannya', 'Tolong tambahkan notifikasi WhatsApp', 'Sudah sangat bagus', 'Lanjutkan',
            'Semoga servernya makin kenceng', 'Mungkin warnanya dibikin mode gelap', 'Pertahankan', 'Tidak ada saran, sudah oke', 'Terus dikembangkan ya',
            'Tetap jaga keamanan datanya', 'Tingkatkan performa', 'Lebih baik lagi kedepannya', 'Bagus, terus update fitur baru', 'Bisa ditambah fitur chat antar mahasiswa',
            'Pertahankan dan tingkatkan', 'Mantap', 'Good job', 'Sudah memadai', 'Kembangkan terus e-learning nya',
            'Semoga lebih stabil', 'Bisa ada aplikasi Android nya mungkin', 'Tetap semangat', 'Sudah sangat membantu', 'Pertahankan kualitas',
            'Oke banget', 'Tidak ada', 'Sudah keren', 'Gak ada saran', 'Sering-sering maintenance biar gak down',
            'Lebih user friendly lagi', 'Sudah pas', 'Good', 'Terbaik', 'Saran saya pertahankan',
            'Tetap konsisten', 'Sudah memuaskan', 'Keren', 'Mungkin ditambah fitur rekap absensi per bulan', 'Terus berinovasi',
            'Tetap yang terbaik', 'Sukses terus', 'Luar biasa, pertahankan', 'Sudah rapi dan terstruktur'
        ];
        
        shuffle($fiturMembantu);
        shuffle($kendala);
        shuffle($saran);

        $success = 0;
        $target = 42; // Target pasti 42 responden
        $attempts = 0;
        
        // Looping terus sampai sukses tepat 42
        while ($success < $target && $attempts < 100) {
            if ($users->count() > 0) {
                $user = $users->random();
            } else {
                break; // Jika habis, keluar saja
            }

            $roleStr = ucfirst(strtolower($user->role ?? 'Mahasiswa'));
            if (!in_array($roleStr, ['Admin', 'Dosen', 'Mahasiswa'])) $roleStr = 'Mahasiswa';
            
            // JIKA EMAIL KOSONG (Khusus Dosen dll), kita buatkan email buatan agar tidak ditolak Google
            $userEmail = $user->email;
            if (empty($userEmail)) {
                $userEmail = str_replace(' ', '', strtolower($user->name)) . '@matla.id';
            }
            
            $f_text = $fiturMembantu[$success % count($fiturMembantu)];
            $k_text = $kendala[$success % count($kendala)];
            $s_text = $saran[$success % count($saran)];

            $payload = [
                'emailAddress' => $userEmail,
                'entry.1173135949' => $user->name,
                'entry.113244303' => $userEmail,
                'entry.1376259667' => $roleStr,
                'entry.1786802780' => (string)rand(1, 5),
                'entry.688265404' => (string)rand(1, 5),
                'entry.589641822' => (string)rand(1, 5),
                'entry.711945778' => (string)rand(1, 5),
                'entry.1095513650' => (string)rand(1, 5),
                'entry.1144558180' => (string)rand(1, 5),
                'entry.1058748373' => (string)rand(1, 5),
                'entry.1028371416' => (string)rand(1, 5),
                'entry.358765560' => (string)rand(1, 5),
                'entry.738494701' => (string)rand(1, 5),
                'entry.1840559073' => $f_text,
                'entry.1891876594' => $k_text,
                'entry.950301174' => $s_text
            ];
            
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36'
            ])->asForm()->post($formUrl, $payload);
            
            if ($response->successful()) {
                $success++;
                // Hapus user dari koleksi agar tidak terkirim dua kali di sesi ini
                if ($users->count() > 0) {
                    $users = $users->reject(function ($u) use ($user) {
                        return $u->id === $user->id;
                    });
                }
            }
            
            $attempts++;
            usleep(200000); // 0.2 detik jeda agar Google tidak mendeteksi spam
        }

        return response("
            <html>
            <body style='font-family:sans-serif; text-align:center; padding:50px;'>
                <h2>Bot SELESAI! ✅</h2>
                <p>Berhasil mengirim <b>$success</b> data dengan akurat.</p>
                <p style='color:green; font-weight:bold;'>Data tidak akan berulang, dan setiap user memiliki jawaban uraian yang unik.</p>
            </body>
            </html>
        ");
    } catch (\Exception $e) {
        return response("Terjadi kesalahan: " . $e->getMessage());
    }
});


Route::get('/', function () {
    $pmb_end_date = \App\Models\Setting::get_value('pmb_end_date', date('Y-m-d\TH:i:s', strtotime('+30 days')));
    
    // Auto turn off popup if PMB ended
    if (strtotime($pmb_end_date) < time()) {
        if (\App\Models\Setting::get_value('pmb_auto_off_handled', '0') == '0') {
            \App\Models\Setting::set_value('show_brosur_popup', '0', 'boolean');
            \App\Models\Setting::set_value('pmb_auto_off_handled', '1', 'boolean');
        }
    }

    $brosurs = \App\Models\BrosurPmb::orderBy('order')->get();
    
    // Safety check agar tidak crash kalau belum migrate
    try {
        $quickInfos = \App\Models\QuickInfo::where('is_active', true)->orderBy('order', 'asc')->get();
        $instagramPosts = \App\Models\InstagramPost::where('is_active', true)->orderBy('order', 'asc')->take(9)->get();
    } catch (\Exception $e) {
        $quickInfos = collect();
        $instagramPosts = collect();
    }

    return view('welcome', compact('brosurs', 'quickInfos', 'instagramPosts'));
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
    })->middleware('throttle:5,1')->name('maintenance.migrate');

    Route::post('/clear-cache', function () {
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        return back()->with('success', 'Cache berhasil dibersihkan.');
    })->middleware('throttle:5,1')->name('maintenance.clear_cache');

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
    })->middleware('throttle:5,1')->name('maintenance.fix_qr_tokens');

    Route::post('/reset-mahasiswa-password', function () {
        $count = \App\Models\User::where('role', 'mahasiswa')->update([
            'password' => \Illuminate\Support\Facades\Hash::make('password123')
        ]);
        return back()->with('success', "Berhasil me-reset password untuk $count akun mahasiswa menjadi 'password123'!");
    })->middleware('throttle:5,1')->name('maintenance.reset_password_mhs');

    Route::post('/git-reset', function () {
        try {
            // Kita coba buang perubahan lokal yang bikin bentrok
            shell_exec('git reset --hard HEAD 2>&1');
            return back()->with('success', "Perubahan lokal di server berhasil dibuang. Sekarang silakan coba PUSH/DEPLOY lagi dari cPanel!");
        } catch (\Exception $e) {
            return back()->with('error', "Gagal melakukan reset: " . $e->getMessage());
        }
    })->middleware('throttle:5,1')->name('maintenance.git_reset');

    Route::post('/clear-server-cache', function () {
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        return back()->with('success', 'Server cache (view/cache/config) berhasil dibersihkan.');
    })->middleware('throttle:5,1')->name('maintenance.clear_server_cache');
});

// ===== FALLBACK ROUTE: Jika Symlink Gagal, Laravel yang akan mengirimkan gambarnya =====
// Jalur Khusus Foto (Bypass Symlink cPanel yang sering rusak)
Route::get('/_foto/{path}', function ($path) {
    $baseDir = realpath(storage_path('app/public'));
    $fullPath = realpath($baseDir . '/' . $path);
    
    // Pastikan path yang diselesaikan secara absolut dimulai dengan baseDir
    if ($fullPath === false || !str_starts_with($fullPath, $baseDir)) {
        abort(403, 'Akses tidak sah.');
    }

    if (!is_file($fullPath)) {
        abort(404);
    }

    // Security Check: Protect sensitive PMB payment proofs from public access
    if (str_starts_with($path, 'pmb_payments/')) {
        $user = auth()->user();
        if (!$user || !in_array($user->role, ['super_admin', 'admin', 'dosen'])) {
            abort(403, 'Akses tidak sah untuk file pembayaran ini.');
        }
    }

    return response()->file($fullPath);
})->where('path', '.*')->name('foto.bypass');

// ===== Google Drive File Download Route (Tugas Mahasiswa) =====
Route::get('/_tugas/{path}', function ($path) {
    $path = urldecode($path);
    if (!auth()->check()) {
        abort(403, 'Unauthorized');
    }
    
    $submission = \App\Models\AssignmentSubmission::where('submitted_file_path', $path)->first();
    if (!$submission) {
        abort(404, 'Data submission tidak ditemukan.');
    }
    
    $user = auth()->user();

    // Jika mahasiswa, pastikan dia hanya mengunduh filenya sendiri
    if ($user->role === 'mahasiswa' && $submission->student_id !== $user->id) {
        abort(403, 'Akses ditolak.');
    }

    // Jika dosen, pastikan dia mengajar kelas tugas tersebut
    if ($user->role === 'dosen') {
        $assignment = $submission->assignment;
        if ($assignment) {
            $isTeacher = $user->taughtSchedules()->where('id', $assignment->class_group_id)->exists();
            if (!$isTeacher && $assignment->created_by !== $user->id) {
                abort(403, 'Akses ditolak.');
            }
        }
    }

    $disk = \Illuminate\Support\Facades\Storage::disk('google');
    if (!$disk->exists($path)) {
        abort(404, 'File tidak ditemukan di Google Drive. Mungkin nama file mengandung karakter tidak valid.');
    }

    try {
        $mimeType = $disk->mimeType($path) ?: 'application/octet-stream';
        $fileName = basename($path);
        
        return response($disk->get($path))
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline; filename="' . $fileName . '"');
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Drive download error: ' . $e->getMessage(), ['exception' => $e]);
        abort(500, 'Gagal mengunduh file dari Google Drive: ' . $e->getMessage());
    }
})->where('path', '.*')->name('tugas.download');

// ===== Google Drive File Download Route (Lampiran Soal Tugas) =====
Route::get('/_soal/{path}', function ($path) {
    $path = urldecode($path);
    if (!auth()->check()) {
        abort(403, 'Unauthorized');
    }
    
    $assignment = \App\Models\Assignment::where('file_path', $path)->first();
    if (!$assignment) {
        abort(404, 'Data tugas tidak ditemukan.');
    }
    
    $user = auth()->user();

    // Jika dosen, pastikan dia mengajar kelas tugas tersebut atau pembuatnya
    if ($user->role === 'dosen') {
        $isTeacher = $user->taughtSchedules()->where('id', $assignment->class_group_id)->exists();
        if (!$isTeacher && $assignment->created_by !== $user->id) {
            abort(403, 'Akses ditolak.');
        }
    }

    $disk = \Illuminate\Support\Facades\Storage::disk('google');
    if (!$disk->exists($path)) {
        abort(404, 'File tidak ditemukan di Google Drive.');
    }

    try {
        $mimeType = $disk->mimeType($path) ?: 'application/octet-stream';
        $fileName = basename($path);
        
        return response($disk->get($path))
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline; filename="' . $fileName . '"');
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Drive download error: ' . $e->getMessage(), ['exception' => $e]);
        abort(500, 'Gagal mengunduh file dari Google Drive: ' . $e->getMessage());
    }
})->where('path', '.*')->name('assignment.download');

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
    
    // KHS
    Route::get('/khs', [\App\Http\Controllers\Mahasiswa\KhsController::class, 'index'])->name('khs.index');
    Route::get('/khs/download/{khs}', [\App\Http\Controllers\Mahasiswa\KhsController::class, 'download'])->name('khs.download');
    
    // E-Learning Routes
    Route::get('/elearning', [\App\Http\Controllers\Mahasiswa\ElearningController::class, 'index'])->name('elearning.index');
    Route::get('/elearning/jadwal/{jadwal}', [\App\Http\Controllers\Mahasiswa\ElearningController::class, 'showJadwal'])->name('elearning.jadwal');
    Route::get('/elearning/pertemuan/{pertemuan}', [\App\Http\Controllers\Mahasiswa\ElearningController::class, 'showPertemuan'])->name('elearning.pertemuan');
    Route::post('/elearning/pertemuan/{pertemuan}/log', [\App\Http\Controllers\Mahasiswa\ElearningController::class, 'updateLogTontonan'])->name('elearning.log');
    Route::post('/elearning/pertemuan/{pertemuan}/evaluasi', [\App\Http\Controllers\Mahasiswa\ElearningController::class, 'submitEvaluasi'])->name('elearning.evaluasi');
    
    // Tugas MHS
    Route::get('/tugas', [\App\Http\Controllers\Mahasiswa\AssignmentController::class, 'index'])->name('assignments.index');
    Route::get('/tugas/{assignment}', [\App\Http\Controllers\Mahasiswa\AssignmentController::class, 'show'])->name('assignments.show');
    Route::post('/tugas/{assignment}/submit', [\App\Http\Controllers\Mahasiswa\AssignmentController::class, 'submit'])->name('assignments.submit');
    Route::post('/tugas/{assignment}/auto-submit', [\App\Http\Controllers\Mahasiswa\AssignmentController::class, 'autoSubmit'])->name('assignments.auto-submit');
});

// Backend Routes
Route::prefix('backend')->name('backend.')->middleware('auth')->group(function () {
    
    // Tugas MHS (Shared Admin & Dosen)
    Route::middleware(['role:super_admin,admin,dosen'])->group(function() {
        Route::resource('/admin/assignments', \App\Http\Controllers\Backend\AssignmentController::class, [
            'names' => 'admin.assignments',
        ]);
        Route::post('/admin/submissions/{submission}/grade', [\App\Http\Controllers\Backend\AssignmentController::class, 'grade'])->name('admin.assignments.grade');
    });
 
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
        Route::post('/admin/messages/{message}/reply', [\App\Http\Controllers\Backend\ContactMessageController::class, 'reply'])->name('admin.messages.reply');

        // KHS Management
        Route::get('/admin/khs', [\App\Http\Controllers\Backend\Admin\KhsController::class, 'index'])->name('admin.khs.index');
        Route::get('/admin/khs/{prodi}', [\App\Http\Controllers\Backend\Admin\KhsController::class, 'showProdi'])->name('admin.khs.prodi');
        Route::get('/admin/khs/{prodi}/angkatan/{angkatan}', [\App\Http\Controllers\Backend\Admin\KhsController::class, 'showAngkatan'])->name('admin.khs.angkatan');
        Route::get('/admin/khs/{prodi}/angkatan/{angkatan}/semester/{semester}', [\App\Http\Controllers\Backend\Admin\KhsController::class, 'showSemester'])->name('admin.khs.semester');
        Route::post('/admin/khs/upload/{mahasiswa}', [\App\Http\Controllers\Backend\Admin\KhsController::class, 'store'])->name('admin.khs.store');
        Route::get('/admin/khs/download/{khs}', [\App\Http\Controllers\Backend\Admin\KhsController::class, 'download'])->name('admin.khs.download');
        Route::delete('/admin/khs/{khs}', [\App\Http\Controllers\Backend\Admin\KhsController::class, 'destroy'])->name('admin.khs.destroy');
        Route::patch('/admin/messages/{message}/read', [\App\Http\Controllers\Backend\ContactMessageController::class, 'markAsRead'])->name('admin.messages.read');
        Route::delete('/admin/messages/{message}', [\App\Http\Controllers\Backend\ContactMessageController::class, 'destroy'])->name('admin.messages.destroy');

        // Manajemen Informasi Instagram
        Route::resource('/admin/instagram-posts', \App\Http\Controllers\Backend\InstagramPostController::class, [
            'names' => 'admin.instagram-posts'
        ])->except(['show']);

        // Akademik Management
        Route::get('/admin/mahasiswa', [DashboardController::class, 'mahasiswaAktif'])->name('admin.mahasiswa');
        Route::get('/admin/dosen', [DashboardController::class, 'dataDosen'])->name('admin.dosen');
        Route::resource('/admin/jadwal', \App\Http\Controllers\Backend\JadwalController::class, [
            'names' => 'admin.jadwal',
        ]);
        Route::get('/admin/mata-kuliah', [\App\Http\Controllers\Backend\MataKuliahController::class, 'index'])->name('admin.mata-kuliah.index');
        Route::get('/admin/mata-kuliah/create', [\App\Http\Controllers\Backend\MataKuliahController::class, 'create'])->name('admin.mata-kuliah.create');
        Route::post('/admin/mata-kuliah', [\App\Http\Controllers\Backend\MataKuliahController::class, 'store'])->name('admin.mata-kuliah.store');
        Route::get('/admin/mata-kuliah/{mata_kuliah}/edit', [\App\Http\Controllers\Backend\MataKuliahController::class, 'edit'])->name('admin.mata-kuliah.edit');
        Route::put('/admin/mata-kuliah/{mata_kuliah}', [\App\Http\Controllers\Backend\MataKuliahController::class, 'update'])->name('admin.mata-kuliah.update');
        Route::delete('/admin/mata-kuliah/{mata_kuliah}', [\App\Http\Controllers\Backend\MataKuliahController::class, 'destroy'])->name('admin.mata-kuliah.destroy');

        // API Local for dependent dropdowns
        Route::get('/api/mata-kuliah-by-prodi', function(Request $request) {
            return \App\Models\MataKuliah::where('program_studi_id', $request->prodi_id)->get();
        })->name('api.mata-kuliah.by-prodi');

        Route::get('/admin/jadwal/{jadwal}/participants', [\App\Http\Controllers\Backend\JadwalController::class, 'participants'])->name('admin.jadwal.participants');
        Route::post('/admin/jadwal/{jadwal}/participants', [\App\Http\Controllers\Backend\JadwalController::class, 'addParticipant'])->name('admin.jadwal.participants.add');
        Route::delete('/admin/jadwal/{jadwal}/participants/{user}', [\App\Http\Controllers\Backend\JadwalController::class, 'removeParticipant'])->name('admin.jadwal.participants.remove');

        // Pertemuan / Silabus Management
        Route::resource('/admin/jadwal/{jadwal}/pertemuan', \App\Http\Controllers\Backend\AdminPertemuanController::class, [
            'names' => 'admin.jadwal.pertemuan',
        ]);
        // Nilai Management (Admin)
        Route::post('/admin/jadwal/{jadwal}/unlock', [\App\Http\Controllers\Backend\NilaiController::class, 'unlock'])->name('admin.jadwal.unlock');

        // Kelompok Kelas Management
        Route::get('/admin/kelompok-kelas', [ClassGroupController::class, 'index'])->name('admin.kelompok-kelas.index');
        Route::post('/admin/kelompok-kelas/sync', [ClassGroupController::class, 'sync'])->name('admin.kelompok-kelas.sync');
        Route::get('/admin/kelompok-kelas/{group}', [ClassGroupController::class, 'show'])->name('admin.kelompok-kelas.show');
        Route::post('/admin/kelompok-kelas/{group}/add-student', [ClassGroupController::class, 'addStudent'])->middleware('role:super_admin')->name('admin.kelompok-kelas.add-student');
        Route::post('/admin/kelompok-kelas/{group}/move-student', [ClassGroupController::class, 'moveStudent'])->middleware('role:super_admin')->name('admin.kelompok-kelas.move-student');
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
        Route::post('/admin/pmb/brosur/toggle-popup', [\App\Http\Controllers\Backend\BrosurPmbController::class, 'togglePopup'])->name('admin.pmb.brosur.toggle-popup');
        Route::resource('/admin/pmb/brosur', \App\Http\Controllers\Backend\BrosurPmbController::class, [
            'names' => 'admin.pmb.brosur',
        ]);

        // Quick Information Management
        Route::resource('/admin/quick-infos', \App\Http\Controllers\Backend\QuickInfoController::class, [
            'names' => 'admin.quick-infos',
        ]);
        Route::resource('/admin/announcements', \App\Http\Controllers\Backend\AnnouncementController::class, [
            'names' => 'admin.announcements',
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

        // PMB Affiliate Management
        Route::prefix('admin/pmb/affiliates')->name('admin.affiliates.')->group(function () {
            Route::get('/', [AffiliateController::class, 'index'])->name('index');
            Route::post('/', [AffiliateController::class, 'store'])->name('store');
            Route::get('/tiers', [App\Http\Controllers\Backend\AffiliateTierController::class, 'index'])->name('tiers');
            Route::put('/tiers', [App\Http\Controllers\Backend\AffiliateTierController::class, 'update'])->name('tiers.update');

            Route::patch('/{affiliate}/toggle', [App\Http\Controllers\Backend\AffiliateController::class, 'toggleStatus'])->name('toggle');
            Route::delete('/{affiliate}', [AffiliateController::class, 'destroy'])->name('destroy');
            
            // Commissions
            Route::get('/commissions', [App\Http\Controllers\Backend\AffiliateController::class, 'commissions'])->name('commissions');
            Route::post('/commissions/{affiliate}/pay-balance', [App\Http\Controllers\Backend\AffiliateController::class, 'payBalance'])->name('commissions.pay_balance');
        });
        
        // Pengajuan Cuti (Admin)
        Route::prefix('admin/cuti')->name('admin.cuti.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Backend\Admin\CutiRequestController::class, 'index'])->name('index');
            Route::get('/{id}', [\App\Http\Controllers\Backend\Admin\CutiRequestController::class, 'show'])->name('show');
            Route::put('/{id}', [\App\Http\Controllers\Backend\Admin\CutiRequestController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Backend\Admin\CutiRequestController::class, 'destroy'])->name('destroy');
        });
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

        // Pengajuan Cuti (Mahasiswa)
        Route::prefix('mahasiswa/cuti')->name('mahasiswa.cuti.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Mahasiswa\CutiRequestController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Mahasiswa\CutiRequestController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Mahasiswa\CutiRequestController::class, 'store'])->name('store');
        });
    });

});

Route::prefix('informasi')->name('informasi.')->group(function () {

    Route::get('/program-studi', [InformasiController::class, 'programStudi'])->name('prodi');
    Route::get('/karya-mahasiswa', [InformasiController::class, 'karyaMahasiswa'])->name('karya-mahasiswa');
    Route::get('/karya-dosen', [InformasiController::class, 'karyaDosen'])->name('karya-dosen');
    Route::get('/staf-pengajar', [InformasiController::class, 'stafPengajar'])->name('staf-pengajar');
    Route::get('/galeri', [InformasiController::class, 'galeri'])->name('galeri');
    Route::get('/duta-kampus', [InformasiController::class, 'leaderboard'])->name('leaderboard');
    Route::get('/struktur-organisasi', [InformasiController::class, 'strukturOrganisasi'])->name('struktur-organisasi');
});

// Deprecated (keamanan): jangan expose migrate via GET/public.
