<?php
 
namespace App\Http\Controllers\Backend;
 
use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\ClassGroup;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
 
class AssignmentController extends Controller
{
    /**
     * Display all assignments.
     */
    public function index()
    {
        // Admin can see all, Dosen can see assignments they created
        $query = Assignment::with(['classGroup', 'mataKuliah', 'creator'])->latest();
        
        if (Auth::user()->role === 'dosen') {
            $mataKuliahIds = Auth::user()->jadwals()->pluck('mata_kuliah_id');
            $query->where(function($q) use ($mataKuliahIds) {
                $q->whereIn('mata_kuliah_id', $mataKuliahIds)
                  ->orWhere('created_by', Auth::id());
            });
        }
 
        $assignments = $query->paginate(15);
 
        return view('backend.assignments.index', compact('assignments'));
    }
 
    /**
     * Show form to create an assignment.
     */
    public function create()
    {
        $classGroups = ClassGroup::orderBy('name')->get();
        
        if (Auth::user()->role === 'dosen') {
            $mataKuliahIds = Auth::user()->jadwals()->pluck('mata_kuliah_id');
            $mataKuliahs = MataKuliah::whereIn('id', $mataKuliahIds)->orderBy('nama')->get();
        } else {
            $mataKuliahs = MataKuliah::orderBy('nama')->get();
        }

        return view('backend.assignments.create', compact('classGroups', 'mataKuliahs'));
    }
 
    /**
     * Store new assignment.
     */
    public function store(Request $request)
    {
        $request->validate([
            'class_group_id' => 'required|exists:class_groups,id',
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:upload,quiz',
            'file_attachment' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,zip,rar|max:10240', // 10MB max
            'link' => 'nullable|url|max:255',
            'due_date' => 'required|date|after:now',
            'questions' => 'nullable|array',
            'questions.*.type' => 'required_with:questions|in:multiple_choice,checkbox,short_answer,paragraph',
            'questions.*.text' => 'required_with:questions|string',
            'questions.*.points' => 'nullable|numeric|min:0',
            'questions.*.options' => 'nullable|array',
            'questions.*.options.*.text' => 'required_with:questions.*.options|string',
        ]);
 
        $filePath = null;
        if ($request->type === 'upload' && $request->hasFile('file_attachment')) {
            $mataKuliah = \App\Models\MataKuliah::find($request->mata_kuliah_id);
            $courseName = $mataKuliah ? $mataKuliah->nama : 'Umum';
            $folderName = preg_replace('/[^A-Za-z0-9_\-\s]/', '', $courseName);
            $folderName = str_replace(' ', '_', $folderName);
            
            $file = $request->file('file_attachment');
            $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $drivePath = 'soal_tugas/' . $folderName . '/' . $fileName;
            
            \Illuminate\Support\Facades\Storage::disk('google')->put($drivePath, file_get_contents($file->getRealPath()));
            $filePath = $drivePath;
        }

        $assignment = Assignment::create([
            'class_group_id' => $request->class_group_id,
            'mata_kuliah_id' => $request->mata_kuliah_id,
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'file_path' => $request->type === 'upload' ? $filePath : null,
            'link' => $request->type === 'upload' ? $request->link : null,
            'submission_type' => 'file', // Default value for DB compatibility
            'due_date' => $request->due_date,
            'created_by' => Auth::id(),
        ]);

        if ($request->type === 'quiz' && $request->has('questions')) {
            foreach ($request->questions as $qData) {
                $question = $assignment->questions()->create([
                    'type' => $qData['type'],
                    'question_text' => $qData['text'],
                    'points' => $qData['points'] ?? 0,
                    'is_required' => true,
                ]);

                if (in_array($qData['type'], ['multiple_choice', 'checkbox']) && !empty($qData['options'])) {
                    foreach ($qData['options'] as $optIndex => $optData) {
                        $isCorrect = (isset($optData['is_correct']) && $optData['is_correct'] == '1');

                        $question->options()->create([
                            'option_text' => $optData['text'],
                            'is_correct' => $isCorrect,
                        ]);
                    }
                }
            }
        }
 
        return redirect()->route('backend.admin.assignments.index')
            ->with('success', 'Tugas berhasil dipublikasikan ke kelompok kelas!');
    }
 
    /**
     * Show edit form.
     */
    public function edit(Assignment $assignment)
    {
        if (Auth::user()->role === 'dosen' && $assignment->created_by !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $classGroups = ClassGroup::all();
        
        if (Auth::user()->role === 'dosen') {
            $mataKuliahIds = Auth::user()->jadwals()->pluck('mata_kuliah_id');
            $mataKuliahs = MataKuliah::whereIn('id', $mataKuliahIds)->get();
        } else {
            $mataKuliahs = MataKuliah::all();
        }

        return view('backend.assignments.edit', compact('assignment', 'classGroups', 'mataKuliahs'));
    }

    /**
     * Update assignment.
     */
    public function update(Request $request, Assignment $assignment)
    {
        if (Auth::user()->role === 'dosen' && $assignment->created_by !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'class_group_id' => 'required|exists:class_groups,id',
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:upload,quiz',
            'file_attachment' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,zip,rar|max:10240',
            'link' => 'nullable|url|max:255',
            'due_date' => 'required|date',
            'questions' => 'nullable|array',
            'questions.*.type' => 'required_with:questions|in:multiple_choice,checkbox,short_answer,paragraph',
            'questions.*.text' => 'required_with:questions|string',
            'questions.*.points' => 'nullable|numeric|min:0',
            'questions.*.options' => 'nullable|array',
            'questions.*.options.*.text' => 'required_with:questions.*.options|string',
        ]);
 
        $filePath = $assignment->file_path;
        if ($request->type === 'upload' && $request->hasFile('file_attachment')) {
            if ($filePath) {
                try {
                    \Illuminate\Support\Facades\Storage::disk('google')->delete($filePath);
                } catch (\Exception $e) {
                    // Silently fail if old file doesn't exist on Drive
                }
            }
            
            $mataKuliah = \App\Models\MataKuliah::find($request->mata_kuliah_id);
            $courseName = $mataKuliah ? $mataKuliah->nama : 'Umum';
            $folderName = preg_replace('/[^A-Za-z0-9_\-\s]/', '', $courseName);
            $folderName = str_replace(' ', '_', $folderName);
            
            $file = $request->file('file_attachment');
            $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $drivePath = 'soal_tugas/' . $folderName . '/' . $fileName;
            
            \Illuminate\Support\Facades\Storage::disk('google')->put($drivePath, file_get_contents($file->getRealPath()));
            $filePath = $drivePath;
        } else if ($request->type === 'quiz' && $filePath) {
            // Delete old file if changing from upload to quiz
            try {
                \Illuminate\Support\Facades\Storage::disk('google')->delete($filePath);
                $filePath = null;
            } catch (\Exception $e) {
                // Silently fail
            }
        }
 
        $assignment->update([
            'class_group_id' => $request->class_group_id,
            'mata_kuliah_id' => $request->mata_kuliah_id,
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'file_path' => $request->type === 'upload' ? $filePath : null,
            'link' => $request->type === 'upload' ? $request->link : null,
            'due_date' => $request->due_date,
        ]);

        if ($request->type === 'quiz') {
            // Remove existing questions first
            $assignment->questions()->delete();

            if ($request->has('questions')) {
                foreach ($request->questions as $qData) {
                    $question = $assignment->questions()->create([
                        'type' => $qData['type'],
                        'question_text' => $qData['text'],
                        'points' => $qData['points'] ?? 0,
                        'is_required' => true,
                    ]);

                    if (in_array($qData['type'], ['multiple_choice', 'checkbox']) && !empty($qData['options'])) {
                        foreach ($qData['options'] as $optIndex => $optData) {
                                $isCorrect = (isset($optData['is_correct']) && $optData['is_correct'] == '1');

                            $question->options()->create([
                                'option_text' => $optData['text'],
                                'is_correct' => $isCorrect,
                            ]);
                        }
                    }
                }
            }
        } else {
            // If changing to upload, remove any existing questions
            $assignment->questions()->delete();
        }
 
        return redirect()->route('backend.admin.assignments.index')
            ->with('success', 'Tugas berhasil diperbarui!');
    }
 
    /**
     * View assignment submissions / grading center.
     */
    public function show(Assignment $assignment)
    {
        // Security check for Dosen
        if (Auth::user()->role === 'dosen' && $assignment->created_by !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
 
        // Get all students inside this target class group
        $classGroup = $assignment->classGroup;
        $students = $classGroup->students()->orderBy('name')->get();
 
        // Get all submissions for this assignment
        $submissions = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->get()
            ->keyBy('student_id');
 
        return view('backend.assignments.submissions', compact('assignment', 'classGroup', 'students', 'submissions'));
    }
 
    /**
     * Grade student submission.
     */
    public function grade(Request $request, AssignmentSubmission $submission)
    {
        $request->validate([
            'score' => 'required|integer|min:0|max:100',
            'feedback' => 'nullable|string',
        ]);
 
        $submission->update([
            'score' => $request->score,
            'feedback' => $request->feedback,
        ]);
 
        return redirect()->back()
            ->with('success', "Nilai untuk mahasiswa {$submission->student->name} berhasil disimpan!");
    }
 
    /**
     * Delete assignment.
     */
    public function destroy(Assignment $assignment)
    {
        if (Auth::user()->role === 'dosen' && $assignment->created_by !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
 
        // Delete attachment if exists
        if ($assignment->file_path) {
            Storage::delete('public/' . $assignment->file_path);
        }
 
        $assignment->delete();
 
        return redirect()->route('backend.admin.assignments.index')
            ->with('success', 'Tugas berhasil dihapus.');
    }

    /**
     * View student's quiz answers.
     */
    public function quizAnswers(AssignmentSubmission $submission)
    {
        $assignment = $submission->assignment;
        
        // Security check for Dosen
        if (Auth::user()->role === 'dosen' && $assignment->created_by !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($assignment->type !== 'quiz') {
            abort(404, 'Tugas ini bukan kuis.');
        }

        $assignment->load('questions.options');
        $submission->load('quizAnswers.selectedOptions');

        return view('backend.assignments.quiz-answers', compact('assignment', 'submission'));
    }
}
