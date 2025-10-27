<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class MasterCustomer extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::orderByDesc('name');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $encryptedId = encrypt($row->id);
                    return '
                        <a href="' . route('customer.index', $encryptedId) . '" class="btn btn-sm btn-warning">Edit</a>
                        <button class="btn btn-sm btn-danger btn-delete" data-id="' . $encryptedId . '">Hapus</button>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('master.customer.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();
        return view('master.customer.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'nullable|string|max:50',
            'name' => 'required|string|max:200',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'nohp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:255',
            'provinsi' => 'nullable|string|max:100',
            'kota' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kelurahan' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'foto_profil' => 'nullable|file|image|max:2048',
            'status_perkawinan' => 'nullable|in:Belum Menikah,Menikah,Cerai',
            'agama' => 'nullable|string|max:50',
            'golongan_darah' => 'nullable|string|max:5',
            'npwp' => 'nullable|string|max:30',
            'no_bpjs' => 'nullable|string|max:30',
            'no_rekening' => 'nullable|string|max:50',
            'nama_bank' => 'nullable|exists:master_banks,id',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $input = $request->except('roles');
        if ($request->hasFile('foto_profil')) {
            $file = $request->file('foto_profil');
            $file->storeAs('public/foto_profil', $file->getClientOriginalName());
            $input['foto_profil'] = $file->getClientOriginalName();
        }
        $input['password'] = Hash::make($request->input('password'));
        $input['jenis_user'] = 'Customer';
        $input['KodeKantor'] = $request->KodeKantor ?? auth()->user()->KodeKantor;
        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        activity()
            ->causedBy(auth()->user())
            ->withProperties(['ip' => request()->ip()])
            ->log('Akun Customer berhasil dibuat: ' . $request->name);

        return redirect()
            ->route('customer.index')
            ->with('success', 'Akun' . $request->name . 'berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
