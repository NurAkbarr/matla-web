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
    
    public function download(Khs $khs)
    {
        if ($khs->mahasiswa_id !== Auth::id()) {
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

