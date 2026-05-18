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

        $availableStudents = collect();
        $allGroups = collect();

        if (auth()->check() && auth()->user()->role === 'super_admin') {
            $allGroups = ClassGroup::where('id', '!=', $group->id)->orderBy('name')->get();
            
            $activeGroups = ClassGroup::with('prodi')->get();
            $availableQuery = \App\Models\User::where('role', 'mahasiswa');
            
            foreach ($activeGroups as $g) {
                $prodiName = $g->prodi ? $g->prodi->nama : null;
                if ($prodiName && $g->angkatan) {
                    $availableQuery->where(function($q) use ($g, $prodiName) {
                        $q->where('angkatan', '!=', $g->angkatan)
                          ->orWhere('education->program_studi', '!=', $prodiName)
                          ->orWhereNull('angkatan')
                          ->orWhereNull('education');
                    });
                }
            }
            $availableStudents = $availableQuery->orderBy('name')->get();
        }

        return view('backend.class-groups.show', compact('group', 'students', 'availableStudents', 'allGroups'));
    }

    /**
     * Add a student to this class group.
     */
    public function addStudent(Request $request, ClassGroup $group)
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'student_id' => 'required|exists:users,id',
        ]);

        $student = \App\Models\User::findOrFail($request->student_id);

        if ($student->role !== 'mahasiswa') {
            return back()->with('error', 'User yang dipilih bukan merupakan mahasiswa.');
        }

        $education = $student->education ?? [];
        $education['program_studi'] = $group->prodi ? $group->prodi->nama : '';
        $student->education = $education;
        $student->angkatan = $group->angkatan;
        $student->save();

        return back()->with('success', "Mahasiswa {$student->name} berhasil ditambahkan ke kelas {$group->name}!");
    }

    /**
     * Move a student to another class group.
     */
    public function moveStudent(Request $request, ClassGroup $group)
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'student_id' => 'required|exists:users,id',
            'target_class_group_id' => 'required|exists:class_groups,id',
        ]);

        $student = \App\Models\User::findOrFail($request->student_id);
        $targetGroup = ClassGroup::findOrFail($request->target_class_group_id);

        if ($student->role !== 'mahasiswa') {
            return back()->with('error', 'User yang dipilih bukan merupakan mahasiswa.');
        }

        $education = $student->education ?? [];
        $education['program_studi'] = $targetGroup->prodi ? $targetGroup->prodi->nama : '';
        $student->education = $education;
        $student->angkatan = $targetGroup->angkatan;
        $student->save();

        return back()->with('success', "Mahasiswa {$student->name} berhasil dipindahkan ke kelas {$targetGroup->name}!");
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
