<?php

namespace App\Http\Controllers;

use App\Models\Iqob;
use App\Models\Persyaratan;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function show(string $tipe, int $id)
    {
        $user = auth()->user();

        if ($tipe === 'persyaratan') {
            $m = Persyaratan::findOrFail($id);
            $path = $m->file_path; $nama = $m->file_nama;
        } else {
            $iqob = Iqob::findOrFail($id);
            // Hanya musrif atau pemilik iqob yang boleh membuka file
            abort_unless($user->role === 'admin' || $iqob->santri_id === $user->id
                || ($user->role === 'musrif' && in_array($iqob->kamar, $user->kamarSaya())), 403);
            $path = $tipe === 'surat' ? $iqob->surat_pernyataan : $iqob->file_pengumpulan;
            $nama = $tipe === 'surat' ? $iqob->surat_nama : $iqob->file_nama;
        }

        abort_unless($path && Storage::disk('local')->exists($path), 404, 'File tidak ditemukan.');
        return response()->file(Storage::disk('local')->path($path), [
            'Content-Disposition' => 'inline; filename="' . addslashes($nama) . '"',
        ]);
    }
}
