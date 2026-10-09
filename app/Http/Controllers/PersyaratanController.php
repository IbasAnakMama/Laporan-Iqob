<?php

namespace App\Http\Controllers;

use App\Models\Persyaratan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PersyaratanController extends Controller
{
    public function index()
    {
        return view('persyaratan', ['daftar' => Persyaratan::latest()->get()]);
    }

    public function store(Request $r)
    {
        $r->validate(['judul' => 'required|string|max:200', 'file' => 'required|file|mimes:pdf|max:10240']);
        $f = $r->file('file');
        Persyaratan::create([
            'judul' => $r->judul,
            'file_path' => $f->store('persyaratan'),
            'file_nama' => $f->getClientOriginalName(),
            'uploaded_by' => auth()->id(),
        ]);
        return back()->with('ok', 'Persyaratan iqob berhasil diunggah.');
    }

    public function destroy(Persyaratan $persyaratan)
    {
        Storage::delete($persyaratan->file_path);
        $persyaratan->delete();
        return back()->with('ok', 'Persyaratan dihapus.');
    }
}
