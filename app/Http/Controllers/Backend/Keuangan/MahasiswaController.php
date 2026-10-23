<?php

namespace App\Http\Controllers\Backend\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = $this->buildFilterQuery($request);

        $mahasiswas = $query->orderByRaw("CASE WHEN LOWER(COALESCE(status, 'aktif')) = 'aktif' THEN 0 ELSE 1 END")
            ->orderBy('name', 'asc')
            ->paginate(50)
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

        $mahasiswa = $mahasiswas;

        return view('backend.keuangan.mahasiswa.index', compact(
            'mahasiswas',
            'mahasiswa',
            'programStudis',
            'angkatans',
            'semesters',
            'statuses'
        ));
    }

    public function updateNominalSpp(Request $request, $id)
    {
        $request->validate([
            'nominal_spp' => 'required|numeric|min:0',
        ], [
            'nominal_spp.required' => 'Nominal SPP per bulan wajib diisi.',
            'nominal_spp.numeric' => 'Nominal SPP harus berupa angka.',
            'nominal_spp.min' => 'Nominal SPP tidak boleh bernilai negatif.',
        ]);

        $mhs = User::where('role', 'mahasiswa')->findOrFail($id);

        $mhs->update([
            'nominal_spp' => $request->nominal_spp,
        ]);

        return back()->with('success', "Nominal SPP untuk mahasiswa {$mhs->name} berhasil diperbarui menjadi Rp " . number_format($request->nominal_spp, 0, ',', '.') . ".");
    }

    public function exportExcel(Request $request)
    {
        $filename = "rekap_mahasiswa_spp_" . date('Y-m-d_His') . ".xls";

        $users = $this->buildFilterQuery($request)
            ->orderByRaw("CASE WHEN LOWER(COALESCE(status, 'aktif')) = 'aktif' THEN 0 ELSE 1 END")
            ->orderBy('name', 'asc')
            ->get();

        $headers = [
            "Content-Type"        => "application/vnd.ms-excel; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=\"{$filename}\"",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        $html .= '<head>';
        $html .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        $html .= '<!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>Data SPP Mahasiswa</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]-->';
        $html .= '<style>';
        $html .= 'table { border-collapse: collapse; font-family: Arial, Helvetica, sans-serif; font-size: 11pt; }';
        $html .= 'th { background-color: #00FF00; color: #000000; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #000000; height: 36px; padding: 6px 12px; }';
        $html .= 'td { border: 1px solid #000000; vertical-align: middle; color: #000000; padding: 6px 10px; }';
        $html .= '</style>';
        $html .= '</head>';
        $html .= '<body>';
        $html .= '<table border="1" cellpadding="6" cellspacing="0" style="border-collapse: collapse; border: 1px solid #000000; font-family: Arial, sans-serif; font-size: 11pt;">';
        $html .= '<thead>';
        $html .= '<tr style="background-color: #00FF00; height: 36px;">';
        $html .= '<th style="background-color: #00FF00; color: #000000; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #000000; width: 110px;">NIM</th>';
        $html .= '<th style="background-color: #00FF00; color: #000000; font-weight: bold; text-align: left; vertical-align: middle; border: 1px solid #000000; min-width: 220px;">Nama Mahasiswa</th>';
        $html .= '<th style="background-color: #00FF00; color: #000000; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #000000; width: 90px;">Angkatan</th>';
        $html .= '<th style="background-color: #00FF00; color: #000000; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #000000; min-width: 180px;">Program / Kelas</th>';
        $html .= '<th style="background-color: #00FF00; color: #000000; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #000000; min-width: 160px;">SPP per Bulan (Rp)</th>';
        $html .= '<th style="background-color: #00FF00; color: #000000; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #000000; min-width: 140px;">No Hp/Wa</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';

        foreach ($users as $user) {
            $nim = !empty($user->nim) ? (string) $user->nim : '-';
            $prodi = $user->education['program_studi'] ?? 'S1';
            $semester = $user->semester ? 'Sems ' . $user->semester : 'Sems -';
            $programKelas = "{$prodi} / {$semester}";
            $nominal = (float) ($user->nominal_spp ?? 300000);
            $sppFormatted = 'Rp' . number_format($nominal, 0, '', '.');
            $phone = !empty($user->phone) ? (string) $user->phone : '-';

            $html .= '<tr style="background-color: #FFFFFF; height: 30px;">';
            $html .= '<td style="border: 1px solid #000000; text-align: center; vertical-align: middle; background-color: #FFFFFF; mso-number-format:\'@\'; font-weight: bold;">' . htmlspecialchars($nim, ENT_QUOTES, 'UTF-8') . '</td>';
            $html .= '<td style="border: 1px solid #000000; text-align: left; vertical-align: middle; background-color: #FFFFFF; font-weight: 500;">' . htmlspecialchars($user->name, ENT_QUOTES, 'UTF-8') . '</td>';
            $html .= '<td style="border: 1px solid #000000; text-align: center; vertical-align: middle; background-color: #FFFFFF;">' . htmlspecialchars($user->angkatan ?? '-', ENT_QUOTES, 'UTF-8') . '</td>';
            $html .= '<td style="border: 1px solid #000000; text-align: center; vertical-align: middle; background-color: #FFFFFF;">' . htmlspecialchars($programKelas, ENT_QUOTES, 'UTF-8') . '</td>';
            $html .= '<td style="border: 1px solid #000000; text-align: center; vertical-align: middle; background-color: #FFFF00; font-weight: bold; color: #000000;">' . $sppFormatted . '</td>';
            $html .= '<td style="border: 1px solid #000000; text-align: center; vertical-align: middle; background-color: #FFFFFF; mso-number-format:\'@\';">' . htmlspecialchars($phone, ENT_QUOTES, 'UTF-8') . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</body>';
        $html .= '</html>';

        return response($html, 200, $headers);
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

        // Dukungan pratinjau mode html jika diminta (?mode=html)
        if ($request->query('mode') === 'html') {
            return view('backend.keuangan.mahasiswa.print', compact(
                'users',
                'totalMahasiswa',
                'totalAktif',
                'totalPotensiSpp'
            ));
        }

        // Render PDF menggunakan DomPDF A4 Landscape
        $pdf = Pdf::loadView('backend.keuangan.mahasiswa.print', compact(
            'users',
            'totalMahasiswa',
            'totalAktif',
            'totalPotensiSpp'
        ))->setPaper('a4', 'landscape');

        $filename = 'Rekap_Data_Mahasiswa_SPP_' . date('Ymd_His') . '.pdf';

        if ($request->boolean('download')) {
            return $pdf->download($filename);
        }

        return $pdf->stream($filename);
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
