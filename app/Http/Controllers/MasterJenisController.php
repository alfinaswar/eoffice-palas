<?php

namespace App\Http\Controllers;

use App\Models\MasterJenis;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MasterJenisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = MasterJenis::with('getKantor')->latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $encryptedId = encrypt($row->id);
                    return '
                        <a href="' . route('master-jenis-produk.edit', $encryptedId) . '" class="btn btn-sm btn-warning">Edit</a>
                        <button class="btn btn-sm btn-danger btn-delete" data-id="' . $encryptedId . '">Hapus</button>
                    ';
                })
                ->editColumn('KodeKantor', function ($row) {
                    return $row->getKantor ? $row->getKantor->Nama : $row->KodeKantor;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('master.jenis.index');
    }

    public function create()
    {
        return view('master.jenis.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nama' => 'required|string|max:255',
            'Keterangan' => 'nullable|string|max:255',
        ]);

        MasterJenis::create([
            'Nama' => $request->Nama,
            'KodeKantor' => $request->KodeKantor ?? auth()->user()->KodeKantor,
            'Keterangan' => $request->Keterangan,
            'UserCreated' => auth()->user()->name,
        ]);

        activity()
            ->causedBy(auth()->user()->id)
            ->withProperties(['ip' => request()->ip()])
            ->log('Menambah master jenis produk baru: ' . $request->Nama);

        return redirect()->route('master-jenis-produk.index')->with('success', 'Master jenis produk berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $id = decrypt($id);
        $jenis = MasterJenis::findOrFail($id);
        return view('master.jenis.edit', compact('jenis'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Nama' => 'required|string|max:255',
            'Keterangan' => 'nullable|string|max:255',
        ]);

        $id = decrypt($id);
        $jenis = MasterJenis::findOrFail($id);

        $jenis->update([
            'Nama' => $request->Nama,
            'Keterangan' => $request->Keterangan,
            'KodeKantor' => $request->KodeKantor ?? auth()->user()->KodeKantor,
            'UserUpdated' => auth()->user()->name,
        ]);

        activity()
            ->causedBy(auth()->user()->id)
            ->withProperties(['ip' => request()->ip()])
            ->log('Mengupdate master jenis produk: ' . $request->Nama);

        return redirect()->route('master-jenis-produk.index')->with('success', 'Master jenis produk berhasil diupdate.');
    }

    public function destroy($id)
    {
        $id = decrypt($id);
        $jenis = MasterJenis::find($id);

        if (!$jenis) {
            return response()->json(['status' => 404, 'message' => 'Data tidak ditemukan']);
        }
        activity()
            ->causedBy(auth()->user()->id)
            ->withProperties(['ip' => request()->ip()])
            ->log('Menghapus master jenis produk: ' . $jenis->Nama);
        $jenis->delete();

        return response()->json(['status' => 200, 'message' => 'Master jenis produk berhasil dihapus']);
    }
}
