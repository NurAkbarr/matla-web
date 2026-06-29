<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProgramStudi;
use App\Models\User;
use App\Models\Khs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KhsController extends Controller
{
    public function index()
    {
        $programStudis = ProgramStudi::active()->get();
        return view('backend.admin.khs.index', compact('programStudis'));
    }

    public function showProdi(ProgramStudi $prodi)
    {
        // Get distinct angkatan based on students in this prodi
        $angkatans = User::where('role', 'mahasiswa')
            ->where(function($query) use ($prodi) {
                $query->where('education->program_studi', $prodi->nama)
                      ->orWhere('education->program_studi', $prodi->singkatan);
            })
            ->whereNotNull('angkatan')
            ->where('angkatan', '!=', '')
            ->distinct()
            ->pluck('angkatan')
            ->sortDesc()
            ->values();

        return view('backend.admin.khs.angkatan', compact('prodi', 'angkatans'));
    }

    public function showAngkatan(ProgramStudi $prodi, $angkatan)
    {
        // Get highest semester for this prodi and angkatan
        $maxSemester = User::where('role', 'mahasiswa')
            ->where(function($query) use ($prodi) {
                $query->where('education->program_studi', $prodi->nama)
                      ->orWhere('education->program_studi', $prodi->singkatan);
            })
            ->where('angkatan', $angkatan)
            ->max('semester');

        // Generate semesters from 1 up to the highest semester (minimum 1)
        $highest = max(1, (int) $maxSemester);
        $semesters = collect(range(1, $highest));

        return view('backend.admin.khs.semester', compact('prodi', 'angkatan', 'semesters'));
    }

    public function showSemester(ProgramStudi $prodi, $angkatan, $semester)
    {
        $mahasiswas = User::where('role', 'mahasiswa')
            ->where(function($query) use ($prodi) {
                $query->where('education->program_studi', $prodi->nama)
                      ->orWhere('education->program_studi', $prodi->singkatan);
            })
            ->where('angkatan', $angkatan)
            ->with(['khs' => function($query) use ($semester) {
                $query->where('semester', $semester);
            }])
            ->orderBy('name')
            ->get();

        return view('backend.admin.khs.mahasiswa', compact('prodi', 'angkatan', 'semester', 'mahasiswas'));
    }

    public function store(Request $request, User $mahasiswa)
    {
        $request->validate([
            'file' => 'required|mimes:pdf|max:5120', // max 5MB
            'semester' => 'required'
        ]);

        $semester = $request->semester;

        // Check if KHS already exists for this semester
        $khs = Khs::where('mahasiswa_id', $mahasiswa->id)
                  ->where('semester', $semester)
                  ->first();

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = \Illuminate\Support\Str::slug($mahasiswa->name) . '_khs.pdf';
            $directory = "KHS MHS/Angkatan {$mahasiswa->angkatan}/Semester {$semester}";
            $path = $file->storeAs($directory, $fileName, 'google');

            if ($khs) {
                // Delete old file
                if (Storage::disk('google')->exists($khs->file_path)) {
                    Storage::disk('google')->delete($khs->file_path);
                }
                $khs->update(['file_path' => $path]);
            } else {
                Khs::create([
                    'mahasiswa_id' => $mahasiswa->id,
                    'semester' => $semester,
                    'file_path' => $path
                ]);
            }
        }

        return back()->with('success', 'KHS berhasil diupload!');
    }

    public function destroy(Khs $khs)
    {
        if (Storage::disk('google')->exists($khs->file_path)) {
            Storage::disk('google')->delete($khs->file_path);
        }
        $khs->delete();

        return back()->with('success', 'KHS berhasil dihapus!');
    }

    public function preview(Khs $khs)
    {
        $fileName = basename($khs->file_path);
        // Use relative URL to prevent cross-origin iframe blocking if APP_URL doesn't match
        $fileUrl = route('backend.admin.khs.file', $khs->id, false);

        // Mobile browsers generally do not support inline PDF viewing via iframe.
        $userAgent = request()->header('User-Agent');
        if (preg_match('/Mobile|Android|BlackBerry|iPhone|iPad|iPod|Windows Phone/i', $userAgent)) {
            return redirect($fileUrl);
        }

        return response(<<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$fileName}</title>
    <style>
        body, html { margin: 0; padding: 0; height: 100%; overflow: hidden; background-color: #323639; }
        iframe { width: 100%; height: 100%; border: none; }
    </style>
</head>
<body>
    <iframe src="{$fileUrl}#navpanes=0" title="{$fileName}"></iframe>
</body>
</html>
HTML);
    }

    public function file(Khs $khs)
    {
        $disk = Storage::disk('google');
        if (!$disk->exists($khs->file_path)) {
            abort(404, 'File KHS tidak ditemukan di Google Drive.');
        }

        try {
            $mimeType = $disk->mimeType($khs->file_path) ?: 'application/pdf';
            $fileName = basename($khs->file_path);
            
            return response($disk->get($khs->file_path))
                ->header('Content-Type', $mimeType)
                ->header('Content-Disposition', 'inline; filename="' . $fileName . '"');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Drive download error: ' . $e->getMessage(), ['exception' => $e]);
            abort(500, 'Gagal mengunduh file dari Google Drive: ' . $e->getMessage());
        }
    }
}

