<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function index()
    {
        return view('profil');
    }

    public function update(Request $r)
    {
        $r->validate(['foto' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048'],
            ['foto.image' => 'File harus berupa gambar.', 'foto.mimes' => 'Format foto: JPG, PNG, atau WEBP.', 'foto.max' => 'Ukuran foto maksimal 2 MB.']);
        $u = $r->user();
        if ($u->foto) Storage::delete($u->foto);
        $u->foto = $r->file('foto')->store('foto');
        $u->save();
        return back()->with('ok', 'Foto profil diperbarui.');
    }

    public function hapus(Request $r)
    {
        $u = $r->user();
        if ($u->foto) Storage::delete($u->foto);
        $u->foto = null;
        $u->save();
        return back()->with('ok', 'Foto profil dihapus.');
    }

    public function foto(User $user)
    {
        $me = auth()->user();
        abort_unless($me->id === $user->id || in_array($me->role, ['musrif', 'admin']), 403);
        abort_unless($user->foto && Storage::exists($user->foto), 404);
        return response()->file(Storage::path($user->foto), ['Cache-Control' => 'private, max-age=86400']);
    }
}
