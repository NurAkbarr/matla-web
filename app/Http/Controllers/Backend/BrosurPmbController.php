<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\BrosurPmb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrosurPmbController extends Controller
{
    public function index()
    {
        $brosurs = BrosurPmb::orderBy('order')->get();
        return view('backend.admin.pmb.brosur.index', compact('brosurs'));
    }

    public function create()
    {
        return view('backend.admin.pmb.brosur.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'file' => 'required|mimes:pdf|max:10240',
            'order' => 'integer',
        ]);

        $imagePath = $request->file('image')->store('pmb/thumbnails', 'public');
        $filePath = $request->file('file')->store('pmb/files', 'public');

        BrosurPmb::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath,
            'file' => $filePath,
            'is_active' => $request->has('is_active'),
            'order' => $request->order ?? 0,
        ]);

        return redirect()->route('backend.admin.pmb.brosur.index')->with('success', 'Brosur berhasil ditambahkan.');
    }

    public function edit(BrosurPmb $brosur)
    {
        return view('backend.admin.pmb.brosur.edit', compact('brosur'));
    }

    public function update(Request $request, BrosurPmb $brosur)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'file' => 'nullable|mimes:pdf|max:10240',
            'order' => 'integer',
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
            'order' => $request->order ?? 0,
        ];

        if ($request->hasFile('image')) {
            if ($brosur->image) {
                Storage::disk('public')->delete($brosur->image);
            }
            $data['image'] = $request->file('image')->store('pmb/thumbnails', 'public');
        }

        if ($request->hasFile('file')) {
            if ($brosur->file) {
                Storage::disk('public')->delete($brosur->file);
            }
            $data['file'] = $request->file('file')->store('pmb/files', 'public');
        }

        $brosur->update($data);

        return redirect()->route('backend.admin.pmb.brosur.index')->with('success', 'Brosur berhasil diperbarui.');
    }

    public function destroy(BrosurPmb $brosur)
    {
        if ($brosur->image) {
            Storage::disk('public')->delete($brosur->image);
        }
        if ($brosur->file) {
            Storage::disk('public')->delete($brosur->file);
        }
        $brosur->delete();

        return redirect()->route('backend.admin.pmb.brosur.index')->with('success', 'Brosur berhasil dihapus.');
    }
}
