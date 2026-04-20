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
            'image' => 'required|file|max:5120', // Use 'file' instead of 'image' to reduce finfo dependency
            'order' => 'integer',
        ]);

        $imagePath = $request->file('image')->store('', 'direct_public');

        BrosurPmb::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath,
            'file' => $imagePath, // Save image path as file path for backward compatibility
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
            'image' => 'nullable|file|max:5120',
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
                Storage::disk('direct_public')->delete($brosur->image);
            }
            $imagePath = $request->file('image')->store('', 'direct_public');
            $data['image'] = $imagePath;
            $data['file'] = $imagePath;
        }

        $brosur->update($data);

        return redirect()->route('backend.admin.pmb.brosur.index')->with('success', 'Brosur berhasil diperbarui.');
    }

    public function destroy(BrosurPmb $brosur)
    {
        if ($brosur->image) {
            Storage::disk('direct_public')->delete($brosur->image);
        }
        $brosur->delete();

        return redirect()->route('backend.admin.pmb.brosur.index')->with('success', 'Brosur berhasil dihapus.');
    }
}
