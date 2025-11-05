<?php

namespace App\Http\Controllers;

use App\Models\TransaksiDetail;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MenuLaporanController extends Controller
{
    public function Omset(Request $request)
    {
        if ($request->ajax()) {
            $data = TransaksiDetail::with('getCustomer')->latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $encryptedId = encrypt($row->id);
                    return '
                        <a href="' . route('transaksi.list-tagihan', $encryptedId) . '" class="btn btn-sm btn-info">Cek Transaksi</a>
                    ';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d-m-Y H:i') : '';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('laporan.omset.index');
    }
}
