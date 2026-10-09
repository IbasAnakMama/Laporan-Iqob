<?php

namespace App\Http\Controllers\Musrif;

use App\Http\Controllers\Controller;
use App\Models\Kamar;
use App\Models\Mabna;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SantriController extends Controller
{
    public function index(Request $r)
    {
        $q = User::with('kamarRel.mabna')->where('role', 'santri')->whereIn('kamar', auth()->user()->kamarSaya())
            ->orderBy('kamar')->orderBy('nama_lengkap');
        if ($r->filled('kamar')) $q->where('kamar', $r->kamar);
        if ($r->filled('mabna')) $q->whereIn('kamar', Kamar::where('mabna_id', $r->mabna)->pluck('nomor'));
        return view('musrif.santri', [
            'santri' => $q->paginate(20)->withQueryString(),
            'mabnaList' => Mabna::with(['kamars' => fn ($k) => $k->orderBy('nomor')])->orderBy('nama')->get(),
        ]);
    }

    public function store(Request $r)
    {
        $d = $r->validate([
            'nama_lengkap' => 'required|string|max:150',
            'nim' => 'required|string|max:30|unique:users,nim',
            'kamar' => ['required', 'integer', Rule::in(auth()->user()->kamarSaya())],
        ]);
        User::create($d + ['role' => 'santri']);
        return back()->with('ok', 'Santri ditambahkan.');
    }

    public function destroy(User $user)
    {
        abort_unless($user->role === 'santri' && in_array($user->kamar, auth()->user()->kamarSaya()), 403);
        $user->delete();
        return back()->with('ok', 'Santri dihapus.');
    }
}
