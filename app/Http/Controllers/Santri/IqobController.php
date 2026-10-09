<?php

namespace App\Http\Controllers\Santri;

use App\Http\Controllers\Controller;
use App\Models\Iqob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IqobController extends Controller
{
    public function laporan()
    {
        $iqobs = auth()->user()->iqobs()->latest()->get();
        return view('santri.laporan', compact('iqobs'));
    }

    public function upload()
    {
        $iqobs = auth()->user()->iqobs()->where('status', '!=', 'selesai')->orderBy('batas_waktu')->get();
        return view('santri.upload', compact('iqobs'));
    }

    public function store(Request $r, Iqob $iqob)
    {
        // Santri hanya boleh mengunggah untuk iqob miliknya sendiri
        abort_unless($iqob->santri_id === auth()->id(), 403);

        if (! $iqob->bisa_upload) {
            return back()->with('error', 'Batas waktu pengumpulan sudah lewat atau iqob sudah selesai. Hubungi musrif.');
        }

        $r->validate(['file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240'],
            ['file.mimes' => 'Format file harus PDF, JPG, PNG, DOC, atau DOCX.', 'file.max' => 'Ukuran file maksimal 10 MB.']);

        if ($iqob->file_pengumpulan) {
            Storage::delete($iqob->file_pengumpulan);
        }
        $f = $r->file('file');
        $iqob->update([
            'file_pengumpulan' => $f->store('pengumpulan'),
            'file_nama' => $f->getClientOriginalName(),
            'tanggal_dikumpulkan' => now(),
            'status' => 'dikumpulkan',
        ]);
        return back()->with('ok', 'Iqob berhasil dikumpulkan. Tunggu verifikasi musrif.');
    }
}
