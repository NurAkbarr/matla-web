<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\CutiRequest;
use Illuminate\Http\Request;

class CutiRequestController extends Controller
{
    public function index()
    {
        $cutiRequests = CutiRequest::with('user')->orderBy('created_at', 'desc')->paginate(15);
        return view('backend.admin.cuti.index', compact('cutiRequests'));
    }

    public function show($id)
    {
        $cutiRequest = CutiRequest::with('user')->findOrFail($id);
        return view('backend.admin.cuti.show', compact('cutiRequest'));
    }

    public function update(Request $request, $id)
    {
        $cutiRequest = CutiRequest::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_notes' => 'nullable|string'
        ]);

        $cutiRequest->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes
        ]);

        if ($request->status === 'approved') {
            // Update user status
            if ($cutiRequest->user) {
                $cutiRequest->user->update([
                    'status' => 'CUTI'
                ]);
            }
        }

        $message = $request->status === 'approved' ? 'disetujui' : 'ditolak';

        return redirect()->route('backend.admin.cuti.index')
            ->with('success', "Pengajuan cuti berhasil {$message}.");
    }

    public function destroy($id)
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Unauthorized action.');
        }

        $cutiRequest = CutiRequest::findOrFail($id);

        // Jika form yang dihapus sebelumnya sudah di-Acc (status mahasiswa sedang CUTI),
        // maka kembalikan status mahasiswanya menjadi AKTIF
        if ($cutiRequest->status === 'approved' && $cutiRequest->user && $cutiRequest->user->status === 'CUTI') {
            $cutiRequest->user->update([
                'status' => 'AKTIF'
            ]);
        }

        $cutiRequest->delete();

        return redirect()->route('backend.admin.cuti.index')
            ->with('success', 'Data pengajuan cuti berhasil dihapus secara permanen.');
    }
}
