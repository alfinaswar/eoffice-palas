<?php

namespace App\Http\Controllers;

use App\Models\MasterKantor;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MasterKantorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = MasterKantor::latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $encryptedId = encrypt($row->id);
                    return '
                        <a href="' . route('master-kantor.edit', $encryptedId) . '" class="btn btn-sm btn-warning">Edit</a>
                        <button class="btn btn-sm btn-danger btn-delete" data-id="' . $encryptedId . '">Hapus</button>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('master.kantor.index');
    }

    public function create()
    {
        return view('master.kantor.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nama' => 'required|string|max:255',
            'Kode' => 'required|string|max:255',
            'Alamat' => 'required|string|max:255',
        ]);

        MasterKantor::create([
            'Nama' => $request->Nama,
            'Kode' => $request->Kode,
            'Alamat' => $request->Alamat,
            'UserCreated' => auth()->user()->name,
        ]);

        activity()
            ->causedBy(auth()->user())
            ->withProperties(['ip' => request()->ip()])
            ->log('Menambah master kantor baru: ' . $request->Nama);

        return redirect()->route('master-kantor.index')->with('success', 'Master kantor berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $id = decrypt($id);
        $kantor = MasterKantor::findOrFail($id);
        return view('master.kantor.edit', compact('kantor'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Nama' => 'required|string|max:255',
            'Kode' => 'required|string|max:255',
            'Alamat' => 'required|string|max:255',
        ]);

        $kantor = MasterKantor::findOrFail($id);
        $kantor->update([
            'Nama' => $request->Nama,
            'Kode' => $request->Kode,
            'Alamat' => $request->Alamat,
            'UserUpdated' => auth()->user()->name
        ]);

        activity()
            ->causedBy(auth()->user())
            ->withProperties(['ip' => request()->ip()])
            ->log('Memperbarui master kantor: ' . $request->Nama);

        return redirect()->route('master-kantor.index')->with('success', 'Master kantor berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $id = decrypt($id);
        $kantor = MasterKantor::find($id);

        if (!$kantor) {
            return response()->json(['status' => 404, 'message' => 'Data tidak ditemukan']);
        }
        activity()
            ->causedBy(auth()->user())
            ->withProperties(['ip' => request()->ip()])
            ->log('Menghapus master kantor: ' . $kantor->Nama);
        $kantor->delete();

        return response()->json(['status' => 200, 'message' => 'Master kantor berhasil dihapus']);
    }
}
