<?php

namespace App\Http\Controllers\Backend\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\MasterSemester;
use App\Models\MasterTahunAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterTahunAkademikController extends Controller
{
    /**
     * Halaman kelola Master Tahun Akademik & Semester
     */
    public function index(Request $request)
    {
        $tahunAkademiks = MasterTahunAkademik::with('semesters')->orderBy('tahun', 'desc')->get();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $tahunAkademiks,
            ]);
        }

        return view('backend.keuangan.master-tahun-akademik.index', compact('tahunAkademiks'));
    }

    /**
     * Simpan Tahun Akademik baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'tahun' => 'required|string|max:50|unique:master_tahun_akademik,tahun',
            'is_active' => 'nullable|boolean',
        ], [
            'tahun.required' => 'Tahun akademik wajib diisi (misal: 2026/2027).',
            'tahun.unique' => 'Tahun akademik tersebut sudah terdaftar.',
        ]);

        $isActive = $request->boolean('is_active');

        DB::transaction(function () use ($request, $isActive) {
            if ($isActive) {
                MasterTahunAkademik::query()->update(['is_active' => false]);
            }

            $ta = MasterTahunAkademik::create([
                'tahun' => trim($request->tahun),
                'is_active' => $isActive,
            ]);

            // Buat otomatis 2 semester default: Ganjil & Genap
            $ta->semesters()->createMany([
                [
                    'nama_semester' => 'Ganjil (Jul-Des)',
                    'tipe' => 'ganjil',
                    'bulan_mulai' => 7,
                    'bulan_selesai' => 12,
                    'is_active' => $isActive, // jika TA aktif, jadikan ganjil aktif
                ],
                [
                    'nama_semester' => 'Genap (Jan-Jun)',
                    'tipe' => 'genap',
                    'bulan_mulai' => 1,
                    'bulan_selesai' => 6,
                    'is_active' => false,
                ],
            ]);
        });

        return back()->with('success', 'Tahun akademik dan semester default berhasil ditambahkan.');
    }

    /**
     * Perbarui Tahun Akademik
     */
    public function update(Request $request, $id)
    {
        $ta = MasterTahunAkademik::findOrFail($id);

        $request->validate([
            'tahun' => 'required|string|max:50|unique:master_tahun_akademik,tahun,' . $id,
            'is_active' => 'nullable|boolean',
        ]);

        $isActive = $request->boolean('is_active');

        DB::transaction(function () use ($ta, $request, $isActive) {
            if ($isActive) {
                MasterTahunAkademik::where('id', '!=', $ta->id)->update(['is_active' => false]);
            }

            $ta->update([
                'tahun' => trim($request->tahun),
                'is_active' => $isActive,
            ]);
        });

        return back()->with('success', 'Tahun akademik berhasil diperbarui.');
    }

    /**
     * Hapus Tahun Akademik
     */
    public function destroy($id)
    {
        $ta = MasterTahunAkademik::findOrFail($id);
        $tahun = $ta->tahun;
        $ta->delete();

        // Jika tidak ada yang aktif, aktifkan yang terbaru
        if (!MasterTahunAkademik::where('is_active', true)->exists()) {
            $latest = MasterTahunAkademik::latest()->first();
            if ($latest) {
                $latest->update(['is_active' => true]);
                $firstSemester = $latest->semesters()->first();
                if ($firstSemester) {
                    $firstSemester->update(['is_active' => true]);
                }
            }
        }

        return back()->with('success', "Tahun akademik {$tahun} beserta semesternya berhasil dihapus.");
    }

    /**
     * Jadikan Tahun Akademik Aktif
     */
    public function setActive($id)
    {
        DB::transaction(function () use ($id) {
            MasterTahunAkademik::query()->update(['is_active' => false]);
            $ta = MasterTahunAkademik::findOrFail($id);
            $ta->update(['is_active' => true]);

            // Jika belum ada semester aktif di TA ini, aktifkan semester pertama
            if (!$ta->semesters()->where('is_active', true)->exists()) {
                MasterSemester::query()->update(['is_active' => false]);
                $first = $ta->semesters()->first();
                if ($first) {
                    $first->update(['is_active' => true]);
                }
            }
        });

        return back()->with('success', 'Tahun akademik aktif berhasil diubah.');
    }

    /**
     * Simpan Semester baru
     */
    public function storeSemester(Request $request, $tahunAkademikId)
    {
        $ta = MasterTahunAkademik::findOrFail($tahunAkademikId);

        $request->validate([
            'nama_semester' => 'required|string|max:100',
            'tipe' => 'required|in:ganjil,genap',
            'bulan_mulai' => 'required|integer|min:1|max:12',
            'bulan_selesai' => 'required|integer|min:1|max:12',
            'is_active' => 'nullable|boolean',
        ]);

        $isActive = $request->boolean('is_active');

        DB::transaction(function () use ($ta, $request, $isActive) {
            if ($isActive) {
                MasterSemester::query()->update(['is_active' => false]);
                MasterTahunAkademik::query()->update(['is_active' => false]);
                $ta->update(['is_active' => true]);
            }

            $ta->semesters()->create([
                'nama_semester' => trim($request->nama_semester),
                'tipe' => $request->tipe,
                'bulan_mulai' => (int)$request->bulan_mulai,
                'bulan_selesai' => (int)$request->bulan_selesai,
                'is_active' => $isActive,
            ]);
        });

        return back()->with('success', 'Semester berhasil ditambahkan.');
    }

    /**
     * Perbarui Semester
     */
    public function updateSemester(Request $request, $id)
    {
        $semester = MasterSemester::findOrFail($id);

        $request->validate([
            'nama_semester' => 'required|string|max:100',
            'tipe' => 'required|in:ganjil,genap',
            'bulan_mulai' => 'required|integer|min:1|max:12',
            'bulan_selesai' => 'required|integer|min:1|max:12',
            'is_active' => 'nullable|boolean',
        ]);

        $isActive = $request->boolean('is_active');

        DB::transaction(function () use ($semester, $request, $isActive) {
            if ($isActive) {
                MasterSemester::where('id', '!=', $semester->id)->update(['is_active' => false]);
                MasterTahunAkademik::query()->update(['is_active' => false]);
                $semester->tahunAkademik()->update(['is_active' => true]);
            }

            $semester->update([
                'nama_semester' => trim($request->nama_semester),
                'tipe' => $request->tipe,
                'bulan_mulai' => (int)$request->bulan_mulai,
                'bulan_selesai' => (int)$request->bulan_selesai,
                'is_active' => $isActive,
            ]);
        });

        return back()->with('success', 'Semester berhasil diperbarui.');
    }

    /**
     * Hapus Semester
     */
    public function destroySemester($id)
    {
        $semester = MasterSemester::findOrFail($id);
        $nama = $semester->nama_semester;
        $semester->delete();

        return back()->with('success', "Semester {$nama} berhasil dihapus.");
    }

    /**
     * Jadikan Semester Aktif
     */
    public function setActiveSemester($id)
    {
        DB::transaction(function () use ($id) {
            MasterSemester::query()->update(['is_active' => false]);
            $semester = MasterSemester::findOrFail($id);
            $semester->update(['is_active' => true]);

            // Pastikan tahun akademiknya juga aktif
            MasterTahunAkademik::query()->update(['is_active' => false]);
            $semester->tahunAkademik()->update(['is_active' => true]);
        });

        return back()->with('success', 'Semester aktif berhasil diubah.');
    }
}
