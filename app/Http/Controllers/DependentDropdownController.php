<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DependentDropdownController extends Controller
{
    public function provinces()
    {
        return \Indonesia::allProvinces();
    }

    // Ubah parameter $request menjadi opsional, dan ambil id dari parameter langsung
    public function cities($id)
    {
        return \Indonesia::findProvince($id, ['cities'])->cities->pluck('name', 'id');
    }

    public function districts($id)
    {
        return \Indonesia::findCity($id, ['districts'])->districts->pluck('name', 'id');
    }

    public function villages($id)
    {
        return \Indonesia::findDistrict($id, ['villages'])->villages->pluck('name', 'id');
    }
}
