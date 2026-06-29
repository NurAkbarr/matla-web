<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Khs;
use Illuminate\Support\Facades\Storage;

class KhsController extends Controller
{
    public function index()
    {
        $khsList = Khs::where('mahasiswa_id', Auth::id())
                      ->orderBy('semester', 'desc')
                      ->get();
                      
        return view('mahasiswa.khs.index', compact('khsList'));
    }
    
    public function preview(Khs $khs)
    {
        if ($khs->mahasiswa_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $fileName = basename($khs->file_path);
        // Use relative URL to prevent cross-origin iframe blocking
        $fileUrl = route('mahasiswa.khs.file', $khs->id, false);

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
    <iframe src="{$fileUrl}#toolbar=0&navpanes=0" title="{$fileName}"></iframe>
</body>
</html>
HTML);
    }

    public function file(Khs $khs)
    {
        if ($khs->mahasiswa_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

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

