<?php

namespace App\Http\Controllers\Backend\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index()
    {
        // Get all users with role 'mahasiswa'
        $mahasiswas = User::where('role', 'mahasiswa')->orderBy('name', 'asc')->get();

        // Extract unique options for filters
        $programStudis = $mahasiswas->map(function($mhs) {
            return $mhs->education['program_studi'] ?? null;
        })->filter()->unique()->sort()->values();

        $angkatans = $mahasiswas->pluck('angkatan')->filter()->unique()->sort()->values();
        $semesters = $mahasiswas->pluck('semester')->filter()->unique()->sort()->values();
        $statuses = $mahasiswas->pluck('status')->filter()->unique()->sort()->values();
        
        return view('backend.keuangan.mahasiswa.index', compact('mahasiswas', 'programStudis', 'angkatans', 'semesters', 'statuses'));
    }
}
