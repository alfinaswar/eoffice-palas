<?php

namespace App\Http\Controllers;

use App\Models\BookingList;
use App\Models\MasterAngsuran;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Pdf;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::where('jenis_user', 'Customer')->latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $encryptedId = encrypt($row->id);
                    return '
                        <a href="' . route('transaksi.list-tagihan', $encryptedId) . '" class="btn btn-sm btn-info">Cek Transaksi</a>
                    ';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d-m-Y H:i') : '';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $booking = BookingList::with('getProduk', 'getCustomer')->latest()->get();
        return view('transaksi.masuk.index', compact('booking'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $id = decrypt($id);
        $booking = BookingList::with('getPenawaran', 'getProduk')->find($id);
        $angsuran = MasterAngsuran::get();
        return view('transaksi.masuk.create', compact('booking', 'angsuran'));
    }

    public function PrintKwitansi($id)
    {
        $id = decrypt($id);
        $data = TransaksiDetail::where('id', $id)->first();

        if (!$data) {
            return redirect()->back()->with('error', 'Data Pembayaran tidak ditemukan');
        }
        $width = 21 * 28.35;  // 595.35 points (lebar)
        $height = 15 * 28.35;  // 425.25 points (tinggi)

        $customPaper = array(0, 0, $width, $height);

        $pdf = Pdf::loadView('transaksi.masuk.cetak-kwitansi', compact('data'))
            ->setPaper($customPaper, 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'dpi' => 96,
                'defaultFont' => 'Arial',
                'margin_top' => 0,
                'margin_right' => 0,
                'margin_bottom' => 0,
                'margin_left' => 0
            ]);
        return $pdf->stream('kwitansi-' . $data->KodeBayar . '.pdf');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'TanggalTransaksi' => 'required|date',
            'IdPelanggan' => 'required',
            'IdPetugas' => 'required',
            'JenisTransaksi' => 'required',
            'TotalHarga' => 'required|numeric',
        ]);

        $generateKode = $this->generateKodeTransaksi();

        $transaksi = Transaksi::create([
            'KodeTransaksi' => $generateKode,
            'IdProduk' => $request->IdProduk,
            'TanggalTransaksi' => $request->TanggalTransaksi,
            'IdPelanggan' => $request->IdPelanggan,
            'IdPetugas' => $request->IdPetugas,
            'JenisTransaksi' => $request->JenisTransaksi,
            'DurasiPembayaran' => $request->DurasiAngsuran,
            'TotalHarga' => str_replace(['.', ','], '', $request->TotalHarga),
            'UangMuka' => $request->UangMuka ?? null,
            'SisaBayar' => $request->SisaBayar ?? null,
            'Keterangan' => $request->Keterangan ?? null,
        ]);

        if ($request->JenisTransaksi == 'Kredit' && !empty($request->DurasiAngsuran)) {
            $angsuran = MasterAngsuran::find($request->DurasiAngsuran);

            if ($angsuran) {
                $jumlahBulan = intval($angsuran->JumlahPembayaran);
                $totalHarga = (float) str_replace(['.', ','], '', $request->TotalHarga);
                $besarCicilan = $jumlahBulan > 0 ? $totalHarga / $jumlahBulan : 0;
                $tanggalJatuhTempoAwal = $angsuran->TanggalJatuhTempo;
                if (!$tanggalJatuhTempoAwal) {
                    $tanggalJatuhTempoAwal = $request->TanggalTransaksi;
                }

                for ($i = 1; $i <= $jumlahBulan; $i++) {
                    $carbonStart = \Carbon\Carbon::parse($request->TanggalTransaksi)->startOfMonth()->addMonths($i - 1);
                    $hariJatuhTempo = intval($angsuran->TanggalJatuhTempo);
                    if ($hariJatuhTempo < 1 || $hariJatuhTempo > 28) {
                        $hariJatuhTempo = 1;
                    }
                    $tanggalJatuhTempo = $carbonStart->setDay($hariJatuhTempo)->format('Y-m-d');

                    TransaksiDetail::create([
                        'IdTransaksi' => $transaksi->id,
                        'IdPelanggan' => $request->IdPelanggan,
                        'CicilanKe' => $i,
                        'BesarCicilan' => $besarCicilan,
                        'TotalPembayaran' => $besarCicilan,
                        'TanggalJatuhTempo' => $tanggalJatuhTempo,
                        'Status' => 'Tidak',
                        'UserCreated' => auth()->user()->name,
                    ]);
                }
            }
        } else {
            TransaksiDetail::create([
                'IdTransaksi' => $transaksi->id,
                'IdPelanggan' => $request->IdPelanggan,
                'CicilanKe' => 1,
                'BesarCicilan' => str_replace(['.', ','], '', $request->TotalHarga),
                'TotalPembayaran' => str_replace(['.', ','], '', $request->TotalHarga),
                'TanggalJatuhTempo' => $request->TanggalTransaksi,
                'Status' => 'Lunas',
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
        $id = decrypt($id);
        $data = user::with('getTransaksi')->find($id);
        return view('transaksi.masuk.show', compact('data'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $id = decrypt($id);
        $data = Transaksi::with('getTransaksi', 'getUser', 'getProduk', 'getDurasiPembayaran')->find($id);
        return view('transaksi.masuk.tagihan-pelanggan', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaksi $transaksi)
    {
        //
    }

    public function PembayaranTagihan(Request $request)
    {
        $request->validate([
            'DibayarOleh' => 'required|string',
        ]);

        $id_transaksi_detail = decrypt($request->input('IdTransaksiDetail'));
        $transaksiDetail = TransaksiDetail::with('transaksi')->findOrFail($id_transaksi_detail);

        $transaksiDetail->KodeBayar = $this->generateKodeBayar();
        $transaksiDetail->Status = 'Lunas';
        $transaksiDetail->DibayarPada = now();
        $transaksiDetail->DibayarOleh = $request->input('DibayarOleh');
        $transaksiDetail->save();

        $transaksi = $transaksiDetail->transaksi;
        if ($transaksi) {
            $totalSisa = $transaksi->getTransaksi()->where('Status', '!=', 'Lunas')->sum('BesarCicilan');
            $transaksi->SisaBayar = $totalSisa;
            $transaksi->save();
        }

        activity()
            ->causedBy(auth()->user()->id)
            ->withProperties([
                'ip' => $request->ip(),
                'transaksi_detail_id' => $transaksiDetail->id,
                'kode_bayar' => $transaksiDetail->KodeBayar,
                'dibayar_oleh' => $transaksiDetail->DibayarOleh,
                'nominal' => $transaksiDetail->BesarCicilan,
            ])
            ->log('Pembayaran tagihan berhasil untuk Kode Bayar: ' . $transaksiDetail->KodeBayar);

        return redirect()->back()->with('success', 'Pembayaran berhasil diproses.');
    }

    public function generateKodeBayar()
    {
        do {
            $microtime = sprintf('%.0f', microtime(true) * 1000);
            $kode = "PAY{$microtime}" . rand(100, 999);
            $exists = TransaksiDetail::where('KodeBayar', $kode)->exists();
        } while ($exists);

        return $kode;
    }

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
