<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kamar;
use App\Models\Mabna;
use App\Models\MusrifKamar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class MabnaController extends Controller
{
    public function index()
    {
        $bebas = MusrifKamar::ringkas(Kamar::whereNull('mabna_id')->pluck('nomor')->all());
        return view('admin.mabna', ['mabna' => Mabna::ringkasan(), 'bebas' => $bebas]);
    }

    public function store(Request $r)
    {
        $d = $r->validate(['nama' => 'required|string|max:100|unique:mabna,nama', 'kamar' => 'nullable|string|max:100']);
        $m = Mabna::create(['nama' => $d['nama']]);
        $err = $this->aturKamar($m, $d['kamar'] ?? '');
        return back()->with($err ? 'error' : 'ok', $err ? 'Mabna dibuat, tetapi kamar gagal diatur: ' . $err : 'Mabna ditambahkan.');
    }

    public function update(Request $r, Mabna $mabna)
    {
        $d = $r->validate([
            'nama' => ['required', 'string', 'max:100', Rule::unique('mabna', 'nama')->ignore($mabna->id)],
            'kamar' => 'nullable|string|max:100',
        ]);
        $err = $this->aturKamar($mabna, $d['kamar'] ?? '');
        if ($err) return back()->with('error', $err);
        $mabna->update(['nama' => $d['nama']]);
        return back()->with('ok', $mabna->nama . ' diperbarui.');
    }

    public function destroy(Mabna $mabna)
    {
        $nomor = Kamar::where('mabna_id', $mabna->id)->pluck('nomor');
        if (User::where('mabna_id', $mabna->id)->exists() || User::where('role', 'santri')->whereIn('kamar', $nomor)->exists()) {
            return back()->with('error', 'Mabna masih punya musrif atau santri, tidak bisa dihapus.');
        }
        $mabna->delete(); // kamar otomatis jadi tanpa mabna
        return back()->with('ok', 'Mabna dihapus.');
    }

    /** Tentukan kamar yang masuk mabna ini. Kembalikan pesan error atau null. */
    private function aturKamar(Mabna $m, string $input): ?string
    {
        $input = trim($input);
        $list = $input === '' ? [] : MusrifKamar::parse($input);
        if ($list === null) return 'Format kamar salah. Contoh: 1-8 atau 1,2,5 (nomor 1 sampai 46).';

        $bentrok = Kamar::with('mabna')->whereIn('nomor', $list)->whereNotNull('mabna_id')->where('mabna_id', '!=', $m->id)->get();
        if ($bentrok->isNotEmpty()) {
            return 'Kamar ' . MusrifKamar::ringkas($bentrok->pluck('nomor')->all()) . ' sudah masuk '
                . $bentrok->pluck('mabna.nama')->unique()->implode(', ') . '.';
        }

        // Kamar yang dikeluarkan dari mabna tidak boleh masih dipakai musrif / santri
        $keluar = Kamar::where('mabna_id', $m->id)->whereNotIn('nomor', $list)->pluck('nomor')->all();
        if ($keluar) {
            if (MusrifKamar::whereIn('kamar', $keluar)->exists()) {
                return 'Sebagian kamar yang dikeluarkan masih dipegang musrif. Lepas dulu di menu Kelola Musrif.';
            }
            if (User::where('role', 'santri')->whereIn('kamar', $keluar)->exists()) {
                return 'Sebagian kamar yang dikeluarkan masih berisi santri. Pindahkan atau hapus santrinya dulu.';
            }
        }

        DB::transaction(function () use ($m, $list) {
            Kamar::where('mabna_id', $m->id)->whereNotIn('nomor', $list)->update(['mabna_id' => null]);
            if ($list) Kamar::whereIn('nomor', $list)->update(['mabna_id' => $m->id]);
        });
        return null;
    }
}
