<?php

namespace App\Http\Controllers;

use App\Models\MasterJenisPengeluaran;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MasterJenisPengeluaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = MasterJenisPengeluaran::latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $encryptedId = encrypt($row->id);
                    return '
                        <a href="' . route('master-pengeluaran.edit', $encryptedId) . '" class="btn btn-sm btn-warning">Edit</a>
                        <button class="btn btn-sm btn-danger btn-delete" data-id="' . $encryptedId . '">Hapus</button>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('master.jenis-pengeluaran.index');
    }

    public function create()
    {
        return view('master.jenis-pengeluaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nama' => 'required|string|max:255',
        ]);

        MasterJenisPengeluaran::create([
            'Nama' => $request->Nama,
            'UserCreated' => auth()->user()->name,
        ]);

        activity()
            ->causedBy(auth()->user()->id)
            ->withProperties(['ip' => request()->ip()])
            ->log('Menambah master jenis pengeluaran baru: ' . $request->Nama);

        return redirect()->route('master-pengeluaran.index')->with('success', 'Master jenis pengeluaran berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $id = decrypt($id);
        $jenisPengeluaran = MasterJenisPengeluaran::findOrFail($id);
        return view('master.jenis-pengeluaran.edit', compact('jenisPengeluaran'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Nama' => 'required|string|max:255',
        ]);

        $jenisPengeluaran = MasterJenisPengeluaran::findOrFail($id);
        $jenisPengeluaran->update([
            'Nama' => $request->Nama,
            'UserUpdated' => auth()->user()->name,
        ]);

        activity()
            ->causedBy(auth()->user()->id)
            ->withProperties(['ip' => request()->ip()])
            ->log('Memperbarui master jenis pengeluaran: ' . $request->Nama);

        return redirect()->route('master-jenis-pengeluaran.index')->with('success', 'Master jenis pengeluaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $id = decrypt($id);
        $jenisPengeluaran = MasterJenisPengeluaran::find($id);

        if (!$jenisPengeluaran) {
            return response()->json(['status' => 404, 'message' => 'Data tidak ditemukan']);
        }
        activity()
            ->causedBy(auth()->user()->id)
            ->withProperties(['ip' => request()->ip()])
            ->log('Menghapus master jenis pengeluaran: ' . $jenisPengeluaran->Nama);
        $jenisPengeluaran->delete();

        return response()->json(['status' => 200, 'message' => 'Master jenis pengeluaran berhasil dihapus']);
    }
}
