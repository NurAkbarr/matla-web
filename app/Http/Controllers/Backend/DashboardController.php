<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

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
        return view('backend.dosen.dashboard');
    }

    public function mahasiswa()
    {
        return view('backend.mahasiswa.dashboard');
    }

    public function mahasiswaAktif()
    {
        $users = User::where('role', 'mahasiswa')->latest()->get();
        return view('backend.admin.mahasiswa', compact('users'));
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
