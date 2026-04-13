<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('backend.dosen.profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'nidn' => ['nullable', 'string', 'max:50'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'bio' => ['nullable', 'string'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $data = $request->only(['name', 'email', 'nidn', 'phone', 'address', 'bio']);

        // Handle Avatar Upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        // Handle Password Change
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Handle Education (Dynamic)
        if ($request->has('education_level')) {
            $education = [];
            foreach ($request->education_level as $key => $level) {
                if ($level && $request->education_institution[$key]) {
                    $education[] = [
                        'level' => $level,
                        'institution' => $request->education_institution[$key],
                        'year' => $request->education_year[$key] ?? '',
                    ];
                }
            }
            $data['education'] = $education;
        }

        // Handle Expertise
        if ($request->has('expertise')) {
            $data['expertise'] = array_filter(explode(',', $request->expertise), fn($val) => !empty(trim($val)));
        }

        // Handle Social Links
        $data['social_links'] = [
            'linkedin' => $request->linkedin,
            'github' => $request->github,
            'scholar' => $request->scholar,
        ];

        $user->update($data);

        return redirect()->back()->with('success', 'Profil Anda berhasil diperbarui.');
    }
}
