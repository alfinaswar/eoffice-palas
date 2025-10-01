<?php

namespace App\Http\Controllers;

use App\Models\MasterGrade;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MasterGradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = MasterGrade::with('getKantor')->latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $encryptedId = encrypt($row->id);
                    return '
                        <a href="' . route('master-grade.edit', $encryptedId) . '" class="btn btn-sm btn-warning">Edit</a>
                        <button class="btn btn-sm btn-danger btn-delete" data-id="' . $encryptedId . '">Hapus</button>
                    ';
                })
                ->editColumn('KodeKantor', function ($row) {
                    return $row->getKantor ? $row->getKantor->Nama : $row->KodeKantor;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('master.grade.index');
    }

    public function create()
    {
        return view('master.grade.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nama' => 'required|string|max:255',
            'Keterangan' => 'nullable|string|max:255',
        ]);

        MasterGrade::create([
            'Nama' => $request->Nama,
            'KodeKantor' => $request->KodeKantor ?? auth()->user()->KodeKantor,
            'Keterangan' => $request->Keterangan,
            'UserCreated' => auth()->user()->name,
        ]);

        activity()
            ->causedBy(auth()->user())
            ->withProperties(['ip' => request()->ip()])
            ->log('Menambah master grade baru: ' . $request->Nama);

        return redirect()->route('master-grade.index')->with('success', 'Master grade berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $id = decrypt($id);
        $grade = MasterGrade::findOrFail($id);
        return view('master.grade.edit', compact('grade'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Nama' => 'required|string|max:255',
            'Keterangan' => 'nullable|string|max:255',
        ]);

        $id = decrypt($id);
        $grade = MasterGrade::findOrFail($id);

        $grade->update([
            'Nama' => $request->Nama,
            'Keterangan' => $request->Keterangan,
            'KodeKantor' => $request->KodeKantor ?? auth()->user()->KodeKantor,
            'UserUpdated' => auth()->user()->name,
        ]);

        activity()
            ->causedBy(auth()->user())
            ->withProperties(['ip' => request()->ip()])
            ->log('Mengupdate master grade: ' . $request->Nama);

        return redirect()->route('master-grade.index')->with('success', 'Master grade berhasil diupdate.');
    }

    public function destroy($id)
    {
        $id = decrypt($id);
        $grade = MasterGrade::find($id);

        if (!$grade) {
            return response()->json(['status' => 404, 'message' => 'Data tidak ditemukan']);
        }
        activity()
            ->causedBy(auth()->user())
            ->withProperties(['ip' => request()->ip()])
            ->log('Menghapus master grade: ' . $grade->Nama);
        $grade->delete();

        return response()->json(['status' => 200, 'message' => 'Master grade berhasil dihapus']);
    }
}
