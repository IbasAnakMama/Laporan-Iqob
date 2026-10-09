<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Iqob;
use App\Models\Kamar;
use App\Models\Mabna;
use App\Models\MusrifKamar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MusrifController extends Controller
{
    public function index(Request $r)
    {
        $q = User::with('mabna')->where('role', 'musrif')->orderBy('mabna_id')->orderBy('nama_lengkap');
        if ($r->filled('mabna')) $q->where('mabna_id', $r->mabna);
        $jumlah = Iqob::selectRaw('musrif_id, count(*) total')->groupBy('musrif_id')->pluck('total', 'musrif_id');
        $kamarMusrif = MusrifKamar::orderBy('kamar')->get()->groupBy('musrif_id')
            ->map(fn ($g) => MusrifKamar::ringkas($g->pluck('kamar')->all()));

        // Kamar yang sudah masuk mabna tetapi belum ada musrif-nya
        $dipegang = MusrifKamar::pluck('kamar')->all();
        $belum = [];
        foreach (Mabna::with(['kamars' => fn ($k) => $k->orderBy('nomor')])->orderBy('nama')->get() as $m) {
            $sisa = array_diff($m->kamars->pluck('nomor')->all(), $dipegang);
            if ($sisa) $belum[$m->nama] = MusrifKamar::ringkas($sisa);
        }
        return view('admin.musrif', ['musrif' => $q->get(), 'mabnaList' => Mabna::orderBy('nama')->get(),
            'jumlah' => $jumlah, 'kamarMusrif' => $kamarMusrif, 'belum' => $belum]);
    }

    public function store(Request $r)
    {
        $d = $r->validate([
            'nama_lengkap' => 'required|string|max:150',
            'nim' => 'required|string|max:30|unique:users,nim',
            'mabna_id' => 'nullable|exists:mabna,id',
            'kamar' => 'nullable|string|max:100',
        ]);
        $u = User::create(['nama_lengkap' => $d['nama_lengkap'], 'nim' => $d['nim'], 'role' => 'musrif']);
        $err = $this->atur($u, $d['mabna_id'] ?? null, $d['kamar'] ?? '');
        if ($err) return back()->with('error', 'Musrif ditambahkan, tetapi pembagian gagal: ' . $err);
        return back()->with('ok', 'Akun musrif ditambahkan.');
    }

    public function kamar(Request $r, User $user)
    {
        abort_unless($user->role === 'musrif', 403);
        $r->validate(['mabna_id' => 'nullable|exists:mabna,id', 'kamar' => 'nullable|string|max:100']);
        $err = $this->atur($user, $r->input('mabna_id') ?: null, (string) $r->input('kamar', ''));
        return back()->with($err ? 'error' : 'ok', $err ?: 'Pembagian untuk ' . $user->nama_lengkap . ' diperbarui.');
    }

    public function destroy(User $user)
    {
        abort_unless($user->role === 'musrif', 403);
        if (Iqob::where('musrif_id', $user->id)->exists()) {
            return back()->with('error', 'Musrif ini masih punya data iqob, tidak bisa dihapus.');
        }
        $user->delete();
        return back()->with('ok', 'Akun musrif dihapus.');
    }

    /** Tetapkan mabna dan kamar musrif. Kembalikan pesan error atau null. */
    private function atur(User $u, ?int $mabnaId, string $input): ?string
    {
        $input = trim($input);
        $list = $input === '' ? [] : MusrifKamar::parse($input);
        if ($list === null) return 'Format kamar salah. Contoh: 40-43 atau 40,41,45.';
        if ($list && ! $mabnaId) return 'Pilih mabna dulu sebelum mengisi kamar.';

        if ($list) {
            $valid = Kamar::where('mabna_id', $mabnaId)->pluck('nomor')->map(fn ($x) => (int) $x)->all();
            $luar = array_diff($list, $valid);
            if ($luar) return 'Kamar ' . MusrifKamar::ringkas($luar) . ' bukan bagian mabna ini. Atur dulu di menu Kelola Mabna.';
        }

        $bentrok = MusrifKamar::whereIn('kamar', $list)->where('musrif_id', '!=', $u->id)->get();
        if ($bentrok->isNotEmpty()) {
            $nama = User::whereIn('id', $bentrok->pluck('musrif_id'))->pluck('nama_lengkap')->implode(', ');
            return 'Kamar ' . MusrifKamar::ringkas($bentrok->pluck('kamar')->all()) . ' sudah dipegang ' . $nama . '.';
        }

        DB::transaction(function () use ($u, $mabnaId, $list) {
            $u->mabna_id = $mabnaId;
            $u->save();
            MusrifKamar::where('musrif_id', $u->id)->delete();
            foreach ($list as $k) MusrifKamar::create(['musrif_id' => $u->id, 'kamar' => $k]);
        });
        return null;
    }
}
