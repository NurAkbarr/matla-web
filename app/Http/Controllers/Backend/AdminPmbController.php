<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\PmbRegistration;
use Illuminate\Http\Request;

class AdminPmbController extends Controller
{
    public function index(Request $request)
    {
        $query = PmbRegistration::query()->orderBy('created_at', 'desc');

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $registrations = $query->paginate(15);
        return view('backend.admin.pmb.index', compact('registrations'));
    }

    public function show(PmbRegistration $registration)
    {
        return view('backend.admin.pmb.show', compact('registration'));
    }

    public function updateStatus(Request $request, PmbRegistration $registration)
    {
        $request->validate([
            'status' => 'required|in:pending,verified,rejected,accepted',
            'admin_notes' => 'nullable|string'
        ]);

        $registration->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes
        ]);

        return redirect()->back()->with('success', 'Status pendaftar berhasil diperbarui.');
    }
}
