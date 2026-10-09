<?php

namespace App\Http\Controllers;

use App\Models\Iqob;
use App\Models\Mabna;
use App\Models\Motivasi;
use App\Models\User;

class DashboardController extends Controller
{
    public function santri()
    {
        $iqobs = auth()->user()->iqobs();
        $stat = [
            'total' => (clone $iqobs)->count(),
            'belum' => (clone $iqobs)->where('status', 'belum_dikumpulkan')->count(),
            'menunggu' => (clone $iqobs)->where('status', 'dikumpulkan')->count(),
            'selesai' => (clone $iqobs)->where('status', 'selesai')->count(),
        ];
        $deadline = (clone $iqobs)->where('status', 'belum_dikumpulkan')->orderBy('batas_waktu')->take(5)->get();
        return view('santri.dashboard', ['motivasi' => Motivasi::hariIni(), 'stat' => $stat, 'deadline' => $deadline]);
    }

    public function admin()
    {
        $stat = [
            'musrif' => User::where('role', 'musrif')->count(),
            'santri' => User::where('role', 'santri')->count(),
            'iqob' => Iqob::count(),
            'motivasi' => Motivasi::count(),
        ];
        return view('admin.dashboard', ['motivasi' => Motivasi::hariIni(), 'stat' => $stat, 'ringkasan' => Mabna::ringkasan()]);
    }

    public function musrif()
    {
        $k = auth()->user()->kamarSaya();
        $stat = [
            'santri' => User::where('role', 'santri')->whereIn('kamar', $k)->count(),
            'aktif' => Iqob::whereIn('kamar', $k)->where('status', 'belum_dikumpulkan')->count(),
            'menunggu' => Iqob::whereIn('kamar', $k)->where('status', 'dikumpulkan')->count(),
            'selesai' => Iqob::whereIn('kamar', $k)->where('status', 'selesai')->count(),
        ];
        $perlu = Iqob::with('santri')->whereIn('kamar', $k)->where('status', 'dikumpulkan')->orderBy('tanggal_dikumpulkan')->take(8)->get();
        return view('musrif.dashboard', ['motivasi' => Motivasi::hariIni(), 'stat' => $stat, 'perlu' => $perlu]);
    }
}
