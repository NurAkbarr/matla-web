<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;

class ProgramStudiController extends Controller
{
    public function index()
    {
        $programStudis = ProgramStudi::orderBy('urutan')->orderBy('nama')->get();
        return view('backend.admin.program-studi.index', compact('programStudis'));
    }

    public function create()
    {
        return view('backend.admin.program-studi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'       => ['required', 'string', 'max:255'],
            'singkatan'  => ['required', 'string', 'max:20'],
            'jenjang'    => ['required', 'in:D3,D4,S1,S2,S3,Profesi'],
            'deskripsi'  => ['nullable', 'string'],
            'icon'       => ['nullable', 'string', 'max:10'],
            'akreditasi' => ['nullable', 'string', 'max:20'],
            'urutan'     => ['nullable', 'integer'],
        ]);

        ProgramStudi::create([
            'nama'       => $request->nama,
            'singkatan'  => strtoupper($request->singkatan),
            'jenjang'    => $request->jenjang,
            'deskripsi'  => $request->deskripsi,
            'icon'       => $request->icon ?? '🎓',
            'akreditasi' => $request->akreditasi ?? 'Baik',
            'is_active'  => $request->has('is_active'),
            'urutan'     => $request->urutan ?? 0,
        ]);

        return redirect()->route('backend.admin.program-studi.index')
            ->with('success', 'Program Studi berhasil ditambahkan.');
    }

    public function edit(ProgramStudi $programStudi)
    {
        return view('backend.admin.program-studi.edit', compact('programStudi'));
    }

    public function update(Request $request, ProgramStudi $programStudi)
    {
        $request->validate([
            'nama'       => ['required', 'string', 'max:255'],
            'singkatan'  => ['required', 'string', 'max:20'],
            'jenjang'    => ['required', 'in:D3,D4,S1,S2,S3,Profesi'],
            'deskripsi'  => ['nullable', 'string'],
            'icon'       => ['nullable', 'string', 'max:10'],
            'akreditasi' => ['nullable', 'string', 'max:20'],
            'urutan'     => ['nullable', 'integer'],
        ]);

        $programStudi->update([
            'nama'       => $request->nama,
            'singkatan'  => strtoupper($request->singkatan),
            'jenjang'    => $request->jenjang,
            'deskripsi'  => $request->deskripsi,
            'icon'       => $request->icon ?? '🎓',
            'akreditasi' => $request->akreditasi ?? 'Baik',
            'is_active'  => $request->has('is_active'),
            'urutan'     => $request->urutan ?? 0,
        ]);

        return redirect()->route('backend.admin.program-studi.index')
            ->with('success', 'Program Studi berhasil diperbarui.');
    }

    public function destroy(ProgramStudi $programStudi)
    {
        $programStudi->delete();
        return redirect()->route('backend.admin.program-studi.index')
            ->with('success', 'Program Studi berhasil dihapus.');
    }
}
