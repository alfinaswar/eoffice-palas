<?php

namespace App\Http\Controllers;

use App\Models\MasterJenisPengeluaran;
use App\Models\TransaksiKeluar;
use App\Models\TransaksiKeluarDetail;
use Illuminate\Http\Request;
use Laraindo\RupiahFormat;
use Yajra\DataTables\Facades\DataTables;

class TransaksiKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = TransaksiKeluar::with('getJenis', 'getPetugas', 'getKantor')->latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('Jenis', function ($row) {
                    return optional($row->getJenis)->Nama ?? '-';
                })
                ->editColumn('IdPetugas', function ($row) {
                    return optional($row->getPetugas)->name ?? '-';
                })
                ->editColumn('KodeKantor', function ($row) {
                    return optional($row->getKantor)->Nama ?? '-';
                })
                ->editColumn('Total', function ($row) {
                    return RupiahFormat::currency($row->Total);
                })
                ->addColumn('action', function ($row) {
                    $encryptedId = encrypt($row->id);
                    return '
                        <a href="' . route('transaksi-keluar.edit', $encryptedId) . '" class="btn btn-sm btn-warning">Edit</a>
                        <button class="btn btn-sm btn-danger btn-delete" data-id="' . $encryptedId . '">Hapus</button>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('transaksi.keluar.index');
    }

    public function create()
    {
        $jenis = MasterJenisPengeluaran::get();
        return view('transaksi.keluar.create', compact('jenis'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'Jenis' => 'required|string|max:255',
            'Tanggal' => 'required',
        ]);
        $buktiBeliPath = null;
        if ($request->hasFile('BuktiBeli')) {
            $file = $request->file('BuktiBeli');
            $fileName = 'bukti_beli_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $buktiBeliPath = $file->storeAs('bukti_struk_belanja', $fileName, 'public');
        } else {
            $fileName = null;
        }

        $header = TransaksiKeluar::create([
            'Nomor' => $this->generateNomorTransaksiKeluar(),
            'Jenis' => $request->Jenis,
            'Jumlah' => is_array($request->Jumlah) ? array_sum($request->Jumlah) : $request->Jumlah,
            'Total' => preg_replace('/[^0-9]/', '', $request->GrandTotal),
            'Keterangan' => $request->Keterangan,
            'Tanggal' => $request->Tanggal,
            'BuktiBeli' => $fileName,
            'IdPetugas' => auth()->user()->id,
            'KodeKantor' => auth()->user()->KodeKantor,
            'UserCreated' => auth()->user()->name,
        ]);
        if (is_array($request->NamaBarang) && count($request->NamaBarang) > 0) {
            foreach ($request->NamaBarang as $i => $namaBarang) {
                TransaksiKeluarDetail::create([
                    'IdTransaksiKeluar' => $header->id,
                    'NamaBarang' => $namaBarang,
                    'Jumlah' => isset($request->Jumlah[$i]) ? $request->Jumlah[$i] : 0,
                    'Harga' => isset($request->Harga[$i]) ? preg_replace('/[^0-9]/', '', $request->Harga[$i]) : 0,
                    'Total' => isset($request->Total[$i]) ? preg_replace('/[^0-9]/', '', $request->Total[$i]) : 0,
                    'Keterangan' => isset($request->KeteranganDetail[$i]) ? $request->KeteranganDetail[$i] : null,
                    'Tanggal' => $request->Tanggal,
                    'IdPetugas' => isset($request->IdPetugas[$i]) ? $request->IdPetugas[$i] : auth()->user()->id,
                    'KodeKantor' => auth()->user()->KodeKantor,
                    'UserCreated' => auth()->user()->name,
                ]);
            }
        }

        activity()
            ->causedBy(auth()->user()->id)
            ->withProperties(['ip' => request()->ip()])
            ->log('Menambah master bank baru: ' . $request->Nama);

        return redirect()->route('transaksi-keluar.index')->with('success', 'Data Transaksi Keluar Berhasil Diatmabahkan');
    }

    /**
     * Generate Nomor Transaksi Keluar dengan format:
     * TKyyMMddNNNN (TK + tahun 2 digit + bulan 2 digit + tanggal 2 digit + running number 4 digit)
     */
    private function generateNomorTransaksiKeluar()
    {
        $date = now();
        $prefix = 'TK';
        $year = $date->format('y');
        $month = $date->format('m');
        $day = $date->format('d');

        $last = TransaksiKeluar::whereDate('created_at', $date->toDateString())
            ->orderBy('Nomor', 'desc')
            ->first();

        if ($last && preg_match('/^TK' . $year . $month . $day . '(\d{4})$/', $last->Nomor, $matches)) {
            $increment = (int) $matches[1] + 1;
        } else {
            $increment = 1;
        }

        $nomor = $prefix . $year . $month . $day . str_pad($increment, 4, '0', STR_PAD_LEFT);
        return $nomor;
    }

    public function edit($id)
    {
        $id = decrypt($id);
        $jenis = MasterJenisPengeluaran::get();
        $transaksiKeluar = TransaksiKeluar::with('getDetail')->find($id);
        return view('transaksi.keluar.edit', compact('jenis', 'transaksiKeluar'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Jenis' => 'required|string|max:255',
            'Tanggal' => 'required',
        ]);
        $buktiBeliPath = null;
        if ($request->hasFile('BuktiBeli')) {
            $file = $request->file('BuktiBeli');
            $fileName = 'bukti_beli_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $buktiBeliPath = $file->storeAs('bukti_struk_belanja', $fileName, 'public');
        } else {
            $fileName = null;
        }
        $transaksi = TransaksiKeluar::findOrFail($id);
        $transaksi->update([
            'Jenis' => $request->Jenis,
            'Jumlah' => is_array($request->Jumlah) ? array_sum($request->Jumlah) : $request->Jumlah,
            'Total' => $request->GrandTotal,
            'Keterangan' => $request->Keterangan,
            'Tanggal' => $request->Tanggal,
            'BuktiBeli' => $fileName,
            'IdPetugas' => auth()->user()->id,
            'KodeKantor' => auth()->user()->KodeKantor,
            'UserUpdate' => auth()->user()->name,
        ]);

        TransaksiKeluarDetail::where('IdTransaksiKeluar', $transaksi->id)->delete();

        if (is_array($request->NamaBarang) && count($request->NamaBarang) > 0) {
            foreach ($request->NamaBarang as $i => $namaBarang) {
                TransaksiKeluarDetail::create([
                    'IdTransaksiKeluar' => $transaksi->id,
                    'NamaBarang' => $namaBarang,
                    'Jumlah' => isset($request->Jumlah[$i]) ? $request->Jumlah[$i] : 0,
                    'Harga' => isset($request->Harga[$i]) ? preg_replace('/[^0-9]/', '', $request->Harga[$i]) : 0,
                    'Total' => isset($request->Total[$i]) ? preg_replace('/[^0-9]/', '', $request->Total[$i]) : 0,
                    'Keterangan' => isset($request->KeteranganDetail[$i]) ? $request->KeteranganDetail[$i] : null,
                    'Tanggal' => $request->Tanggal,
                    'IdPetugas' => isset($request->IdPetugas[$i]) ? $request->IdPetugas[$i] : auth()->user()->id,
                    'KodeKantor' => auth()->user()->KodeKantor,
                    'UserCreated' => auth()->user()->name,
                ]);
            }
        }

        activity()
            ->causedBy(auth()->user()->id)
            ->withProperties(['ip' => request()->ip()])
            ->log('Memperbarui transaksi keluar: ' . $transaksi->Nomor);

        return redirect()->route('transaksi-keluar.index')->with('success', 'Transaksi keluar berhasil diperbarui.');
        return redirect()->route('master-bank.index')->with('success', 'Master bank berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $id = decrypt($id);
        $transaksi = TransaksiKeluar::find($id);

        if (!$transaksi) {
            return response()->json(['status' => 404, 'message' => 'Data tidak ditemukan']);
        }

        // Hapus detail transaksi keluar
        TransaksiKeluarDetail::where('IdTransaksiKeluar', $transaksi->id)->delete();

        // Log aktivitas
        activity()
            ->causedBy(auth()->user()->id)
            ->withProperties(['ip' => request()->ip()])
            ->log('Menghapus transaksi keluar: ' . $transaksi->Nomor);

        // Hapus transaksi keluar utama
        $transaksi->delete();

        return response()->json(['status' => 200, 'message' => 'Transaksi keluar dan detail berhasil dihapus']);
    }
}
