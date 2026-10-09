<?php

namespace App\Http\Controllers\Musrif;

use App\Http\Controllers\Controller;
use App\Models\Iqob;
use App\Models\Kamar;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    private function data(Request $r)
    {
        $bulan = Carbon::createFromFormat('Y-m', $r->input('bulan', now()->format('Y-m')))->startOfMonth();
        $q = Iqob::with('santri.kamarRel.mabna')->whereBetween('created_at', [$bulan, $bulan->copy()->endOfMonth()])
            ->whereIn('kamar', auth()->user()->kamarSaya())->orderBy('kamar')->orderBy('created_at');
        if ($r->filled('kamar')) $q->where('kamar', $r->kamar);
        if ($r->filled('mabna') && auth()->user()->role === 'admin') {
            $q->whereIn('kamar', Kamar::where('mabna_id', $r->mabna)->pluck('nomor'));
        }
        return [$bulan, $q->get()];
    }

    public function index(Request $r)
    {
        [$bulan, $iqobs] = $this->data($r);
        return view('musrif.laporan', compact('bulan', 'iqobs'));
    }

    /** Spreadsheet (CSV UTF-8 berpemisah titik koma, langsung terbuka rapi di Excel). */
    public function export(Request $r)
    {
        [$bulan, $iqobs] = $this->data($r);
        $nama = 'laporan-iqob-' . $bulan->format('Y-m') . '.csv';

        return response()->streamDownload(function () use ($iqobs) {
            $o = fopen('php://output', 'w');
            fwrite($o, "\xEF\xBB\xBF");
            fputcsv($o, ['No', 'Tanggal Diberikan', 'Nama Lengkap', 'NIM', 'Mabna', 'Kamar', 'Tingkat', 'Jenis Iqob',
                'Batas Waktu', 'Tanggal Dikumpulkan', 'Status', 'Catatan Musrif'], ';');
            foreach ($iqobs as $i => $x) {
                fputcsv($o, [$i + 1, $x->created_at->format('d/m/Y'), $x->santri->nama_lengkap, $x->santri->nim, $x->santri->mabna_nama ?? '-', $x->kamar,
                    $x->tingkat_label, $x->jenis_label, $x->batas_waktu->format('d/m/Y H:i'),
                    $x->tanggal_dikumpulkan?->format('d/m/Y H:i') ?? '-', $x->status_label, $x->catatan_musrif], ';');
            }
            fclose($o);
        }, $nama, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
