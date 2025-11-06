<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\TransaksiDetail;
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
        $today = Carbon::today();
        $totalPendapatanHariIni = TransaksiDetail::whereDate('DibayarPada', $today)
            ->where('Status', 'Lunas')
            ->sum('TotalPembayaran');

        // Minggu ini (mulai Senin sampai Minggu)
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $totalPendapatanMingguIni = TransaksiDetail::whereBetween('DibayarPada', [$startOfWeek, $endOfWeek])
            ->where('Status', 'Lunas')
            ->sum('TotalPembayaran');

        // Bulan ini
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $pendapatanBulanIni = TransaksiDetail::whereBetween('DibayarPada', [$startOfMonth, $endOfMonth])
            ->where('Status', 'Lunas')
            ->sum('TotalPembayaran');

        $RiwayatTransaksi = TransaksiDetail::with('getCustomer', 'transaksi.getProduk')
            ->whereDate('created_at', $today)
            ->where('Status', 'Lunas')
            ->get();
        // Perbaiki query agar setiap record hasil query memiliki relasi produk (getProduk) yang benar, gunakan with() setelah get()
        $ProdukTerpopuler = Transaksi::selectRaw('IdProduk, COUNT(*) as total_terjual')
            ->groupBy('IdProduk')
            ->orderByDesc('total_terjual')
            ->limit(10)
            ->get();
        $ProdukTerpopuler = $ProdukTerpopuler->load('getProduk');
        // dd($ProdukTerpopuler);
        $RiwayatTransaksi = TransaksiDetail::with('getCustomer', 'transaksi.getProduk')->whereDate('created_at', $today)->get();
        return view('home', compact('ProdukTerpopuler', 'RiwayatTransaksi', 'totalPendapatanHariIni', 'totalPendapatanMingguIni', 'pendapatanBulanIni'));
    }
}
