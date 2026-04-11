<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\User;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwals = Jadwal::with(['dosen', 'programStudi'])->latest()->get();
        return view('backend.admin.jadwal.index', compact('jadwals'));
    }

    public function create()
    {
        $dosens = User::where('role', 'dosen')->get();
        $programStudis = ProgramStudi::all();
        $haris = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        return view('backend.admin.jadwal.create', compact('dosens', 'programStudis', 'haris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mata_kuliah' => 'required|string|max:255',
            'dosen_id' => 'required|exists:users,id',
            'program_studi_id' => 'required|exists:program_studis,id',
            'hari' => 'required|string',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'ruang' => 'required|string',
            'semester' => 'required|integer|min:1',
        ]);

        Jadwal::create($request->all());

        return redirect()->route('backend.admin.jadwal.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit(Jadwal $jadwal)
    {
        $dosens = User::where('role', 'dosen')->get();
        $programStudis = ProgramStudi::all();
        $haris = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        return view('backend.admin.jadwal.edit', compact('jadwal', 'dosens', 'programStudis', 'haris'));
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        $request->validate([
            'mata_kuliah' => 'required|string|max:255',
            'dosen_id' => 'required|exists:users,id',
            'program_studi_id' => 'required|exists:program_studis,id',
            'hari' => 'required|string',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'ruang' => 'required|string',
            'semester' => 'required|integer|min:1',
        ]);

        $jadwal->update($request->all());

        return redirect()->route('backend.admin.jadwal.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Jadwal $jadwal)
    {
        $jadwal->delete();
        return redirect()->route('backend.admin.jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
    }
}
