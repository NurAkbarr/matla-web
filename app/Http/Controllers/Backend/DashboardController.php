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
        $pendaftarBaru  = 0; // Belum ada modul pendaftaran

        return view('backend.admin.dashboard', compact('totalMahasiswa', 'totalDosen', 'pendaftarBaru'));
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
