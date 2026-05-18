<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ClassGroup;
use Illuminate\Http\Request;

class ClassGroupController extends Controller
{
    /**
     * Display a listing of the class groups.
     */
    public function index()
    {
        $groups = ClassGroup::orderBy('name')->paginate(15);
        return view('backend.class-groups.index', compact('groups'));
    }

    /**
     * Display the specified class group with its students.
     */
    public function show(ClassGroup $group)
    {
        // Load students that belong to the same prodi and angkatan
        $students = $group->students()->with(['profil'])->orderBy('name')->get();
        return view('backend.class-groups.show', compact('group', 'students'));
    }

    /**
     * Trigger manual synchronization of class groups.
     */
    public function sync()
    {
        \Illuminate\Support\Facades\Artisan::call('classgroup:sync');
        return redirect()->route('backend.admin.kelompok-kelas.index')
            ->with('success', 'Kelompok kelas berhasil disinkronkan berdasarkan data mahasiswa terbaru!');
    }
}
