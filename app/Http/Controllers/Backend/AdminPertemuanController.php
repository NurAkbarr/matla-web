<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Pertemuan;
use Illuminate\Http\Request;

class AdminPertemuanController extends Controller
{
    public function index(Jadwal $jadwal)
    {
        $pertemuans = $jadwal->pertemuans;
        return view('backend.admin.pertemuan.index', compact('jadwal', 'pertemuans'));
    }

    public function create(Jadwal $jadwal)
    {
        return view('backend.admin.pertemuan.create', compact('jadwal'));
    }

    public function store(Request $request, Jadwal $jadwal)
    {
        $request->validate([
            'pertemuan_ke' => 'required|integer|min:1|max:16',
            'judul_materi' => 'required|string|max:255',
            'tipe_pertemuan' => 'required|in:video,zoom',
            'link_url' => 'nullable|url',
            'deskripsi' => 'nullable|string',
            'soal_evaluasi' => 'nullable|string|required_if:tipe_pertemuan,video',
        ]);

        $jadwal->pertemuans()->create($request->all());

        return redirect()->route('backend.admin.jadwal.pertemuan.index', $jadwal->id)
            ->with('success', 'Pertemuan berhasil ditambahkan.');
    }

    public function edit(Jadwal $jadwal, Pertemuan $pertemuan)
    {
        return view('backend.admin.pertemuan.edit', compact('jadwal', 'pertemuan'));
    }

    public function update(Request $request, Jadwal $jadwal, Pertemuan $pertemuan)
    {
        $request->validate([
            'pertemuan_ke' => 'required|integer|min:1|max:16',
            'judul_materi' => 'required|string|max:255',
            'tipe_pertemuan' => 'required|in:video,zoom',
            'link_url' => 'nullable|url',
            'deskripsi' => 'nullable|string',
            'soal_evaluasi' => 'nullable|string|required_if:tipe_pertemuan,video',
        ]);

        $pertemuan->update($request->all());

        return redirect()->route('backend.admin.jadwal.pertemuan.index', $jadwal->id)
            ->with('success', 'Pertemuan berhasil diperbarui.');
    }

    public function destroy(Jadwal $jadwal, Pertemuan $pertemuan)
    {
        $pertemuan->delete();

        return redirect()->route('backend.admin.jadwal.pertemuan.index', $jadwal->id)
            ->with('success', 'Pertemuan berhasil dihapus.');
    }
}
