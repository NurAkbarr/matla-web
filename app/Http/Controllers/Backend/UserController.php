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
        if ($request->has('phone')) {
            $request->merge(['phone' => User::sanitizeScientificNumber($request->phone)]);
        }
        if ($request->has('nim')) {
            $request->merge(['nim' => User::sanitizeScientificNumber($request->nim)]);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [$request->role === 'dosen' ? 'nullable' : 'required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20', 'unique:users'],
            'nidn' => ['nullable', 'string', 'max:50', 'unique:users'],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', 'in:admin,dosen,mahasiswa,super_admin,keuangan'],
            'nim' => ['required_if:role,mahasiswa', 'nullable', 'string', 'max:50', 'unique:users'],
            'program_studi' => ['required_if:role,mahasiswa', 'nullable', 'string', 'max:100'],
            'angkatan' => ['required_if:role,mahasiswa', 'nullable', 'string'],
            'semester' => ['required_if:role,mahasiswa', 'nullable', 'integer'],
            'status' => ['required_if:role,mahasiswa', 'nullable', 'string'],
        ]);

        if ($request->role === 'super_admin' && Auth::user()->role !== 'super_admin') {
            abort(403, 'Hanya Super Admin yang dapat menetapkan role Super Admin.');
        }

        $password = $request->filled('password') ? $request->password : 'password123';

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'nidn' => $request->nidn,
            'password' => Hash::make($password),
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

        // Auto-synchronize class groups if a new student is added
        if ($user->role === 'mahasiswa') {
            try {
                \Illuminate\Support\Facades\Artisan::call('classgroup:sync');
            } catch (\Exception $e) {
                // Ignore failure
            }
        }

        $redirectRoute = 'backend.admin.users.index';
        if ($user->role === 'mahasiswa') {
            $redirectRoute = 'backend.admin.mahasiswa';
        } elseif ($user->role === 'dosen') {
            $redirectRoute = 'backend.admin.dosen';
        }

        return redirect()->route($redirectRoute)->with('success', 'User berhasil ditambahkan.');
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
        if ($request->has('phone')) {
            $request->merge(['phone' => User::sanitizeScientificNumber($request->phone)]);
        }
        if ($request->has('nim')) {
            $request->merge(['nim' => User::sanitizeScientificNumber($request->nim)]);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [$request->role === 'dosen' ? 'nullable' : 'required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone,' . $user->id],
            'nidn' => ['nullable', 'string', 'max:50', 'unique:users,nidn,' . $user->id],
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
            'phone' => $request->phone,
            'nidn' => $request->nidn,
            'nim' => $request->nim,
            'angkatan' => $request->angkatan,
            'semester' => $request->semester,
            'status' => $request->status,
        ];

        $currentUser = Auth::user();

        // 1. Cegah non-super_admin mengedit akun super_admin
        if ($user->role === 'super_admin' && $currentUser->role !== 'super_admin') {
            abort(403, 'Hanya Super Admin yang dapat mengubah profil Super Admin.');
        }

        // 2. Cegah non-super_admin menaikkan level ke super_admin
        if ($request->filled('role')) {
            if ($request->role === 'super_admin' && $currentUser->role !== 'super_admin') {
                abort(403, 'Hanya Super Admin yang dapat menetapkan role Super Admin.');
            }
            if ($user->role !== 'super_admin') {
                $data['role'] = $request->role;
            }
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        $education = $user->education ?? [];
        $education['program_studi'] = $request->program_studi;
        $user->education = $education;
        $user->save();

        // Auto-synchronize class groups on student update
        if ($user->role === 'mahasiswa') {
            try {
                \Illuminate\Support\Facades\Artisan::call('classgroup:sync');
            } catch (\Exception $e) {
                // Ignore failure
            }
        }

        $redirectRoute = 'backend.admin.users.index';
        if ($user->role === 'mahasiswa') {
            $redirectRoute = 'backend.admin.mahasiswa';
        } elseif ($user->role === 'dosen') {
            $redirectRoute = 'backend.admin.dosen';
        }

        return redirect()->route($redirectRoute)->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $currentUser = Auth::user();
        if (!in_array($currentUser->role, ['super_admin', 'admin'])) {
            return redirect()->back()->with('error', 'Hanya Super Admin dan Admin yang dapat menghapus data.');
        }

        if ($currentUser->id == $user->id) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        if ($user->role === 'super_admin') {
            return redirect()->back()->with('error', 'Akun Super Admin tidak dapat dihapus.');
        }

        if ($currentUser->role === 'admin' && in_array($user->role, ['admin', 'super_admin'])) {
            return redirect()->back()->with('error', 'Admin tidak dapat menghapus akun sesama Admin atau Super Admin.');
        }

        if ($user) {
            $role = $user->role;
            $user->delete();
            
            $redirectRoute = 'backend.admin.users.index';
            if ($role === 'mahasiswa') {
                $redirectRoute = 'backend.admin.mahasiswa';
            } elseif ($role === 'dosen') {
                $redirectRoute = 'backend.admin.dosen';
            }
            
            return redirect()->route($redirectRoute)->with('success', 'User berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'User tidak ditemukan.');
    }

    public function bulkDelete(Request $request)
    {
        $currentUser = Auth::user();
        if (!in_array($currentUser->role, ['super_admin', 'admin'])) {
            return redirect()->back()->with('error', 'Akses ditolak. Hanya Super Admin dan Admin yang dapat melakukan Bulk Delete.');
        }

        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        $ids = $request->user_ids;

        // Cegah Super Admin dihapus
        $superAdmins = User::whereIn('id', $ids)->where('role', 'super_admin')->count();
        if ($superAdmins > 0) {
            return redirect()->back()->with('error', 'Terdapat akun Super Admin dalam pilihan Anda. Akun Super Admin tidak dapat dihapus massal.');
        }

        // Jika Admin, cegah menghapus akun Admin
        if ($currentUser->role === 'admin') {
            $adminCount = User::whereIn('id', $ids)->where('role', 'admin')->count();
            if ($adminCount > 0) {
                return redirect()->back()->with('error', 'Terdapat akun Admin dalam pilihan Anda. Admin tidak dapat menghapus sesama akun Admin.');
            }
        }

        // Pastikan tidak menghapus diri sendiri
        if (in_array($currentUser->id, $ids)) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        User::whereIn('id', $ids)->delete();

        return redirect()->back()->with('success', count($ids) . ' pengguna berhasil dihapus massal.');
    }
}
