<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\InstagramPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InstagramPostController extends Controller
{
    /**
     * Extract shortcode from Instagram URL
     * e.g. https://www.instagram.com/p/ABC123/ -> ABC123
     */
    private function extractShortcode(string $url): ?string
    {
        if (preg_match('/instagram\.com\/(?:p|reel|tv)\/([A-Za-z0-9_-]+)/', $url, $matches)) {
            return $matches[1];
        }
        return null;
    }

    public function index()
    {
        $posts = InstagramPost::orderBy('order')->get();
        return view('backend.admin.instagram_posts.index', compact('posts'));
    }

    public function create()
    {
        return view('backend.admin.instagram_posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'           => 'nullable|string|max:255',
            'image'           => 'required|file|mimes:jpeg,png,jpg,webp,gif|max:10240',
            'instagram_link'  => 'required|url|max:500',
            'order'           => 'nullable|integer',
        ]);

        $imagePath = $request->file('image')->store('', 'direct_instagram');
        $shortcode = $this->extractShortcode($request->instagram_link);

        InstagramPost::create([
            'title'          => $request->title,
            'type'           => 'image',
            'image'          => $imagePath,
            'instagram_link' => $request->instagram_link,
            'caption'        => $shortcode, // reuse caption field to store shortcode
            'is_active'      => $request->has('is_active'),
            'order'          => $request->order ?? (InstagramPost::max('order') + 1),
        ]);

        return redirect()->route('backend.admin.instagram-posts.index')->with('success', 'Postingan Instagram berhasil ditambahkan.');
    }

    public function edit(InstagramPost $instagram_post)
    {
        return view('backend.admin.instagram_posts.edit', compact('instagram_post'));
    }

    public function update(Request $request, InstagramPost $instagram_post)
    {
        $request->validate([
            'title'           => 'nullable|string|max:255',
            'image'           => 'nullable|file|mimes:jpeg,png,jpg,webp,gif|max:10240',
            'instagram_link'  => 'required|url|max:500',
            'order'           => 'nullable|integer',
        ]);

        $shortcode = $this->extractShortcode($request->instagram_link);

        $oldOrder = $instagram_post->order;
        $newOrder = $request->order ?? 0;

        // Logic Tukar Posisi (Swap Order)
        if ($oldOrder != $newOrder) {
            $conflictPost = InstagramPost::where('order', $newOrder)->where('id', '!=', $instagram_post->id)->first();
            if ($conflictPost) {
                $conflictPost->update(['order' => $oldOrder]);
            }
        }

        $data = [
            'title'          => $request->title,
            'type'           => 'image',
            'instagram_link' => $request->instagram_link,
            'caption'        => $shortcode,
            'is_active'      => $request->has('is_active'),
            'order'          => $newOrder,
        ];

        if ($request->hasFile('image')) {
            if ($instagram_post->image) {
                Storage::disk('direct_instagram')->delete($instagram_post->image);
            }
            $data['image'] = $request->file('image')->store('', 'direct_instagram');
        }

        $instagram_post->update($data);

        return redirect()->route('backend.admin.instagram-posts.index')->with('success', 'Postingan Instagram berhasil diperbarui.');
    }

    public function destroy(InstagramPost $instagram_post)
    {
        if ($instagram_post->image) {
            Storage::disk('direct_instagram')->delete($instagram_post->image);
        }
        $instagram_post->delete();

        return redirect()->route('backend.admin.instagram-posts.index')->with('success', 'Postingan Instagram berhasil dihapus.');
    }
}
