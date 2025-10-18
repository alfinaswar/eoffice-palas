<?php

namespace App\Http\Controllers;

use App\Models\PenawaranHarga;
use App\Models\PenawaranHargaDetail;
use App\Models\Produk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PenawaranHargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = PenawaranHarga::with('DetailPenawaran')->latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $encryptedId = encrypt($row->id);
                    return '
                        <a href="' . route('penawaran-harga.edit', $encryptedId) . '" class="btn btn-sm btn-warning">Edit</a>
                        <button class="btn btn-sm btn-danger btn-delete" data-id="' . $encryptedId . '">Hapus</button>
                    ';
                })
                ->editColumn('Total', function ($row) {
                    return 'Rp ' . number_format($row->Total, 0, ',', '.');
                })
                ->editColumn('Tanggal', function ($row) {
                    return Carbon::parse($row->Tanggal)->translatedFormat('d F Y');
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('penawaran-harga.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $produk = Produk::get();
        return view('penawaran-harga.create', compact('produk'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'Tanggal' => 'required|date',
            'NamaPelanggan' => 'required|string|max:255',
            'Keterangan' => 'nullable|string',
            'IdProduk' => 'required|array|min:1',

        ]);
        $data = $request->all();
        $year = date('y');
        $month = date('m');
        $lastPenawaran = PenawaranHarga::whereYear('Tanggal', date('Y'))
            ->whereMonth('Tanggal', $month)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastPenawaran && isset($lastPenawaran->Nomor)) {
            $lastNomor = $lastPenawaran->Nomor;
            $lastSeq = intval(substr($lastNomor, -4));
            $nextSeq = $lastSeq + 1;
        } else {
            $nextSeq = 1;
        }
        $newNomor = sprintf('PNW%s%s%04d', $year, $month, $nextSeq);

        $header = PenawaranHarga::create([
            'Nomor' => $newNomor,
            'Tanggal' => $data['Tanggal'],
            'NamaPelanggan' => $data['NamaPelanggan'],
            'Total' => str_replace('.', '', $data['Total']),
            'Keterangan' => $data['Keterangan'],
        ]);

        if (isset($data['IdProduk']) && is_array($data['IdProduk'])) {
            $count = count($data['IdProduk']);
            for ($i = 0; $i < $count; $i++) {
                PenawaranHargaDetail::create([
                    'IdPenawaran' => $header->id,
                    'IdProduk' => $data['IdProduk'][$i],
                    'Jumlah' => isset($data['Jumlah'][$i]) ? $data['Jumlah'][$i] : 1,
                    'Harga' => isset($data['HargaAsli'][$i]) ? str_replace('.', '', $data['HargaAsli'][$i]) : 0,
                    'HargaPenawaran' => isset($data['Harga'][$i]) ? str_replace('.', '', $data['Harga'][$i]) : 0,
                    'Subtotal' => isset($data['Subtotal'][$i]) ? str_replace('.', '', $data['Subtotal'][$i]) : 0,
                    'Diskon' => isset($data['Diskon'][$i]) ? $data['Diskon'][$i] : 0,
                    'JenisDiskon' => isset($data['JenisDiskon'][$i]) ? $data['JenisDiskon'][$i] : null,
                ]);
            }
        }
        activity()
            ->causedBy(auth()->user()->id)
            ->withProperties(['ip' => request()->ip()])
            ->log('Menambah penawaran harga baru: ' . $request->Nomor . ' untuk pelanggan: ' . $request->NamaPelanggan);
        return redirect()->route('penawaran-harga.index')->with('success', 'Penawaran harga berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PenawaranHarga $penawaranHarga)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $id = decrypt($id);
        $produk = Produk::get();
        $penawaran = PenawaranHarga::with('DetailPenawaran')->findOrFail($id);

        return view('penawaran-harga.edit', compact('penawaran', 'produk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PenawaranHarga $penawaranHarga)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PenawaranHarga $penawaranHarga)
    {
        //
    }
}
