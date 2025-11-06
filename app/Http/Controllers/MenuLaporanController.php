<?php

namespace App\Http\Controllers;

use App\Exports\OmsetExport;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Pdf;
class MenuLaporanController extends Controller
{
    public function Omset(Request $request)
    {
        if ($request->ajax()) {
            $year = $request->tahun ?? date('Y');
            $months = [
                '01' => 'Januari',
                '02' => 'Februari',
                '03' => 'Maret',
                '04' => 'April',
                '05' => 'Mei',
                '06' => 'Juni',
                '07' => 'Juli',
                '08' => 'Agustus',
                '09' => 'September',
                '10' => 'Oktober',
                '11' => 'November',
                '12' => 'Desember',
            ];
            $data = TransaksiDetail::selectRaw("MONTH(DibayarPada) as BulanNum, SUM(TotalPembayaran) as TotalOmset")
                ->where('Status', 'Lunas')
                ->whereYear('DibayarPada', $year)
                ->groupBy('BulanNum')
                ->orderBy('BulanNum', 'asc')
                ->get()
                ->keyBy(function ($item) {
                    return str_pad($item->BulanNum, 2, '0', STR_PAD_LEFT);
                });
            $result = [];
            foreach ($months as $num => $namaBulan) {
                $totalOmset = isset($data[$num]) ? $data[$num]->TotalOmset : 0;
                $result[] = (object) [
                    'Bulan' => $year . '-' . $num,
                    'TotalOmset' => $totalOmset,
                ];
            }
            return DataTables::of(collect($result))
                ->addIndexColumn()
                ->addColumn('Bulan', function ($row) use ($months) {
                    $bulanNum = substr($row->Bulan, 5, 2);
                    $tahun = substr($row->Bulan, 0, 4);
                    return ($months[$bulanNum] ?? $bulanNum) . ' ' . $tahun;
                })
                ->addColumn('TotalOmset', function ($row) {
                    return 'Rp ' . number_format($row->TotalOmset, 0, ',', '.');
                })
                ->rawColumns(['Bulan', 'TotalOmset'])
                ->make(true);
        }
        return view('laporan.omset.index');
    }
    public function DownloadOmset(Request $request)
    {
        $tahunDari = $request->input('tahun_dari', date('Y'));
        $tahunSampai = $request->input('tahun_sampai', $tahunDari);
        $format = $request->input('format', 'excel'); // default excel jika tidak ada

        $tahunDari = intval($tahunDari);
        $tahunSampai = intval($tahunSampai);

        $months = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];

        $omsetData = [];

        foreach ($months as $num => $namaBulan) {
            $row = ['bulan' => $namaBulan];
            foreach (range($tahunDari, $tahunSampai) as $tahun) {
                $row["$tahun"] = 0;
            }
            $omsetData[$num] = $row;
        }

        // Query ke database untuk omset per bulan-tahun
        $transaksi = TransaksiDetail::selectRaw('YEAR(DibayarPada) as tahun, MONTH(DibayarPada) as bulan, SUM(TotalPembayaran) as total')
            ->where('Status', 'Lunas')
            ->whereBetween(DB::raw('YEAR(DibayarPada)'), [$tahunDari, $tahunSampai])
            ->groupBy('tahun', 'bulan')
            ->orderBy('bulan')
            ->orderBy('tahun')
            ->get();
        foreach ($transaksi as $t) {
            $bulanNum = str_pad($t->bulan, 2, '0', STR_PAD_LEFT);
            $tahun = $t->tahun;
            if (isset($omsetData[$bulanNum]) && isset($omsetData[$bulanNum][$tahun])) {
                $omsetData[$bulanNum][$tahun] = $t->total;
            }
        }

        $rows = array_values($omsetData);

        if ($format == 'excel') {
            $fileName = "Laporan_Omset_{$tahunDari}_{$tahunSampai}.xlsx";
            return Excel::download(new OmsetExport($rows, $tahunDari, $tahunSampai), $fileName);

        } elseif ($format == 'pdf') {
            $pdf = Pdf::loadView('laporan.omset.export', [
                'data' => $rows,
                'tahun_dari' => $tahunDari,
                'tahun_sampai' => $tahunSampai
            ])->setPaper('a4', 'landscape');
            return $pdf->stream("Laporan_Omset_{$tahunDari}_{$tahunSampai}.pdf");
        } else {
            return back()->with('error', 'Format export tidak dikenali.');
        }
    }
}
