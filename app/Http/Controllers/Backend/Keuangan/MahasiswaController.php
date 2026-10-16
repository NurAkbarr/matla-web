<?php

namespace App\Http\Controllers\Backend\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        $query = User::where('role', 'mahasiswa');

        // Search by name, nim, or email
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter Program Studi
        if ($request->filled('prodi')) {
            $prodi = $request->input('prodi');
            $query->where('education->program_studi', $prodi);
        }

        // Filter Angkatan
        if ($request->filled('angkatan')) {
            $query->where('angkatan', $request->input('angkatan'));
        }

        // Filter Semester
        if ($request->filled('semester')) {
            $query->where('semester', $request->input('semester'));
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $mahasiswas = $query->orderBy('name', 'asc')->paginate($perPage)->withQueryString();

        // Extract unique options for filters from all mahasiswa
        $allMahasiswa = User::where('role', 'mahasiswa')->get(['education', 'angkatan', 'semester', 'status']);

        $programStudis = $allMahasiswa->map(function ($mhs) {
            return $mhs->education['program_studi'] ?? null;
        })->filter()->unique()->sort()->values();

        $angkatans = $allMahasiswa->pluck('angkatan')->filter()->unique()->sort()->values();
        $semesters = $allMahasiswa->pluck('semester')->filter()->unique()->sort()->values();
        $statuses = $allMahasiswa->pluck('status')->filter()->unique()->sort()->values();

        return view('backend.keuangan.mahasiswa.index', compact(
            'mahasiswas',
            'programStudis',
            'angkatans',
            'semesters',
            'statuses',
            'perPage'
        ));
    }
}
