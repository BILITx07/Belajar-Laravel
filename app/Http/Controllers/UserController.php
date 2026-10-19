<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Menampilkan daftar pengguna dengan tampilan rapi persis referensi FEWS BWS
     */
    public function index(Request $request)
    {
        $search  = trim((string) $request->input('search', ''));
        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        $query = User::query();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate($perPage)->withQueryString();

        $totalUsers       = User::count();
        $totalSuperadmin  = User::where('role', 'superadmin')->count();
        $totalAdmin       = User::where('role', '!=', 'superadmin')->count();

        return view('admin.users', compact(
            'users',
            'search',
            'perPage',
            'totalUsers',
            'totalSuperadmin',
            'totalAdmin'
        ));
    }

    /**
     * Menyimpan pengguna baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'nullable|string|max:100|unique:users,username',
            'email'    => 'required|email|max:255|unique:users,email',
            'phone'    => 'nullable|string|max:30',
            'role'     => 'required|in:superadmin,admin',
            'password' => 'required|string|min:6',
        ], [
            'name.required'     => 'Nama pengguna wajib diisi.',
            'email.required'    => 'Email wajib diisi.',
            'email.unique'      => 'Email sudah digunakan oleh akun lain.',
            'username.unique'   => 'Username sudah digunakan oleh akun lain.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal harus 6 karakter.',
            'role.required'     => 'Role pengguna wajib dipilih.',
            'role.in'           => 'Role pengguna harus Super Admin atau Admin.',
        ]);

        $username = $request->username;
        if (empty($username)) {
            $username = explode('@', $request->email)[0];
        }

        User::create([
            'name'     => $request->name,
            'username' => $username,
            'email'    => strtolower(trim($request->email)),
            'phone'    => $request->phone,
            'role'     => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', "User '{$request->name}' berhasil ditambahkan ke sistem.");
    }

    /**
     * Memperbarui data pengguna
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => ['nullable', 'string', 'max:100', Rule::unique('users')->ignore($user->id)],
            'email'    => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone'    => 'nullable|string|max:30',
            'role'     => 'required|in:superadmin,admin',
            'password' => 'nullable|string|min:6',
        ], [
            'name.required'   => 'Nama pengguna wajib diisi.',
            'email.required'  => 'Email wajib diisi.',
            'email.unique'    => 'Email sudah terdaftar pada pengguna lain.',
            'username.unique' => 'Username sudah digunakan oleh akun lain.',
            'password.min'    => 'Password baru minimal harus 6 karakter.',
            'role.required'   => 'Role pengguna wajib dipilih.',
            'role.in'         => 'Role pengguna harus Super Admin atau Admin.',
        ]);

        // Cegah superadmin mengubah role dirinya sendiri
        if (Auth::id() === $user->id && $request->role !== $user->role && $user->role === 'superadmin') {
            return back()->with('error', 'Anda tidak dapat mengubah role akun Super Admin milik Anda sendiri.');
        }

        $updateData = [
            'name'     => $request->name,
            'username' => $request->username ?: explode('@', $request->email)[0],
            'email'    => strtolower(trim($request->email)),
            'phone'    => $request->phone,
            'role'     => $request->role,
        ];

        if (!empty($request->password)) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return redirect()
            ->route('users.index')
            ->with('success', "Data user '{$user->name}' berhasil diperbarui.");
    }

    /**
     * Menghapus pengguna
     */
    public function destroy(User $user)
    {
        if (Auth::id() === $user->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', "User '{$userName}' berhasil dihapus dari sistem.");
    }
}
