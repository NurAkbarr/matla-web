<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Pertemuan;
use App\Models\LogTontonan;
use App\Models\JawabanEvaluasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ElearningController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Get schedules the student is enrolled in
        $jadwals = $user->enrolledSchedules()->with('programStudi', 'dosen')->get();
        return view('mahasiswa.elearning.index', compact('jadwals'));
    }

    public function showJadwal(Jadwal $jadwal)
    {
        $user = Auth::user();
        // Ensure student is enrolled
        if (!$jadwal->participants()->where('mahasiswa_id', $user->id)->exists()) {
            abort(403, 'Anda tidak terdaftar di mata kuliah ini.');
        }

        // Get all meetings for this schedule
        $pertemuans = $jadwal->pertemuans()
            ->with(['logTontonans' => function($q) use ($user) {
                $q->where('mahasiswa_id', $user->id);
            }, 'jawabanEvaluasis' => function($q) use ($user) {
                $q->where('mahasiswa_id', $user->id);
            }])
            ->orderBy('pertemuan_ke', 'asc')
            ->get();

        return view('mahasiswa.elearning.jadwal', compact('jadwal', 'pertemuans'));
    }

    public function showPertemuan(Pertemuan $pertemuan)
    {
        $user = Auth::user();
        $jadwal = $pertemuan->jadwal;

        if (!$jadwal->participants()->where('mahasiswa_id', $user->id)->exists()) {
            abort(403, 'Anda tidak terdaftar di mata kuliah ini.');
        }

        $log = LogTontonan::firstOrCreate(
            ['mahasiswa_id' => $user->id, 'pertemuan_id' => $pertemuan->id],
            ['detik_ditonton' => 0, 'is_lulus_nonton' => false]
        );

        $evaluasi = JawabanEvaluasi::where('mahasiswa_id', $user->id)
            ->where('pertemuan_id', $pertemuan->id)
            ->first();

        // Extract YouTube Video ID if it's a video
        $youtubeId = null;
        if ($pertemuan->tipe_pertemuan == 'video' && $pertemuan->link_url) {
            preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user|shorts)\/))([^\?&\"'>]+)/", $pertemuan->link_url, $matches);
            if (isset($matches[1])) {
                $youtubeId = $matches[1];
            }
        }

        return view('mahasiswa.elearning.pertemuan', compact('pertemuan', 'jadwal', 'log', 'evaluasi', 'youtubeId'));
    }

    public function updateLogTontonan(Request $request, Pertemuan $pertemuan)
    {
        $user = Auth::user();
        
        $request->validate([
            'detik_ditonton' => 'required|integer|min:0',
            'is_lulus_nonton' => 'required|boolean'
        ]);

        $log = LogTontonan::where('mahasiswa_id', $user->id)
            ->where('pertemuan_id', $pertemuan->id)
            ->first();

        if ($log) {
            // Only update if the new watch time is greater
            if ($request->detik_ditonton > $log->detik_ditonton) {
                $log->detik_ditonton = $request->detik_ditonton;
            }
            
            // Once passed, it stays passed
            if ($request->is_lulus_nonton) {
                $log->is_lulus_nonton = true;
            }
            
            $log->save();
        }

        return response()->json(['success' => true, 'log' => $log]);
    }

    public function submitEvaluasi(Request $request, Pertemuan $pertemuan)
    {
        $user = Auth::user();

        // Validate they have watched the video
        $log = LogTontonan::where('mahasiswa_id', $user->id)
            ->where('pertemuan_id', $pertemuan->id)
            ->first();

        if ($pertemuan->tipe_pertemuan == 'video' && (!$log || !$log->is_lulus_nonton)) {
            return back()->with('error', 'Anda harus menonton minimal 50% durasi video sebelum bisa mengisi evaluasi.');
        }

        $request->validate([
            'jawaban' => 'required|string|min:10',
        ]);

        JawabanEvaluasi::updateOrCreate(
            ['mahasiswa_id' => $user->id, 'pertemuan_id' => $pertemuan->id],
            ['jawaban' => $request->jawaban]
        );

        return back()->with('success', 'Evaluasi berhasil dikirim. Terima kasih telah mengikuti pertemuan ini.');
    }
}
