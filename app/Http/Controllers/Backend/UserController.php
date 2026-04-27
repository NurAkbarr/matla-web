<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Apply Search
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%")
                  ->orWhere('nim', 'like', "%{$searchTerm}%");
            });
        }

        // Apply Filter by Role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(15);
        $roles = User::distinct()->pluck('role')->sort()->values();

        return view('backend.admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $prodis = \App\Models\ProgramStudi::active()->get();
        return view('backend.admin.users.create', compact('prodis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'in:admin,dosen,mahasiswa,super_admin'],
            'nim' => ['required_if:role,mahasiswa', 'nullable', 'string', 'max:50', 'unique:users'],
            'program_studi' => ['required_if:role,mahasiswa', 'nullable', 'string', 'max:100'],
            'angkatan' => ['required_if:role,mahasiswa', 'nullable', 'string'],
            'semester' => ['required_if:role,mahasiswa', 'nullable', 'integer'],
            'status' => ['required_if:role,mahasiswa', 'nullable', 'string'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'nim' => $request->nim,
            'angkatan' => $request->angkatan,
            'semester' => $request->semester,
            'status' => $request->status ?? 'AKTIF',
        ]);

        if ($request->filled('program_studi')) {
            $education = $user->education ?? [];
            $education['program_studi'] = $request->program_studi;
            $user->education = $education;
            $user->save();
        }

        return redirect()->route('backend.admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        return view('backend.admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $prodis = \App\Models\ProgramStudi::active()->get();
        return view('backend.admin.users.edit', compact('user', 'prodis'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8'],
            'nim' => ['required_if:role,mahasiswa', 'nullable', 'string', 'max:50', 'unique:users,nim,' . $user->id],
            'program_studi' => ['required_if:role,mahasiswa', 'nullable', 'string', 'max:100'],
            'angkatan' => ['required_if:role,mahasiswa', 'nullable', 'string'],
            'semester' => ['required_if:role,mahasiswa', 'nullable', 'integer'],
            'status' => ['required_if:role,mahasiswa', 'nullable', 'string'],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'nim' => $request->nim,
            'angkatan' => $request->angkatan,
            'semester' => $request->semester,
            'status' => $request->status,
        ];

        // Super Admin: role tidak boleh diubah
        if ($user->role !== 'super_admin') {
            $data['role'] = $request->role;
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        $education = $user->education ?? [];
        $education['program_studi'] = $request->program_studi;
        $user->education = $education;
        $user->save();

        return redirect()->route('backend.admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if (Auth::id() == $user->id) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        if ($user->role === 'super_admin') {
            return redirect()->back()->with('error', 'Akun Super Admin tidak dapat dihapus.');
        }

        if ($user) {
            $user->delete();
            return redirect()->route('backend.admin.users.index')->with('success', 'User berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'User tidak ditemukan.');
    }
}
