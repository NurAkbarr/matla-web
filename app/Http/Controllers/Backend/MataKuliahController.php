<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\MataKuliah::with('programStudi');

        if ($request->filled('prodi')) {
            $query->where('program_studi_id', $request->prodi);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('kode', 'like', '%' . $request->search . '%');
            });
        }

        $mataKuliahs = $query->latest()->paginate(15);
        $programStudis = \App\Models\ProgramStudi::active()->get();

        return view('backend.admin.mata_kuliah.index', compact('mataKuliahs', 'programStudis'));
    }

    public function create()
    {
        $programStudis = \App\Models\ProgramStudi::active()->get();
        return view('backend.admin.mata_kuliah.create', compact('programStudis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'program_studi_id' => 'required|exists:program_studis,id',
            'kode' => 'required|unique:mata_kuliahs,kode',
            'nama' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:8',
            'semester' => 'required|integer|min:1|max:14',
        ]);

        \App\Models\MataKuliah::create($validated);

        return redirect()->route('backend.admin.mata-kuliah.index')->with('success', 'Mata Kuliah berhasil ditambahkan.');
    }

    public function edit(\App\Models\MataKuliah $mataKuliah)
    {
        $programStudis = \App\Models\ProgramStudi::active()->get();
        return view('backend.admin.mata_kuliah.edit', compact('mataKuliah', 'programStudis'));
    }

    public function update(Request $request, \App\Models\MataKuliah $mataKuliah)
    {
        $validated = $request->validate([
            'program_studi_id' => 'required|exists:program_studis,id',
            'kode' => 'required|unique:mata_kuliahs,kode,' . $mataKuliah->id,
            'nama' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:8',
            'semester' => 'required|integer|min:1|max:14',
        ]);

        $mataKuliah->update($validated);

        return redirect()->route('backend.admin.mata-kuliah.index')->with('success', 'Mata Kuliah berhasil diperbarui.');
    }

    public function destroy(\App\Models\MataKuliah $mataKuliah)
    {
        $mataKuliah->delete();
        return redirect()->route('backend.admin.mata-kuliah.index')->with('success', 'Mata Kuliah berhasil dihapus.');
    }
}
