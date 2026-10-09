<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function form()
    {
        return view('auth.login');
    }

    public function login(Request $r)
    {
        $data = $r->validate([
            'role' => 'required|in:admin,musrif,santri',
            'nama_lengkap' => 'required|string|max:150',
            'nim' => 'required|string|max:30',
        ]);

        $norm = fn ($s) => mb_strtolower(preg_replace('/\s+/', ' ', trim($s)));

        // Peran ikut dicocokkan: santri tidak bisa masuk lewat tab musrif, dan sebaliknya.
        $user = User::where('role', $data['role'])->where('nim', trim($data['nim']))->first();

        if (! $user || $norm($user->nama_lengkap) !== $norm($data['nama_lengkap'])) {
            return back()->withInput($r->only('role', 'nama_lengkap'))
                ->withErrors(['login' => 'Nama lengkap atau NIM tidak cocok dengan peran yang dipilih.']);
        }

        Auth::login($user);
        $r->session()->regenerate();
        return redirect('/' . $user->role . '/dashboard');
    }

    public function logout(Request $r)
    {
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();
        return redirect()->route('login');
    }
}
