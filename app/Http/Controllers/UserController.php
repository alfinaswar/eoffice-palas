<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MasterBank;
use App\Models\MasterShift;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View
    {
        $data = User::orderBy('id', 'DESC')->paginate(5);
        return view('users.index', compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $roles = Role::pluck('name', 'name')->all();
        $bank = MasterBank::get();
        return view('users.create', compact('roles', 'bank'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
        $input['jenis_user'] = 'Karyawan';
        $input['KodeKantor'] = $request->KodeKantor ?? auth()->user()->KodeKantor;
        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        activity()
            ->causedBy(auth()->user())
            ->withProperties(['ip' => request()->ip()])
            ->log('Akun Karyawan berhasil dibuat: ' . $request->name);

        return redirect()
            ->route('users.index')
            ->with('success', 'Akun' . $request->name . 'berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): View
    {
        $user = User::find($id);
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $user = User::with('getProvinsi', 'getKota', 'getKecamatan', 'getKelurahan')->find($id);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();
        $bank = MasterBank::get();
        return view('users.edit', compact('user', 'roles', 'userRole', 'bank'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'nullable|string|max:50',
            'name' => 'required|string|max:200',
            'email' => 'required|email|unique:users,email,' . $id,
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

        $user = User::findOrFail($id);

        $input = $request->except('roles', 'password');
        if ($request->hasFile('foto_profil')) {
            $file = $request->file('foto_profil');
            $file->storeAs('public/foto_profil', $file->getClientOriginalName());
            $input['foto_profil'] = $file->getClientOriginalName();
        }

        if ($request->filled('password')) {
            $input['password'] = \Hash::make($request->input('password'));
        }

        $input['jenis_user'] = 'Karyawan';
        $input['KodeKantor'] = $request->KodeKantor ?? auth()->user()->KodeKantor;

        $user->update($input);

        // Update roles
        $user->syncRoles($request->input('roles'));

        activity()
            ->causedBy(auth()->user())
            ->withProperties(['ip' => request()->ip()])
            ->log('Akun Karyawan berhasil diupdate: ' . $request->name);

        return redirect()
            ->route('users.index')
            ->with('success', 'Akun ' . $request->name . ' berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): RedirectResponse
    {
        User::find($id)->delete();
        return redirect()
            ->route('users.index')
            ->with('success', 'User deleted successfully');
    }
}
