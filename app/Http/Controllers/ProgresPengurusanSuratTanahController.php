<?php

namespace App\Http\Controllers;

use App\Models\MasterBank;
use App\Models\MasterProjek;
use App\Models\ProgresPengurusanSuratTanah;
use App\Models\TransaksiKeuangan;
use Illuminate\Http\Request;
use Laraindo\RupiahFormat;
use Yajra\DataTables\DataTables;

class ProgresPengurusanSuratTanahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ProgresPengurusanSuratTanah::with('getProyek')->orderByDesc('created_at');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $encryptedId = encrypt($row->id);
                    return '
                        <a href="' . route('pengurusan-tanah.edit', $encryptedId) . '" class="btn btn-sm btn-warning">Edit</a>
                        <button class="btn btn-sm btn-danger btn-delete" data-id="' . $encryptedId . '">Hapus</button>
                    ';
                })
                ->editColumn('Tanggal', function ($row) {
                    return $row->Tanggal ? date('d-m-Y', strtotime($row->Tanggal)) : '-';
                })
                ->editColumn('Legal', function ($row) {
                    return RupiahFormat::currency($row->Legal);
                })
                ->editColumn('KodeProyek', function ($row) {
                    return $row->getProyek && $row->getProyek->NamaProyek
                        ? $row->getProyek->NamaProyek
                        : '-';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('progres-pengurusan-tanah.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $proyeks = MasterProjek::get();
        $bank = MasterBank::get();
        return view('progres-pengurusan-tanah.create', compact('proyeks', 'bank'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'KodeProyek' => 'required',
            'Tanggal' => 'required',
            'Legal' => 'required',
            'NamaBank' => 'required',
            'Deskripsi' => 'required',
            'Keterangan' => 'nullable',
        ]);

        // Tambahkan userCreate (UserCreated) ke data yang divalidasi
        $validated['UserCreated'] = auth()->user()->name ?? 'system';
        $validated['Legal'] = preg_replace('/[^\d]/', '', $request->Legal);

        $validated['KodeKantor'] = auth()->user()->KodeKantor ?? 'system';

        $progres = ProgresPengurusanSuratTanah::create($validated);


        $saldoSebelumnya = TransaksiKeuangan::where('NamaBank', $request->NamaBank)
            ->orderBy('Tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->value('SaldoSetelah');

        $saldoSebelumnya = $saldoSebelumnya ? preg_replace('/[^\d]/', '', $saldoSebelumnya) : 0;
        $nominalKeluar = preg_replace('/[^\d]/', '', $request->GrandTotal);
        $saldoSetelah = (int) $saldoSebelumnya - (int) $nominalKeluar;

        $transaksi = TransaksiKeuangan::create([
            'Tanggal' => $request->Tanggal,
            'Jenis' => 'OUT',
            'Kategori' => 'PengurusanSuratTanah',
            'Deskripsi' => 'Pengeluaran kas untuk pengurusan surat tanah proyek: ' . $request->KodeProyek,
            'Nominal' => $nominalKeluar,
            'NamaBank' => $request->NamaBank,
            'RefType' => 'ProgresPengurusanSuratTanah',
            'RefId' => $progres->id,
            'SaldoSetelah' => $saldoSetelah,
            'UserCreated' => auth()->user()->name,
        ]);

        activity()
            ->causedBy(auth()->user()->id)
            ->withProperties(['ip' => request()->ip()])
            ->log('Menambah pengurusan/progres surat tanah untuk proyek: ' . $request->KodeProyek);

        return redirect()->route('pengurusan-tanah.index')->with('success', 'Data progres pengurusan surat tanah berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProgresPengurusanSuratTanah $progresPengurusanSuratTanah)
    {
        $proyeks = MasterProjek::get();
        $bank = MasterBank::get();
        return view('progres-pengurusan-tanah.show', compact('progresPengurusanSuratTanah'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $id = decrypt($id);
        $progresPengurusanSuratTanah = ProgresPengurusanSuratTanah::findOrFail($id);
        $proyeks = MasterProjek::get();
        $bank = MasterBank::get();
        return view('progres-pengurusan-tanah.edit', compact('progresPengurusanSuratTanah', 'proyeks', 'bank'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProgresPengurusanSuratTanah $progresPengurusanSuratTanah)
    {
        $validated = $request->validate([
            'KodeProyek' => 'required',
            'Tanggal' => 'required',
            'Legal' => 'required',
            'NamaBank' => 'required',
            'Deskripsi' => 'required',
            'Keterangan' => 'nullable',
        ]);
        $validated['Legal'] = preg_replace('/[^\d]/', '', $request->Legal);
        $validated['UserUpdated'] = auth()->user()->name ?? 'system';
        $validated['KodeKantor'] = auth()->user()->KodeKantor ?? 'system';

        $progresPengurusanSuratTanah->update($validated);
        $saldoSebelumnya = TransaksiKeuangan::where('NamaBank', $request->NamaBank)
            ->orderBy('Tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->value('SaldoSetelah');
        $saldoSebelumnya = $saldoSebelumnya ? preg_replace('/[^\d]/', '', $saldoSebelumnya) : 0;

        // Ambil Legal sebagai nominal keluar (biaya legalitas)
        $nominalKeluar = preg_replace('/[^\d]/', '', $request->Legal);
        $saldoSetelah = (int) $saldoSebelumnya - (int) $nominalKeluar;

        $transaksi = TransaksiKeuangan::updateOrCreate(
            [
                'RefType' => 'ProgresPengurusanSuratTanah',
                'RefId' => $progresPengurusanSuratTanah->id,
            ],
            [
                'Tanggal' => $request->Tanggal,
                'Jenis' => 'OUT',
                'Kategori' => 'PengurusanSuratTanah',
                'Deskripsi' => 'Pengeluaran kas untuk pengurusan surat tanah proyek: ' . $request->KodeProyek,
                'Nominal' => $nominalKeluar,
                'NamaBank' => $request->NamaBank,
                'SaldoSetelah' => $saldoSetelah,
                'UserCreated' => auth()->user()->name,
            ]
        );

        activity()
            ->causedBy(auth()->user()->id)
            ->withProperties(['ip' => request()->ip()])
            ->log('Mengubah progres pengurusan surat tanah untuk proyek: ' . $request->KodeProyek);

        return redirect()->route('pengurusan-tanah.index')->with('success', 'Data progres pengurusan surat tanah berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $id = decrypt($id);
        $progres = ProgresPengurusanSuratTanah::find($id);

        if (!$progres) {
            return response()->json(['status' => 404, 'message' => 'Data tidak ditemukan']);
        }

        activity()
            ->causedBy(auth()->user()->id)
            ->withProperties(['ip' => request()->ip()])
            ->log('Menghapus progres pengurusan surat tanah: ' . $progres->KodeProyek);

        $progres->delete();

        return response()->json(['status' => 200, 'message' => 'Progres pengurusan surat tanah berhasil dihapus']);
    }
}
