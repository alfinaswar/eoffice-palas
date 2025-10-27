<?php

namespace App\Http\Controllers;

use App\Models\MasterAngsuran;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MasterAngsuranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = MasterAngsuran::latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $encryptedId = encrypt($row->id);
                    return '
                        <a href="' . route('master-angsuran.edit', $encryptedId) . '" class="btn btn-sm btn-warning">Edit</a>
                        <button class="btn btn-sm btn-danger btn-delete" data-id="' . $encryptedId . '">Hapus</button>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('master.angsuran.index');
    }

    public function create()
    {
        return view('master.angsuran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'JumlahPembayaran' => 'required|string|max:255',
            'KonversiTahun' => 'required|string|max:255',
        ]);

        MasterAngsuran::create([
            'JumlahPembayaran' => $request->JumlahPembayaran,
            'KonversiTahun' => $request->KonversiTahun,
            'UserCreated' => auth()->user()->name,
        ]);

        activity()
            ->causedBy(auth()->user()->id)
            ->withProperties(['ip' => request()->ip()])
            ->log('Menambah master angsuran baru: ' . $request->JumlahPembayaran . ' - ' . $request->KonversiTahun);

        return redirect()->route('master-angsuran.index')->with('success', 'Master angsuran berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $id = decrypt($id);
        $angsuran = MasterAngsuran::findOrFail($id);
        return view('master.angsuran.edit', compact('angsuran'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'JumlahPembayaran' => 'required|string|max:255',
            'KonversiTahun' => 'required|string|max:255',
        ]);

        $angsuran = MasterAngsuran::findOrFail($id);
        $angsuran->update([
            'JumlahPembayaran' => $request->JumlahPembayaran,
            'KonversiTahun' => $request->KonversiTahun,
            'UserUpdated' => auth()->user()->name
        ]);

        activity()
            ->causedBy(auth()->user()->id)
            ->withProperties(['ip' => request()->ip()])
            ->log('Memperbarui master angsuran: ' . $request->JumlahPembayaran . ' - ' . $request->KonversiTahun);

        return redirect()->route('master-angsuran.index')->with('success', 'Master angsuran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $id = decrypt($id);
        $angsuran = MasterAngsuran::find($id);

        if (!$angsuran) {
            return response()->json(['status' => 404, 'message' => 'Data tidak ditemukan']);
        }
        activity()
            ->causedBy(auth()->user()->id)
            ->withProperties(['ip' => request()->ip()])
            ->log('Menghapus master angsuran: ' . $angsuran->JumlahPembayaran . ' - ' . $angsuran->KonversiTahun);
        $angsuran->delete();

        return response()->json(['status' => 200, 'message' => 'Master angsuran berhasil dihapus']);
    }
}
