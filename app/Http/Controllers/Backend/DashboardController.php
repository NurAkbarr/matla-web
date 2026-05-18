<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function admin()
    {
        $totalMahasiswa = User::where('role', 'mahasiswa')->count();
        $totalDosen     = User::where('role', 'dosen')->count();
        $pendaftarBaru  = \App\Models\PmbRegistration::where('status', 'pending')->count();
        
        $recentActivities = \App\Models\PmbRegistration::latest()->take(5)->get();

        return view('backend.admin.dashboard', compact('totalMahasiswa', 'totalDosen', 'pendaftarBaru', 'recentActivities'));
    }

    public function dosen()
    {
        $user = auth()->user();
        $totalKelas = $user->taughtSchedules()->count();
        
        $jadwalIds = $user->taughtSchedules()->pluck('id');
        $totalMahasiswa = \DB::table('jadwal_mahasiswa')
            ->whereIn('jadwal_id', $jadwalIds)
            ->distinct('mahasiswa_id')
            ->count();

        return view('backend.dosen.dashboard', compact('totalKelas', 'totalMahasiswa'));
    }

    public function jadwalDosen()
    {
        $user = auth()->user();
        $jadwals = $user->taughtSchedules()
            ->with(['programStudi', 'participants'])
            ->latest()
            ->get();

        return view('backend.dosen.jadwal', compact('jadwals'));
    }

    public function mahasiswa()
    {
        $user = auth()->user();
        $enrolledSchedules = $user->enrolledSchedules()->with('dosen')->get();
        
        $totalSKS = $enrolledSchedules->sum('sks');
        $totalMataKuliah = $enrolledSchedules->count();
        
        // Setup days translation map
        $hariMap = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];
        $hariIni = $hariMap[now()->format('l')];
        
        $jadwalHariIni = $enrolledSchedules->filter(function($jadwal) use ($hariIni) {
            return strtolower($jadwal->hari) === strtolower($hariIni);
        });

        // Hitung tugas yang belum selesai dari pertemuan video ganjil
        $pendingTasks = 0;
        foreach ($enrolledSchedules as $jadwal) {
            foreach ($jadwal->pertemuans as $pertemuan) {
                if ($pertemuan->tipe_pertemuan == 'video' && $pertemuan->soal_evaluasi) {
                    $hasEvaluated = \App\Models\JawabanEvaluasi::where('mahasiswa_id', $user->id)
                                    ->where('pertemuan_id', $pertemuan->id)->exists();
                    if (!$hasEvaluated) {
                        $pendingTasks++;
                    }
                }
            }
        }

        $announcements = \App\Models\Announcement::where('is_active', true)
                        ->latest('published_at')
                        ->take(5)
                        ->get();

        return view('backend.mahasiswa.dashboard', compact(
            'enrolledSchedules', 
            'totalSKS', 
            'totalMataKuliah', 
            'jadwalHariIni',
            'pendingTasks',
            'announcements'
        ));
    }

    public function mahasiswaAktif(Request $request)
    {
        $query = User::where('role', 'mahasiswa');

        // Apply Search
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%")
                  ->orWhere('nim', 'like', "%{$searchTerm}%");
            });
        }

        // Apply Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('angkatan')) {
            $query->where('angkatan', $request->angkatan);
        }
        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }
        if ($request->filled('program_studi')) {
            $query->where('education->program_studi', $request->program_studi);
        }

        $users = $query->latest()->paginate(15);

        // Data for filters
        $angkatans = User::where('role', 'mahasiswa')->whereNotNull('angkatan')->distinct()->pluck('angkatan')->sort()->values();
        $semesters = User::where('role', 'mahasiswa')->whereNotNull('semester')->distinct()->pluck('semester')->sort()->values();
        $prodis = \App\Models\ProgramStudi::active()->get();

        return view('backend.admin.mahasiswa', compact('users', 'angkatans', 'semesters', 'prodis'));
    }

    public function exportExcel(Request $request)
    {
        $filename = "mahasiswa_" . date('Y-m-d') . ".csv";
        $columns = ['Nama', 'Email', 'Angkatan', 'Semester', 'Status'];

        $users = User::where('role', 'mahasiswa')->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($users, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->name,
                    $user->email,
                    $user->angkatan,
                    $user->semester,
                    $user->status
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $users = User::where('role', 'mahasiswa')
                    ->orderBy('angkatan', 'desc')
                    ->orderBy('name', 'asc')
                    ->get();
                    
        return view('backend.admin.mahasiswa_print', compact('users'));
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('csv_file');
        $fileHandle = fopen($file->getPathname(), 'r');
        
        $headerLine = fgets($fileHandle);
        // Reset file pointer if first line doesn't look like our header
        if (stripos($headerLine, 'nama') === false && stripos($headerLine, 'email') === false) {
            rewind($fileHandle);
        }

        $count = 0;
        while (($row = fgetcsv($fileHandle, 1000, ',')) !== false) {
            // Karena kadang delimiter di excel CSV indonesia pakai titik koma (;), kita cek
            if (count($row) == 1 && strpos($row[0], ';') !== false) {
                $row = explode(';', $row[0]);
            }

            // Cek minimum 3 kolom (NIM, Nama, Email) dan pastikan email valid
            if (count($row) >= 3 && filter_var(trim($row[2]), FILTER_VALIDATE_EMAIL)) {
                
                $nim = trim($row[0]);
                $nama = trim($row[1]);
                $email = trim($row[2]);
                $prodi = count($row) >= 4 ? trim($row[3]) : null;
                $status = count($row) >= 6 ? strtoupper(trim($row[5])) : 'AKTIF';
                
                // Jika NIM kosong, set ke null agar tidak melanggar unique constraint
                $nimValue = empty($nim) ? null : $nim;
                
                $user = User::updateOrCreate(
                    ['email' => $email],
                    [
                        'name' => $nama,
                        'nim' => $nimValue,
                        'role' => 'mahasiswa',
                        'password' => bcrypt('password123'),
                        'status' => $status
                    ]
                );

                // Update info Prodi ke dalam array JSON education
                $education = $user->education ?? [];
                if ($prodi) {
                    $education['program_studi'] = $prodi;
                }
                $user->education = $education;
                $user->save();

                $count++;
            }
        }
        fclose($fileHandle);

        // Auto-synchronize class groups on successful import
        try {
            \Illuminate\Support\Facades\Artisan::call('classgroup:sync');
        } catch (\Exception $e) {
            // Keep going if anything goes wrong with artisan call
        }

        return redirect()->back()->with('success', "$count data mahasiswa berhasil di-import/diperbarui.");
    }

    public function dataDosen()
    {
        $users = User::where('role', 'dosen')->latest()->get();
        return view('backend.admin.dosen', compact('users'));
    }

    public function jadwalPerkuliahan()
    {
        return view('backend.admin.jadwal.index');
    }
}
