<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\CutiRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CutiRequestController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cutiRequests = CutiRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mahasiswa.cuti.index', compact('cutiRequests', 'user'));
    }

    public function create()
    {
        $user = Auth::user();
        
        // Cek jika masih ada cuti pending
        $hasPending = CutiRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->exists();

        if ($hasPending) {
            return redirect()->route('backend.mahasiswa.cuti.index')
                ->with('error', 'Anda masih memiliki pengajuan cuti yang sedang diproses.');
        }

        return view('mahasiswa.cuti.create', compact('user'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $currentSemester = $user->semester ?? 1;

        $request->validate([
            'semester_diajukan' => ['required', 'numeric', 'min:' . $currentSemester],
            'alasan' => ['required', 'string', 'min:10'],
            'pernyataan_persetujuan' => ['accepted'],
        ], [
            'semester_diajukan.required' => 'Semester harus diisi.',
            'semester_diajukan.numeric' => 'Semester harus berupa angka (contoh: 3).',
            'semester_diajukan.min' => "Tidak dapat mengajukan cuti untuk semester yang sudah terlewat. Semester Anda saat ini: {$currentSemester}.",
            'alasan.required' => 'Alasan cuti harus diisi.',
            'alasan.min' => 'Alasan cuti harus berisi minimal 10 karakter.',
            'pernyataan_persetujuan.accepted' => 'Anda harus menyetujui pernyataan pengajuan cuti.',
        ]);

        CutiRequest::create([
            'user_id' => Auth::id(),
            'semester_diajukan' => trim($request->semester_diajukan),
            'alasan' => trim($request->alasan),
            'pernyataan_persetujuan' => true,
            'status' => 'pending',
        ]);

        return redirect()->route('backend.mahasiswa.cuti.index')
            ->with('success', 'Pengajuan cuti berhasil dikirim dan sedang menunggu persetujuan Admin.');
    }
}
