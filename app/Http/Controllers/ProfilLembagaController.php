<?php

namespace App\Http\Controllers;

use App\Models\ProfilLembaga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilLembagaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = ProfilLembaga::first();
        return view('profil-lembaga.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'telepon' => 'nullable|string|max:50',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:255',
            'provinsi' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'deskripsi' => 'nullable|string',
        ]);

        $profil = ProfilLembaga::first();

        if ($request->hasFile('logo')) {
            $logoFile = $request->file('logo');
            $logoName = 'logo_' . time() . '.' . $logoFile->getClientOriginalExtension();
            $logoPath = $logoFile->storeAs('uploads/logo', $logoName, 'public');

            if ($profil && $profil->logo) {
                Storage::disk('public')->delete($profil->logo);
            }

            $validated['logo'] = $logoPath;
        } elseif ($profil) {
            // If editing and no new logo uploaded, do not change logo
            unset($validated['logo']);
        }

        if ($profil) {
            $profil->update($validated);
        } else {
            $profil = ProfilLembaga::create($validated);
        }

        return redirect()->route('profil-lembaga.index')->with('success', 'Profil Lembaga berhasil diperbarui.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProfilLembaga $profilLembaga)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProfilLembaga $profilLembaga)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProfilLembaga $profilLembaga)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProfilLembaga $profilLembaga)
    {
        //
    }
}
