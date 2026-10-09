<?php

namespace App\Http\Controllers\Musrif;

use App\Http\Controllers\Controller;
use App\Models\Iqob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class IqobController extends Controller
{
    public function index(Request $r)
    {
        $q = Iqob::with('santri')->whereIn('kamar', auth()->user()->kamarSaya())->latest();
        foreach (['kamar', 'status', 'tingkat', 'jenis'] as $f) {
            if ($r->filled($f)) $q->where($f, $r->$f);
        }
        return view('musrif.iqob-index', ['iqobs' => $q->paginate(15)->withQueryString()]);
    }

    public function create()
    {
        return view('musrif.iqob-create');
    }

    public function santriByKamar(int $kamar)
    {
        abort_unless(in_array($kamar, auth()->user()->kamarSaya()), 403);
        return User::where('role', 'santri')->where('kamar', $kamar)->orderBy('nama_lengkap')
            ->get(['id', 'nama_lengkap', 'nim']);
    }

    public function store(Request $r)
    {
        $d = $r->validate([
            'kamar' => ['required', 'integer', Rule::in(auth()->user()->kamarSaya())],
            'santri_ids' => 'required|array|min:1',
            'santri_ids.*' => 'integer|exists:users,id',
            'tingkat' => 'required|in:ringan,sedang,berat',
            'jenis' => 'required|in:talim_afkar,talim_quran,ubudiyah,soskeg',
            'batas_waktu' => 'required|date|after:now',
            'keterangan' => 'nullable|string|max:2000',
            'surat' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $santri = User::where('role', 'santri')->where('kamar', $d['kamar'])->whereIn('id', $d['santri_ids'])->get();
        abort_if($santri->isEmpty(), 422, 'Santri tidak valid.');

        $surat = $r->file('surat');
        $master = $surat ? $surat->store('surat') : null;

        foreach ($santri as $i => $s) {
            $path = null;
            if ($master) {
                // Tiap santri punya salinan sendiri supaya penghapusan satu iqob tidak merusak yang lain
                $path = $i === 0 ? $master : 'surat/' . uniqid('', true) . '.pdf';
                if ($i > 0) Storage::copy($master, $path);
            }
            Iqob::create([
                'santri_id' => $s->id,
                'musrif_id' => auth()->id(),
                'kamar' => $s->kamar,
                'tingkat' => $d['tingkat'],
                'jenis' => $d['jenis'],
                'keterangan' => $d['keterangan'] ?? null,
                'batas_waktu' => $d['batas_waktu'],
                'surat_pernyataan' => $path,
                'surat_nama' => $surat?->getClientOriginalName(),
            ]);
        }
        return redirect()->route('musrif.iqob.index')->with('ok', 'Iqob diberikan ke ' . $santri->count() . ' santri.');
    }

    private function cek(Iqob $iqob): void
    {
        abort_unless(in_array($iqob->kamar, auth()->user()->kamarSaya()), 403, 'Kamar ini bukan tanggung jawab Anda.');
    }

    public function edit(Iqob $iqob)
    {
        $this->cek($iqob);
        return view('musrif.iqob-edit', ['iqob' => $iqob->load('santri')]);
    }

    public function update(Request $r, Iqob $iqob)
    {
        $this->cek($iqob);
        $d = $r->validate([
            'status' => 'required|in:belum_dikumpulkan,dikumpulkan,selesai',
            'batas_waktu' => 'required|date',
            'catatan_musrif' => 'nullable|string|max:2000',
        ]);
        $iqob->update($d);
        return redirect()->route('musrif.iqob.index')->with('ok', 'Iqob diperbarui.');
    }

    public function destroy(Iqob $iqob)
    {
        $this->cek($iqob);
        Storage::delete(array_filter([$iqob->surat_pernyataan, $iqob->file_pengumpulan]));
        $iqob->delete();
        return back()->with('ok', 'Iqob dihapus.');
    }
}
