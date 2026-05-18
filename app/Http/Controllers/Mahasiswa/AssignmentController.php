<?php
 
namespace App\Http\Controllers\Mahasiswa;
 
use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\ClassGroup;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
 
class AssignmentController extends Controller
{
    /**
     * Display listing of assignments for the logged-in student.
     */
    public function index()
    {
        $user = Auth::user();
        $prodiName = $user->education['program_studi'] ?? null;
        $angkatan = $user->angkatan;
 
        $classGroup = null;
        $assignments = collect();
 
        if ($prodiName && $angkatan) {
            $prodi = ProgramStudi::where('nama', $prodiName)->first();
            if (!$prodi) {
                $prodi = ProgramStudi::where('singkatan', $prodiName)->first();
            }
            
            if ($prodi) {
                $classGroup = ClassGroup::where('prodi_id', $prodi->id)
                    ->where('angkatan', $angkatan)
                    ->first();
            }
        }
 
        if ($classGroup) {
            $assignments = Assignment::where('class_group_id', $classGroup->id)
                ->with(['creator'])
                ->latest()
                ->get();
 
            // Fetch student submissions for these assignments
            $submissions = AssignmentSubmission::where('student_id', $user->id)
                ->whereIn('assignment_id', $assignments->pluck('id'))
                ->get()
                ->keyBy('assignment_id');
 
            foreach ($assignments as $assignment) {
                $assignment->submission = $submissions->get($assignment->id);
            }
        }
 
        return view('mahasiswa.assignments.index', compact('assignments', 'classGroup'));
    }
 
    /**
     * Show detail of an assignment and its submission form.
     */
    public function show(Assignment $assignment)
    {
        $user = Auth::user();
        
        // Security: Ensure this assignment is targeted to the student's class group
        $prodiName = $user->education['program_studi'] ?? null;
        $angkatan = $user->angkatan;
        $authorized = false;
 
        if ($prodiName && $angkatan) {
            $prodi = ProgramStudi::where('nama', $prodiName)->first();
            if (!$prodi) {
                $prodi = ProgramStudi::where('singkatan', $prodiName)->first();
            }
 
            if ($prodi) {
                $classGroup = ClassGroup::where('prodi_id', $prodi->id)
                    ->where('angkatan', $angkatan)
                    ->first();
 
                if ($classGroup && $assignment->class_group_id === $classGroup->id) {
                    $authorized = true;
                }
            }
        }
 
        if (!$authorized) {
            abort(403, 'Tugas ini tidak dialokasikan untuk kelas Anda.');
        }
 
        // Find existing submission
        $submission = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('student_id', $user->id)
            ->first();
 
        return view('mahasiswa.assignments.show', compact('assignment', 'submission'));
    }
 
    /**
     * Submit an assignment.
     */
    public function submit(Request $request, Assignment $assignment)
    {
        $user = Auth::user();
 
        // Validate deadline
        if (now()->gt($assignment->due_date)) {
            return redirect()->back()->with('error', 'Batas waktu pengumpulan tugas ini telah terlampaui!');
        }
 
        $request->validate([
            'submitted_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,zip,rar|max:10240', // 10MB
            'submitted_link' => 'nullable|url|max:255',
            'notes' => 'nullable|string',
        ]);
 
        if (!$request->hasFile('submitted_file') && empty($request->submitted_link)) {
            return redirect()->back()->with('error', 'Harap unggah berkas tugas Anda atau masukkan tautan/link pengerjaan.');
        }
 
        // Check if already submitted
        $submission = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('student_id', $user->id)
            ->first();
 
        $submittedFilePath = $submission ? $submission->submitted_file_path : null;
        if ($request->hasFile('submitted_file')) {
            // Delete old file if updating
            if ($submittedFilePath) {
                Storage::delete('public/' . $submittedFilePath);
            }
 
            $file = $request->file('submitted_file');
            $fileName = 'submissions_' . time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->storeAs('public/submissions', $fileName);
            $submittedFilePath = 'submissions/' . $fileName; // path for foto.bypass bypass route
        }
 
        AssignmentSubmission::updateOrCreate(
            ['assignment_id' => $assignment->id, 'student_id' => $user->id],
            [
                'submitted_file_path' => $submittedFilePath,
                'submitted_link' => $request->submitted_link,
                'notes' => $request->notes,
                'submitted_at' => now(),
            ]
        );
 
        return redirect()->route('mahasiswa.assignments.index')
            ->with('success', 'Tugas berhasil dikumpulkan!');
    }
}
