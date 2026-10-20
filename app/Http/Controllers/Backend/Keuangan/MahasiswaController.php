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

        $query = $this->buildFilterQuery($request);

        $mahasiswas = $query->orderByRaw("CASE WHEN LOWER(COALESCE(status, 'aktif')) = 'aktif' THEN 0 ELSE 1 END")
            ->orderBy('name', 'asc')
            ->paginate($perPage)
            ->withQueryString();

        // Extract unique options for filters from all mahasiswa
        $allMahasiswa = User::where('role', 'mahasiswa')->get(['education', 'angkatan', 'semester', 'status']);

        $programStudis = $allMahasiswa->map(function ($mhs) {
            return $mhs->education['program_studi'] ?? null;
        })->filter()->unique()->sort()->values();

        $angkatans = $allMahasiswa->pluck('angkatan')->filter()->unique()->sort()->values();
        $semesters = $allMahasiswa->pluck('semester')->filter()->unique()->sort()->values();
        
        // Daftar status baku
        $statuses = collect(['AKTIF', 'CUTI', 'NONAKTIF', 'LULUS', 'KELUAR']);
        $existingStatuses = $allMahasiswa->pluck('status')->filter()->map(fn($s) => strtoupper($s))->unique();
        $statuses = $statuses->merge($existingStatuses)->unique()->values();

        return view('backend.keuangan.mahasiswa.index', compact(
            'mahasiswas',
            'programStudis',
            'angkatans',
            'semesters',
            'statuses',
            'perPage'
        ));
    }

    public function updateNominalSpp(Request $request, $id)
    {
        $request->validate([
            'nominal_spp' => 'required|numeric|min:0',
            'catatan_spp' => 'nullable|string|max:255',
        ], [
            'nominal_spp.required' => 'Nominal SPP per bulan wajib diisi.',
            'nominal_spp.numeric' => 'Nominal SPP harus berupa angka.',
            'nominal_spp.min' => 'Nominal SPP tidak boleh bernilai negatif.',
            'catatan_spp.max' => 'Catatan keringanan/beasiswa maksimal 255 karakter.',
        ]);

        $mhs = User::where('role', 'mahasiswa')->findOrFail($id);

        $mhs->update([
            'nominal_spp' => $request->nominal_spp,
            'catatan_spp' => $request->catatan_spp,
        ]);

        return back()->with('success', "Nominal SPP untuk mahasiswa {$mhs->name} berhasil diperbarui menjadi Rp " . number_format($request->nominal_spp, 0, ',', '.') . ".");
    }

    public function exportExcel(Request $request)
    {
        $filename = "rekap_mahasiswa_keuangan_" . date('Y-m-d_His') . ".csv";
        $columns = [
            'No',
            'NIM',
            'Nama Mahasiswa',
            'Program Studi',
            'Angkatan',
            'Semester',
            'SPP per Bulan (Rp)',
            'Catatan Keringanan / SPP',
            'No HP / WA',
            'Email',
            'Status Akademik'
        ];

        $users = $this->buildFilterQuery($request)
            ->orderByRaw("CASE WHEN LOWER(COALESCE(status, 'aktif')) = 'aktif' THEN 0 ELSE 1 END")
            ->orderBy('name', 'asc')
            ->get();

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($users, $columns) {
            $file = fopen('php://output', 'w');
            // Tulis UTF-8 BOM agar Excel membaca karakter & mata uang dengan benar
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, $columns);
            $no = 1;
            foreach ($users as $user) {
                fputcsv($file, [
                    $no++,
                    $user->nim ? "'".$user->nim : '-', // Tanda kutip agar format NIM angka tidak terpotong leading zero
                    $user->name,
                    $user->education['program_studi'] ?? '-',
                    $user->angkatan ?? '-',
                    $user->semester ? 'Semester ' . $user->semester : '-',
                    (float) ($user->nominal_spp ?? 300000),
                    $user->catatan_spp ?? '-',
                    $user->phone ? "'".$user->phone : '-',
                    $user->email ?? '-',
                    strtoupper($user->status ?? 'AKTIF')
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $users = $this->buildFilterQuery($request)
            ->orderByRaw("CASE WHEN LOWER(COALESCE(status, 'aktif')) = 'aktif' THEN 0 ELSE 1 END")
            ->orderBy('name', 'asc')
            ->get();

        $totalMahasiswa = $users->count();
        $totalAktif = $users->filter(fn($u) => strtolower($u->status ?? 'aktif') === 'aktif')->count();
        $totalPotensiSpp = $users->where('status', 'AKTIF')->sum('nominal_spp');

        return view('backend.keuangan.mahasiswa.print', compact(
            'users',
            'totalMahasiswa',
            'totalAktif',
            'totalPotensiSpp'
        ));
    }

    private function buildFilterQuery(Request $request)
    {
        $query = User::where('role', 'mahasiswa');

        // Search by name, nim, email, or phone
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
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
            $status = $request->input('status');
            $query->whereRaw('LOWER(status) = ?', [strtolower($status)]);
        }

        return $query;
    }
}
