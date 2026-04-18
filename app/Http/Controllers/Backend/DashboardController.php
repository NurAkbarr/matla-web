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
        return view('backend.mahasiswa.dashboard');
    }

    public function mahasiswaAktif(Request $request)
    {
        $query = User::where('role', 'mahasiswa');

        // Apply Search
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
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

        $users = $query->latest()->get();

        // Data for filters
        $angkatans = User::where('role', 'mahasiswa')->whereNotNull('angkatan')->distinct()->pluck('angkatan')->sort()->values();
        $semesters = User::where('role', 'mahasiswa')->whereNotNull('semester')->distinct()->pluck('semester')->sort()->values();

        return view('backend.admin.mahasiswa', compact('users', 'angkatans', 'semesters'));
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
            // Cek minimum 2 kolom (Nama, Email)
            if (count($row) >= 2 && filter_var(trim($row[1]), FILTER_VALIDATE_EMAIL)) {
                User::updateOrCreate(
                    ['email' => trim($row[1])],
                    [
                        'name' => trim($row[0]),
                        'role' => 'mahasiswa',
                        'angkatan' => count($row) >= 3 ? trim($row[2]) : null,
                        'semester' => count($row) >= 4 ? trim($row[3]) : '1',
                        'password' => bcrypt('password123'),
                        'status' => 'AKTIF'
                    ]
                );
                $count++;
            }
        }
        fclose($fileHandle);

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
