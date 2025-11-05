<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuLaporanController extends Controller
{
    public function Omset(Request $request)
    {
        return view('laporan.omset.index');
    }
}
