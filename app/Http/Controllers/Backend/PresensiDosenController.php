<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PresensiDosen;
use App\Models\Jadwal;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Exports\RekapHonorExport;
use Maatwebsite\Excel\Facades\Excel;

class PresensiDosenController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', date('Y-m')); // Format: YYYY-MM
        $user = Auth::user();

        $hariIndo = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];
        $hariIni = $hariIndo[date('l')];

        $presensis = PresensiDosen::where('user_id', $user->id)
            ->where('bulan', $bulan)
            ->get();

        $jadwals = Jadwal::where('dosen_id', $user->id)->get();

        $rows = [];
        foreach ($jadwals as $jadwal) {
            // Cari data presensi yang sudah tersimpan bulan ini untuk jadwal tersebut
            $p = $presensis->where('mata_kuliah', $jadwal->mata_kuliah)
                           ->where('semester', $jadwal->semester)
                           ->first();

            $rows[] = [
                'id' => $p ? $p->id : null,
                'mata_kuliah' => $jadwal->mata_kuliah,
                'semester' => $jadwal->semester,
                'angkatan' => $jadwal->angkatan ?? '-', // Gunakan angkatan dari Jadwal
                'pekan_1' => $p ? $p->pekan_1 : '',
                'pekan_2' => $p ? $p->pekan_2 : '',
                'pekan_3' => $p ? $p->pekan_3 : '',
                'pekan_4' => $p ? $p->pekan_4 : '',
            ];
        }

        $honorPerPertemuan = Setting::get_value('honor_per_pertemuan', 75000);
        
        $totalHadir = 0;
        foreach ($rows as $row) {
            if ($row['pekan_1'] == 'Hadir') $totalHadir++;
            if ($row['pekan_2'] == 'Hadir') $totalHadir++;
            if ($row['pekan_3'] == 'Hadir') $totalHadir++;
            if ($row['pekan_4'] == 'Hadir') $totalHadir++;
        }
        
        $estimasiHonor = $totalHadir * $honorPerPertemuan;

        return view('backend.dosen.presensi.index', compact('rows', 'bulan', 'totalHadir', 'estimasiHonor', 'honorPerPertemuan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bulan' => 'required|date_format:Y-m',
            'rows' => 'nullable|array',
            'rows.*.semester' => 'required',
            'rows.*.angkatan' => 'required',
            'rows.*.mata_kuliah' => 'required',
            'rows.*.pekan_1' => 'nullable|in:Hadir,Izin,Sakit,Alfa',
            'rows.*.pekan_2' => 'nullable|in:Hadir,Izin,Sakit,Alfa',
            'rows.*.pekan_3' => 'nullable|in:Hadir,Izin,Sakit,Alfa',
            'rows.*.pekan_4' => 'nullable|in:Hadir,Izin,Sakit,Alfa',
        ]);

        $userId = Auth::id();
        $bulan = $request->bulan;

        // Ambil ID yang dikirim untuk hapus yang tidak ada (jika user menghapus baris)
        $submittedIds = [];

        if ($request->has('rows')) {
            foreach ($request->rows as $row) {
                // Cari apakah baris ini sudah ada, atau buat baru
                $presensi = PresensiDosen::updateOrCreate(
                    [
                        'user_id' => $userId,
                        'bulan' => $bulan,
                        'semester' => $row['semester'],
                        'angkatan' => $row['angkatan'],
                        'mata_kuliah' => $row['mata_kuliah'],
                    ],
                    [
                        'pekan_1' => $row['pekan_1'] ?? null,
                        'pekan_2' => $row['pekan_2'] ?? null,
                        'pekan_3' => $row['pekan_3'] ?? null,
                        'pekan_4' => $row['pekan_4'] ?? null,
                    ]
                );
                $submittedIds[] = $presensi->id;
            }
        }

        // Hapus baris yang ada di database untuk bulan ini tapi tidak disubmit (dihapus oleh user)
        PresensiDosen::where('user_id', $userId)
            ->where('bulan', $bulan)
            ->whereNotIn('id', $submittedIds)
            ->delete();

        return back()->with('success', 'Data absensi bulanan berhasil disimpan!');
    }

    public function destroy($id)
    {
        $presensi = PresensiDosen::findOrFail($id);
        
        if ($presensi->user_id !== Auth::id()) {
            abort(403);
        }

        $presensi->delete();

        return back()->with('success', 'Baris absensi berhasil dihapus!');
    }

    public function adminIndex(Request $request)
    {
        $bulan = $request->get('bulan', date('Y-m'));
        $honorPerPertemuan = Setting::get_value('honor_per_pertemuan', 75000);

        $dosens = User::where('role', 'dosen')
            ->with(['presensiDosens' => function($q) use ($bulan) {
                $q->where('bulan', $bulan)
                  ->orderBy('semester', 'asc')
                  ->orderBy('angkatan', 'asc');
            }])
            ->get();

        $grandTotalSeluruhHonor = 0;

        foreach ($dosens as $dosen) {
            $totalHadirDosen = 0;
            $jadwals = Jadwal::where('dosen_id', $dosen->id)->get();

            foreach ($dosen->presensiDosens as $p) {
                // Selalu update angkatan secara real-time dari Jadwal
                $matchingJadwal = $jadwals->where('mata_kuliah', $p->mata_kuliah)->where('semester', $p->semester)->first();
                if ($matchingJadwal && $matchingJadwal->angkatan != $p->angkatan) {
                    $p->angkatan = $matchingJadwal->angkatan;
                    $p->save(); // Sinkronkan ke database agar konsisten
                }

                $p->totalHadirBaris = 0;
                if ($p->pekan_1 == 'Hadir') $p->totalHadirBaris++;
                if ($p->pekan_2 == 'Hadir') $p->totalHadirBaris++;
                if ($p->pekan_3 == 'Hadir') $p->totalHadirBaris++;
                if ($p->pekan_4 == 'Hadir') $p->totalHadirBaris++;

                $p->totalHonorBaris = $p->totalHadirBaris * $honorPerPertemuan;
                $totalHadirDosen += $p->totalHadirBaris;
            }
            $dosen->total_honor = $totalHadirDosen * $honorPerPertemuan;
            $grandTotalSeluruhHonor += $dosen->total_honor;
        }

        return view('backend.admin.presensi.rekap', compact('dosens', 'bulan', 'honorPerPertemuan', 'grandTotalSeluruhHonor'));
    }

    public function exportExcel(Request $request)
    {
        $bulan = $request->get('bulan', date('Y-m'));
        $bulanLabel = Carbon::parse($bulan . '-01')->format('Y-m');
        $filename = 'Rekap_Honor_Dosen_' . $bulanLabel . '.xlsx';

        return Excel::download(new RekapHonorExport($bulan), $filename);
    }
}
