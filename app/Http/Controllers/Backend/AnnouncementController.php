<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest()->paginate(10);
        return view('backend.admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('backend.admin.announcements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'icon' => 'required|string',
            'published_at' => 'required|date',
            'pdf_file' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $data = $request->all();

        if ($request->hasFile('pdf_file')) {
            $data['pdf_file'] = $request->file('pdf_file')->store('announcements', 'public');
        }

        Announcement::create($data);

        return redirect()->route('backend.admin.announcements.index')->with('success', 'Berita berhasil ditambahkan');
    }

    public function edit(Announcement $announcement)
    {
        return view('backend.admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'icon' => 'required|string',
            'published_at' => 'required|date',
            'pdf_file' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $data = $request->all();

        if ($request->hasFile('pdf_file')) {
            if ($announcement->pdf_file) {
                Storage::disk('public')->delete($announcement->pdf_file);
            }
            $data['pdf_file'] = $request->file('pdf_file')->store('announcements', 'public');
        }

        $announcement->update($data);

        return redirect()->route('backend.admin.announcements.index')->with('success', 'Berita berhasil diperbarui');
    }

    public function destroy(Announcement $announcement)
    {
        if ($announcement->pdf_file) {
            Storage::disk('public')->delete($announcement->pdf_file);
        }
        $announcement->delete();
        return redirect()->route('backend.admin.announcements.index')->with('success', 'Berita berhasil dihapus');
    }
}
