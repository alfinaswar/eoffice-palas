<?php

namespace App\Http\Controllers;

use App\Models\BookingList;
use App\Models\MasterAngsuran;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $encryptedId = encrypt($row->id);
                    return '
                        <a href="' . route('transaksi.show', $encryptedId) . '" class="btn btn-sm btn-info">Cek Transaksi</a>
                    ';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d-m-Y H:i') : '';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $booking = BookingList::with('getProduk', 'getCustomer')->latest()->get();
        return view('transaksi.index', compact('booking'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $id = decrypt($id);
        $booking = BookingList::with('getPenawaran')->find($id);
        $angsuran = MasterAngsuran::get();
        return view('transaksi.create', compact('booking', 'angsuran'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $generateKode = $this->generateKodeTransaksi();
        $transaksi = Transaksi::create([
            'KodeTransaksi' => $generateKode,
            'IdProduk' => $request->IdProduk,
            'TanggalTransaksi' => $request->TanggalTransaksi,
            'IdPelanggan' => $request->IdPelanggan,
            'IdPetugas' => $request->IdPetugas,
            'JenisTransaksi' => $request->JenisTransaksi,
            'DurasiPembayaran' => $request->DurasiAngsuran,
            'TotalHarga' => $request->TotalHarga,
            'UangMuka' => $request->UangMuka ?? null,
            'SisaBayar' => $request->SisaBayar ?? null,
            'Keterangan' => $request->Keterangan ?? null,
        ]);

        $durasi = $request->DurasiAngsuran;
        $besarCicilan = $request->TotalHarga / $request->DurasiAngsuran;

        for ($i = 1; $i <= $durasi; $i++) {
            TransaksiDetail::create([
                'IdTransaksi' => $transaksi->id,
                'IdPelanggan' => $request->IdPelanggan,
                'CicilanKe' => $i,
                'BesarCicilan' => $besarCicilan,
                'TotalPembayaran' => $besarCicilan,
                'Status' => 'Tidak',
                'UserCreated' => auth()->user()->name,
            ]);
        }
        activity()
            ->causedBy(auth()->user())
            ->withProperties(['ip' => request()->ip()])
            ->log('Transaksi berhasil dibuat: ' . ($transaksi->KodeTransaksi ?? ''));
        return redirect()
            ->route('transaksi.index')
            ->with('success', 'Transaksi berhasil disimpan.');
    }

    /**
     * Generate a unique transaction code.
     *
     * Format: TRX-YYYYMMDD-XXXX (incremental number per day)
     */
    private function generateKodeTransaksi()
    {
        $year = date('y');
        $month = date('n');
        $day = date('d');

        $prefix = 'TRX' . $year . $month . $day;

        $lastTransaksi = Transaksi::where('KodeTransaksi', 'like', $prefix . '%')
            ->orderBy('KodeTransaksi', 'desc')
            ->first();

        if ($lastTransaksi && preg_match('/(\d{4})$/', $lastTransaksi->KodeTransaksi, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        } else {
            $nextNumber = 1;
        }

        $kodeBaru = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        return $kodeBaru;
    }

    public function Tagihan($id)
    {
        dd($id);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $id = decrypt($id);
        $data = user::with('getTransaksi')->find($id);
        return view('transaksi.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaksi $transaksi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaksi $transaksi)
    {
        //
    }
}
