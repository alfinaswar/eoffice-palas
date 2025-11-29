<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\TransaksiKeuangan;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(): View
    {
        $totalUangMasukHariIni = TransaksiKeuangan::where('Jenis', 'IN')
            ->whereDate('Tanggal', Carbon::today())
            ->sum('Nominal');
        $totalUangKeluarHariIni = TransaksiKeuangan::where('Jenis', 'OUT')
            ->whereDate('Tanggal', Carbon::today())
            ->sum('Nominal');
        $totalUangMasukBulanIni = TransaksiKeuangan::where('Jenis', 'IN')
            ->whereMonth('Tanggal', Carbon::now()->month)
            ->whereYear('Tanggal', Carbon::now()->year)
            ->sum('Nominal');


        $months = [];
        $labelMonths = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('Y-m');
            $labelMonths[] = $date->format('M');
        }

        // Uang Masuk per bulan
        $totalUangMasukPerBulan = [];
        foreach ($months as $ym) {
            $total = TransaksiKeuangan::where('Jenis', 'IN')
                ->whereYear('Tanggal', substr($ym, 0, 4))
                ->whereMonth('Tanggal', substr($ym, 5, 2))
                ->sum('Nominal');
            $totalUangMasukPerBulan[] = (int) $total;
        }

        // Uang Keluar per bulan
        $totalUangKeluarPerBulan = [];
        foreach ($months as $ym) {
            $totalKeluar = TransaksiKeuangan::where('Jenis', 'OUT')
                ->whereYear('Tanggal', substr($ym, 0, 4))
                ->whereMonth('Tanggal', substr($ym, 5, 2))
                ->sum('Nominal');
            $totalUangKeluarPerBulan[] = (int) $totalKeluar;
        }

        $chartBar2Labels = $labelMonths;
        $chartBar2Data = $totalUangMasukPerBulan;
        $chartBarKeluarLabels = $labelMonths;
        $chartBarKeluarData = $totalUangKeluarPerBulan;

        // dd($chartBar2Data, $chartBarKeluarData);
        return view('home', compact(
            'chartBar2Data',
            'chartBar2Labels',
            'totalUangMasukHariIni',
            'totalUangKeluarHariIni',
            'totalUangMasukBulanIni',
            'chartBarKeluarLabels',
            'chartBarKeluarData'
        ));
    }
}
