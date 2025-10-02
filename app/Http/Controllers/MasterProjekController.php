<?php

namespace App\Http\Controllers;

use App\Models\MasterProjek;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MasterProjekController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = MasterProjek::with('getKantor')->latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $encryptedId = encrypt($row->id);
                    return '
                        <a href="' . route('master-proyek.edit', $encryptedId) . '" class="btn btn-sm btn-warning">Edit</a>
                        <button class="btn btn-sm btn-danger btn-delete" data-id="' . $encryptedId . '">Hapus</button>
                    ';
                })
                ->editColumn('KodeKantor', function ($row) {
                    return $row->getKantor ? $row->getKantor->Nama : $row->KodeKantor;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('master.projek.index');
    }

    public function create()
    {
        return view('master.projek.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'NamaProyek' => 'required|string|max:255',
            'AlamatProyek' => 'nullable|string|max:255',
        ]);

        MasterProjek::create([
            'KodeKantor' => $request->KodeKantor ?? auth()->user()->KodeKantor,
            'NamaProyek' => $request->NamaProyek,
            'AlamatProyek' => $request->AlamatProyek,
            'UserCreated' => auth()->user()->name,
        ]);

        activity()
            ->causedBy(auth()->user()->id)
            ->withProperties(['ip' => request()->ip()])
            ->log('Menambah master projek baru: ' . $request->NamaProyek);

        return redirect()->route('master-proyek.index')->with('success', 'Master projek berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $id = decrypt($id);
        $projek = MasterProjek::findOrFail($id);
        return view('master.projek.edit', compact('projek'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'NamaProyek' => 'required|string|max:255',
            'AlamatProyek' => 'nullable|string|max:255',
        ]);

        $id = decrypt($id);
        $projek = MasterProjek::findOrFail($id);

        $projek->update([
            'KodeKantor' => $request->KodeKantor ?? auth()->user()->KodeKantor,
            'NamaProyek' => $request->NamaProyek,
            'AlamatProyek' => $request->AlamatProyek,
            'UserUpdated' => auth()->user()->name,
        ]);

        activity()
            ->causedBy(auth()->user()->id)
            ->withProperties(['ip' => request()->ip()])
            ->log('Mengupdate master projek: ' . $request->NamaProyek);

        return redirect()->route('master-proyek.index')->with('success', 'Master projek berhasil diupdate.');
    }

    public function destroy($id)
    {
        $id = decrypt($id);
        $projek = MasterProjek::find($id);

        if (!$projek) {
            return response()->json(['status' => 404, 'message' => 'Data tidak ditemukan']);
        }
        activity()
            ->causedBy(auth()->user()->id)
            ->withProperties(['ip' => request()->ip()])
            ->log('Menghapus master projek: ' . $projek->NamaProyek);
        $projek->delete();

        return response()->json(['status' => 200, 'message' => 'Master projek berhasil dihapus']);
    }
}
