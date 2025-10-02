<?php

namespace App\Http\Controllers;

use App\Models\MasterBank;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MasterBankController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = MasterBank::latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $encryptedId = encrypt($row->id);
                    return '
                        <a href="' . route('master-bank.edit', $encryptedId) . '" class="btn btn-sm btn-warning">Edit</a>
                        <button class="btn btn-sm btn-danger btn-delete" data-id="' . $encryptedId . '">Hapus</button>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('master.bank.index');
    }

    public function create()
    {
        return view('master.bank.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nama' => 'required|string|max:255',
            'Kode' => 'required|string|max:255',
            'Status' => 'required|string|max:255',
        ]);

        MasterBank::create([
            'Nama' => $request->Nama,
            'Kode' => $request->Kode,
            'Keterangan' => $request->Keterangan,
            'Status' => $request->Status,
            'UserCreated' => auth()->user()->name,
        ]);

        activity()
            ->causedBy(auth()->user()->id)
            ->withProperties(['ip' => request()->ip()])
            ->log('Menambah master bank baru: ' . $request->Nama);

        return redirect()->route('master-bank.index')->with('success', 'Master bank berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $id = decrypt($id);
        $bank = MasterBank::findOrFail($id);
        return view('master.bank.edit', compact('bank'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Nama' => 'required|string|max:255',
            'Kode' => 'required|string|max:255',
            'Status' => 'required|string|max:255',
        ]);

        $bank = MasterBank::findOrFail($id);
        $bank->update([
            'Nama' => $request->Nama,
            'Kode' => $request->Kode,
            'Keterangan' => $request->Keterangan,
            'Status' => $request->Status,
            'UserUpdated' => auth()->user()->name
        ]);

        activity()
            ->causedBy(auth()->user()->id)
            ->withProperties(['ip' => request()->ip()])
            ->log('Memperbarui master bank: ' . $request->Nama);

        return redirect()->route('master-bank.index')->with('success', 'Master bank berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $id = decrypt($id);
        $bank = MasterBank::find($id);

        if (!$bank) {
            return response()->json(['status' => 404, 'message' => 'Data tidak ditemukan']);
        }
        activity()
            ->causedBy(auth()->user()->id)
            ->withProperties(['ip' => request()->ip()])
            ->log('Menghapus master bank: ' . $bank->Nama);
        $bank->delete();



        return response()->json(['status' => 200, 'message' => 'Master bank berhasil dihapus']);
    }
}
