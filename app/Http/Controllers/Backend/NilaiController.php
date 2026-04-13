<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Nilai;
use App\Models\NilaiLog;
use App\Models\NilaiKomponen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NilaiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $jadwals = $user->taughtSchedules()->with(['programStudi'])->latest()->get();
        return view('backend.dosen.nilai.index', compact('jadwals'));
    }

    public function input(Jadwal $jadwal)
    {
        if ($jadwal->dosen_id !== Auth::id()) {
            abort(403);
        }

        $participants = $jadwal->participants()->get();
        $nilais = Nilai::where('jadwal_id', $jadwal->id)->get()->keyBy('mahasiswa_id');
        $komponens = NilaiKomponen::where('jadwal_id', $jadwal->id)->get();

        return view('backend.dosen.nilai.input', compact('jadwal', 'participants', 'nilais', 'komponens'));
    }

    public function updateSettings(Request $request, Jadwal $jadwal)
    {
        if ($jadwal->dosen_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'komponens' => 'required|array|min:1',
            'komponens.*.nama' => 'required|string|max:255',
            'komponens.*.bobot' => 'required|integer|min:0|max:100',
        ]);

        $komponens = $request->input('komponens');
        $totalBobot = array_sum(array_column($komponens, 'bobot'));

        if ($totalBobot !== 100) {
            return redirect()->back()->with('error', 'Total bobot harus 100% (saat ini: ' . $totalBobot . '%)');
        }

        DB::transaction(function () use ($jadwal, $komponens) {
            // Simple approach: delete and recreate components
            // WARNING: This might invalidate existing scores if IDs change, 
            // but in dynamic JSON approach, we can use names or map them.
            // Better: use IDs if provided, or recreate.
            
            $jadwal->nilaiKomponens()->delete();
            foreach ($komponens as $comp) {
                NilaiKomponen::create([
                    'jadwal_id' => $jadwal->id,
                    'nama_komponen' => $comp['nama'],
                    'bobot' => $comp['bobot'],
                ]);
            }
        });

        return redirect()->back()->with('success', 'Pengaturan bobot berhasil diperbarui.');
    }

    public function store(Request $request, Jadwal $jadwal)
    {
        if ($jadwal->dosen_id !== Auth::id()) {
            abort(403);
        }

        $scores = $request->input('scores', []);
        
        DB::transaction(function () use ($jadwal, $scores) {
            foreach ($scores as $mhsId => $data) {
                $nilai = Nilai::firstOrNew([
                    'jadwal_id' => $jadwal->id,
                    'mahasiswa_id' => $mhsId,
                ]);

                if ($nilai->is_locked) continue;

                $oldSkor = $nilai->data_skor;
                
                // $data here is expected to be [komponen_id => score, ...]
                $nilai->data_skor = $data;
                $nilai->calculateGrade();
                $nilai->save();

                // Log if changed
                if ($oldSkor != $nilai->data_skor) {
                    NilaiLog::create([
                        'nilai_id' => $nilai->id,
                        'user_id' => Auth::id(),
                        'old_values' => ['data_skor' => $oldSkor],
                        'new_values' => [
                            'data_skor' => $nilai->data_skor,
                            'total_angka' => $nilai->total_angka,
                            'nilai_huruf' => $nilai->nilai_huruf
                        ],
                        'action' => $oldSkor ? 'updated' : 'created',
                    ]);
                }
            }
        });

        return redirect()->back()->with('success', 'Nilai berhasil disimpan sementara.');
    }

    public function lock(Jadwal $jadwal)
    {
        if ($jadwal->dosen_id !== Auth::id()) {
            abort(403);
        }

        Nilai::where('jadwal_id', $jadwal->id)->update([
            'is_locked' => true,
            'is_published' => true
        ]);

        return redirect()->back()->with('success', 'Nilai berhasil dikunci dan dipublikasikan.');
    }

    public function unlock(Jadwal $jadwal)
    {
        // Only Admin or Super Admin can unlock
        if (!in_array(Auth::user()->role, ['admin', 'super_admin'])) {
            abort(403);
        }

        DB::transaction(function () use ($jadwal) {
            $nilais = Nilai::where('jadwal_id', $jadwal->id)->get();
            
            foreach ($nilais as $nilai) {
                if ($nilai->is_locked) {
                    $nilai->update([
                        'is_locked' => false,
                        'is_published' => false
                    ]);

                    NilaiLog::create([
                        'nilai_id' => $nilai->id,
                        'user_id' => Auth::id(),
                        'action' => 'unlocked',
                        'old_values' => ['is_locked' => true],
                        'new_values' => ['is_locked' => false],
                    ]);
                }
            }
        });

        return redirect()->back()->with('success', 'Nilai untuk mata kuliah ' . $jadwal->mata_kuliah . ' berhasil dibuka kuncinya.');
    }

    public function history(Jadwal $jadwal)
    {
        if ($jadwal->dosen_id !== Auth::id()) {
            abort(403);
        }

        $logs = NilaiLog::whereHas('nilai', function($q) use ($jadwal) {
            $q->where('jadwal_id', $jadwal->id);
        })->with(['nilai.mahasiswa', 'user'])->latest()->get();

        return view('backend.dosen.nilai.history', compact('jadwal', 'logs'));
    }

    public function export(Jadwal $jadwal)
    {
        if ($jadwal->dosen_id !== Auth::id()) {
            abort(403);
        }

        $nilais = Nilai::where('jadwal_id', $jadwal->id)->with('mahasiswa')->get();
        $komponens = NilaiKomponen::where('jadwal_id', $jadwal->id)->get();
        
        $filename = "Nilai_" . str_replace(' ', '_', $jadwal->mata_kuliah) . "_" . date('Y-m-d') . ".csv";
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        // Dynamic columns
        $columns = ['NIM', 'Nama Mahasiswa'];
        foreach ($komponens as $comp) {
            $columns[] = $comp->nama_komponen . ' (' . $comp->bobot . '%)';
        }
        $columns[] = 'Total';
        $columns[] = 'Huruf';

        $callback = function() use($nilais, $komponens, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($nilais as $n) {
                $row = [$n->mahasiswa->email, $n->mahasiswa->name];
                $dataSkor = $n->data_skor ?? [];
                
                foreach ($komponens as $comp) {
                    $row[] = $dataSkor[$comp->id] ?? 0;
                }
                
                $row[] = $n->total_angka;
                $row[] = $n->nilai_huruf;
                
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
