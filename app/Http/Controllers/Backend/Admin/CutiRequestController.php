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
}
