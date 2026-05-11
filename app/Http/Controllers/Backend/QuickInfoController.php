<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\QuickInfo;
use Illuminate\Http\Request;

class QuickInfoController extends Controller
{
    public function index()
    {
        $items = QuickInfo::orderBy('order', 'asc')->get();
        return view('backend.admin.quick_infos.index', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'link' => 'nullable|string|max:255',
        ]);

        QuickInfo::create([
            'label' => $request->label,
            'link' => $request->link ?? '#',
            'order' => QuickInfo::max('order') + 1,
            'is_active' => true,
        ]);

        return back()->with('success', 'Tombol informasi berhasil ditambahkan.');
    }

    public function update(Request $request, QuickInfo $quickInfo)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'link' => 'nullable|string|max:255',
        ]);

        $quickInfo->update($request->only('label', 'link'));

        return back()->with('success', 'Tombol informasi berhasil diperbarui.');
    }

    public function destroy(QuickInfo $quickInfo)
    {
        $quickInfo->delete();
        return back()->with('success', 'Tombol informasi berhasil dihapus.');
    }
}
