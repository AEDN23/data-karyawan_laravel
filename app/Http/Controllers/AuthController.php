<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('karyawan.index');
        }
        return view('auth.login');
    }

    /**
     * Tampilkan halaman registrasi
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('karyawan.index');
        }
        return view('auth.register');
    }

    /**
     * Proses registrasi
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // Default role
        ]);

        Auth::login($user);

        return redirect()->route('karyawan.index')
            ->with('success', 'Registrasi berhasil! Selamat datang, ' . $user->name);
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('karyawan.index'))
                ->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '!');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Tampilkan semua user (Hanya Admin)
     */
    public function index()
    {
        // Proteksi Role: Hanya Admin & Superadmin
        if (!Auth::check() || !in_array(Auth::user()->role, ['superadmin'])) {
            return redirect()->route('karyawan.index')->with('error', 'Anda tidak memiliki hak akses untuk halaman tersebut.');
        }

        $users = User::latest()->get();
        return view('auth.index', compact('users'));
    }

    /**
     * Store data user baru (Hanya Admin)
     */
    public function store(Request $request)
    {
        // Proteksi Role: Hanya Superadmin
        if (!Auth::check() || Auth::user()->role !== 'superadmin') {
            return redirect()->route('karyawan.index')->with('error', 'Anda tidak memiliki hak akses untuk aksi tersebut.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:user,admin,superadmin'],
        ]);

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            return redirect()->back()->with('success', 'User ' . $request->name . ' berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan user: ' . $e->getMessage());
        }
    }

    /**
     * Update data user (Hanya Admin)
     */
    public function update(Request $request, $id)
    {
        // Proteksi Role: Hanya Admin & Superadmin
        if (!Auth::check() || !in_array(Auth::user()->role, ['superadmin'])) {
            return redirect()->route('karyawan.index')->with('error', 'Anda tidak memiliki hak akses untuk aksi tersebut.');
        }

        $user = User::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', 'in:user,admin,superadmin'],
        ]);

        try {
            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
            ];

            // Update password hanya jika diisi
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            return redirect()->back()->with('success', 'User ' . $user->name . ' berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui user: ' . $e->getMessage());
        }
    }

    /**
     * Hapus data user (Hanya Superadmin)
     */
    public function destroy($id)
    {
        // Proteksi Role: Hanya Superadmin
        if (!Auth::check() || !in_array(Auth::user()->role, ['superadmin'])) {
            return redirect()->route('karyawan.index')->with('error', 'Anda tidak memiliki hak akses untuk aksi tersebut.');
        }

        $user = User::findOrFail($id);

        // Jangan biarkan user menghapus dirinya sendiri
        if (Auth::id() == $user->id) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        try {
            $user->delete();
            return redirect()->back()->with('success', 'User ' . $user->name . ' berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar.');
    }
}
