<?php

namespace App\Http\Controllers;

use App\Models\PenawaranHarga;
use App\Models\PenawaranHargaDetail;
use App\Models\Produk;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
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
            $data = PenawaranHarga::with('DetailPenawaran', 'getCustomer')->latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $encryptedId = encrypt($row->id);
                    return '
                        <a href="' . route('penawaran-harga.edit', $encryptedId) . '" class="btn btn-sm" style="background-color: #20c997; color: #fff; border-color: #20c997;">Edit</a>
                        <a href="' . route('penawaran-harga.cetakPengajuan', $encryptedId) . '" class="btn btn-sm" style="background-color: #6f42c1; color: #fff; border-color: #6f42c1;" target="_blank">Cetak Dokumen</a>
                        <a href="' . route('penawaran-harga.Approval', $encryptedId) . '" class="btn btn-sm btn-warning">Approval</a>
                        <button class="btn btn-sm btn-delete" style="background-color: #fd7e14; color: #fff; border-color: #fd7e14;" data-id="' . $encryptedId . '">Hapus</button>
                    ';
                })
                ->editColumn('Total', function ($row) {
                    return 'Rp ' . number_format($row->Total, 0, ',', '.');
                })
                ->editColumn('Tanggal', function ($row) {
                    return Carbon::parse($row->Tanggal)->translatedFormat('d F Y');
                })
                ->addColumn('Nomor', function ($row) {
                    $encryptedId = encrypt($row->id);
                    return '<a href="' . route('penawaran-harga.show', $encryptedId) . '">' . e($row->Nomor) . '</a>';
                })
                ->editColumn('NamaPelanggan', function ($row) {
                    return optional($row->getCustomer)->name ?? $row->NamaPelanggan;
                })
                ->rawColumns(['action', 'Nomor'])
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
        $customer = User::where('jenis_user', 'Customer')->get();
        return view('penawaran-harga.create', compact('produk', 'customer'));
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
    public function show($id)
    {
        $id = decrypt($id);
        $produk = Produk::get();
        $penawaran = PenawaranHarga::with('DetailPenawaran', 'getCustomer')->findOrFail($id);

        return view('penawaran-harga.show', compact('penawaran', 'produk'));
    }

    public function AccPengajuan($id)
    {
        $id = decrypt($id);
        $penawaran = PenawaranHarga::findOrFail($id);

        $penawaran->StatusAcc1 = 'Y';
        $penawaran->DisetujuiPada1 = now();
        $penawaran->DisetujuiOleh1 = auth()->user()->id;
        $penawaran->save();

        activity()
            ->causedBy(auth()->user()->id)
            ->withProperties(['ip' => request()->ip()])
            ->log('Menyetujui pengajuan penawaran harga: ' . $penawaran->Nomor);

        return redirect()
            ->route('penawaran-harga.index')
            ->with('success', 'Penawaran harga berhasil disetujui.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $id = decrypt($id);
        $produk = Produk::get();
        $customer = User::where('jenis_user', 'Customer')->get();
        $penawaran = PenawaranHarga::with('DetailPenawaran', 'getCustomer')->findOrFail($id);

        return view('penawaran-harga.edit', compact('penawaran', 'produk', 'customer'));
    }

    public function Approval($id)
    {
        $id = decrypt($id);
        $produk = Produk::get();
        $penawaran = PenawaranHarga::with('DetailPenawaran', 'getCustomer')->findOrFail($id);

        return view('penawaran-harga.approval-harga', compact('penawaran', 'produk'));
    }

    public function DownloadPengajuan($id)
    {
        $id = decrypt($id);
        $penawaran = PenawaranHarga::with('DetailPenawaran', 'getCustomer')->findOrFail($id);
        $produk = Produk::get();

        $pdfView = view('penawaran-harga.cetak-pdf', compact('penawaran', 'produk'))->render();

        $pdf = Pdf::loadHTML($pdfView);

        $filename = 'Penawaran_Harga_' . $penawaran->Nomor . '.pdf';
        return $pdf->stream($filename);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'Tanggal' => 'required|date',
            'NamaPelanggan' => 'required|string|max:255',
            'Keterangan' => 'nullable|string',
            'IdProduk' => 'required|array|min:1',
        ]);

        $data = $request->all();
        $id = decrypt($id);

        // Update header penawaran
        $header = PenawaranHarga::findOrFail($id);
        $header->Tanggal = $data['Tanggal'];
        $header->NamaPelanggan = $data['NamaPelanggan'];
        $header->Total = str_replace('.', '', $data['Total']);
        $header->Keterangan = $data['Keterangan'] ?? null;
        $header->save();

        // Hapus semua detail sebelumnya
        PenawaranHargaDetail::where('IdPenawaran', $header->id)->delete();

        // Buat ulang detail penawaran
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
            ->log('Mengupdate penawaran harga: ' . $header->Nomor . ' untuk pelanggan: ' . $header->NamaPelanggan);
        return redirect()->route('penawaran-harga.index')->with('success', 'Penawaran harga berhasil diperbarui.');
    }

    public function UpdateApproval(Request $request, $id)
    {
        $request->validate([
            'Tanggal' => 'required|date',
            'NamaPelanggan' => 'required|string|max:255',
            'Keterangan' => 'nullable|string',
        ]);

        $data = $request->all();
        $id = decrypt($id);

        // Update header penawaran
        $header = PenawaranHarga::findOrFail($id);
        $header->Tanggal = $data['Tanggal'];
        $header->NamaPelanggan = $data['NamaPelanggan'];
        $header->Total = str_replace('.', '', $data['Total']);
        $header->Keterangan = $data['Keterangan'] ?? null;
        $header->save();

        PenawaranHargaDetail::where('IdPenawaran', $header->id)
            ->where('id', $request->ApproveRadio)
            ->update(['Status' => 'Y']);

        activity()
            ->causedBy(auth()->user()->id)
            ->withProperties(['ip' => request()->ip()])
            ->log('Mengupdate penawaran harga: ' . $header->Nomor . ' untuk pelanggan: ' . $header->NamaPelanggan);
        return redirect()->route('penawaran-harga.index')->with('success', 'Penawaran harga berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PenawaranHarga $penawaranHarga)
    {
        //
    }
}
