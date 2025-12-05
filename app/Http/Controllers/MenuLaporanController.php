<?php

namespace App\Http\Controllers;

use App\Exports\OmsetExport;
use App\Models\MasterBank;
use App\Models\MasterProjek;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\TransaksiKeluar;
use App\Models\TransaksiKeuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laraindo\RupiahFormat;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Pdf;
use Yajra\DataTables\DataTables as YajraDataTables;

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

            // Ambil omset per bulan
            // Ambil data omset/masuk dari TransaksiKeuangan (Jenis IN) dan keluar dari TransaksiKeuangan (Jenis OUT) per bulan
            $dataOmset = TransaksiKeuangan::selectRaw('MONTH(Tanggal) as BulanNum, SUM(Nominal) as TotalOmset')
                ->where('Jenis', 'IN')
                ->whereYear('Tanggal', $year)
                ->groupBy('BulanNum')
                ->orderBy('BulanNum', 'asc')
                ->get()
                ->keyBy(function ($item) {
                    return str_pad($item->BulanNum, 2, '0', STR_PAD_LEFT);
                });

            $dataKeluar = TransaksiKeuangan::selectRaw('MONTH(Tanggal) as BulanNum, SUM(Nominal) as TotalKeluar')
                ->where('Jenis', 'OUT')
                ->whereYear('Tanggal', $year)
                ->groupBy('BulanNum')
                ->orderBy('BulanNum', 'asc')
                ->get()
                ->keyBy(function ($item) {
                    return str_pad($item->BulanNum, 2, '0', STR_PAD_LEFT);
                });

            $result = [];
            foreach ($months as $num => $namaBulan) {
                $totalOmset = isset($dataOmset[$num]) ? $dataOmset[$num]->TotalOmset : 0;
                $totalKeluar = isset($dataKeluar[$num]) ? $dataKeluar[$num]->TotalKeluar : 0;
                $result[] = (object) [
                    'Bulan' => $year . '-' . $num,
                    'TotalOmset' => $totalOmset,
                    'TotalKeluar' => $totalKeluar,
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
                ->addColumn('TotalKeluar', function ($row) {
                    return 'Rp ' . number_format($row->TotalKeluar, 0, ',', '.');
                })
                ->rawColumns(['Bulan', 'TotalOmset', 'TotalKeluar'])
                ->make(true);
        }
        return view('laporan.omset.index');
    }

    public function DownloadOmset(Request $request)
    {
        $tahunDari = $request->input('tahun_dari', date('Y'));
        $tahunSampai = $request->input('tahun_sampai', $tahunDari);
        $format = $request->input('format', 'excel');  // default excel jika tidak ada

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

    public function Penjualan(Request $request)
    {
        if ($request->ajax()) {
            $data = Transaksi::with([
                'getCustomer',
                'getProduk.getProyek',
                'getProduk.getGrade',
                'getBooking.getDp'
            ])->latest();

            if ($request->filled('proyek')) {
                $data = $data->whereHas('getProduk.getProyek', function ($q) use ($request) {
                    $q->where('id', $request->proyek);
                });
            }
            if ($request->filled('produk')) {
                $data = $data->where('IdProduk', $request->produk);
            }
            if ($request->filled('tahun')) {
                $data = $data->whereYear('TanggalTransaksi', $request->tahun);
            }

            return YajraDataTables::of($data)
                ->addIndexColumn()
                ->addColumn('NamaPelanggan', function ($row) {
                    return $row->getCustomer ? $row->getCustomer->name : '-';
                })
                ->addColumn('TotalHarga', function ($row) {
                    return RupiahFormat::currency($row->TotalHarga ?? 0);
                })
                ->addColumn('ProdukGrade', function ($row) {
                    $produk = $row->getProduk;
                    $grade = $produk->getGrade->Nama ?? null;
                    $nama = $produk ? $produk->Nama : '-';
                    return $grade ? $nama . ' (Grade ' . $grade . ')' : $nama;
                })
                ->addColumn('Luas', function ($row) {
                    $produk = $row->getProduk;
                    $luas = $produk->Luas ?? null;
                    return $luas ? $luas . ' m²' : '-';
                })
                ->addColumn('BookingFee', function ($row) {
                    $BookingFee = $row->getBooking->Total ?? null;
                    return RupiahFormat::currency($BookingFee ?? 0);
                })
                ->addColumn('Dp', function ($row) {
                    $Dp = $row->getBooking->getDp->Total ?? null;
                    // dd($Dp);
                    return RupiahFormat::currency($Dp ?? 0);
                })
                ->addColumn('SisaPembayaran', function ($row) {
                    return RupiahFormat::currency($row->SisaBayar ?? 0);
                })
                ->addColumn('TotalUangMasuk', function ($row) {
                    $total = 0;
                    $bookingFee = $row->getBooking->Total ?? 0;
                    $total += (int) $bookingFee;
                    $dp = $row->getBooking && $row->getBooking->getDp ? $row->getBooking->getDp->Total : 0;
                    $total += (int) $dp;

                    $transaksiLunas = $row->getTransaksi ? collect($row->getTransaksi)->where('Status', 'Lunas') : collect();
                    $totalPembayaranLunas = $transaksiLunas->sum('TotalPembayaran');
                    $total += (int) $totalPembayaranLunas;

                    return RupiahFormat::currency($total);
                })
                ->addColumn('TanggalBooking', function ($row) {
                    return $row->TanggalTransaksi
                        ? \Carbon\Carbon::parse($row->TanggalTransaksi)->format('d-m-Y')
                        : '-';
                })
                // NoHP (customer)
                ->addColumn('NoHP', function ($row) {
                    $phone = $row->getCustomer ? $row->getCustomer->nohp : null;
                    return $phone ? '0' . ltrim($phone, '0') : '-';
                })
                // Raw untuk tidak escape kolom apapun
                ->rawColumns([
                    'NamaPelanggan',
                    'TotalHarga',
                    'ProdukGrade',
                    'Luas',
                    'BookingFee',
                    'SisaPembayaran',
                    'TotalUangMasuk',
                    'TanggalBooking',
                    'NoHP',
                    'Dp'
                ])
                ->make(true);
        }

        $MasterProduk = Produk::get();
        $Proyek = MasterProjek::get();

        return view('laporan.penjualan.index', compact('MasterProduk', 'Proyek'));
    }

    public function DownloadPenjualan(Request $request)
    {
        $proyekId = $request->input('Proyek');
        $format = $request->input('format', 'excel');
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalAkhir = $request->input('tanggal_akhir');

        $proyek = MasterProjek::find($proyekId);
        $namaProyek = $proyek ? $proyek->NamaProyek : '-';

        $query = Transaksi::with(['getProduk', 'getProduk.getProyek', 'getBooking', 'getBooking.getDp', 'getCustomer'])
            ->whereHas('getProduk.getProyek', function ($query) use ($proyekId) {
                $query->where('id', $proyekId);
            });

        if ($tanggalMulai && $tanggalAkhir) {
            $query->whereDate('TanggalTransaksi', '>=', $tanggalMulai)
                ->whereDate('TanggalTransaksi', '<=', $tanggalAkhir);
        }

        $data = $query->get()->map(function ($row) {
            $row->NamaPelanggan = $row->getCustomer ? $row->getCustomer->name : '-';
            $row->TotalHarga = $row->TotalHarga ?? 0;
            $produk = $row->getProduk;
            $row->ProdukGrade = $produk ? ($produk->Nama ?? '-') . (isset($produk->Grade) ? ' - ' . $produk->Grade : '') : '-';
            $row->Luas = $produk && isset($produk->Luas) ? $produk->Luas : '-';
            $row->BookingFee = $row->getBooking ? ($row->getBooking->Total ?? 0) : 0;
            $row->Dp = $row->getBooking && $row->getBooking->getDp ? $row->getBooking->getDp->Total : 0;
            $row->SisaPembayaran = $row->TotalHarga - ($row->BookingFee + $row->Dp);

            $cicilan = 0;
            if ($row->getTransaksi && $row->getTransaksi->count() > 0) {
                foreach ($row->getTransaksi as $detail) {
                    if (isset($detail->Status) && strtolower($detail->Status) == 'lunas') {
                        $cicilan += $detail->TotalPembayaran ?? 0;
                    }
                }
            }
            $row->TotalUangMasuk = $row->BookingFee + $row->Dp + $cicilan;
            $row->TanggalBooking = $row->TanggalTransaksi
                ? \Carbon\Carbon::parse($row->TanggalTransaksi)->format('d-m-Y')
                : '-';
            $phone = $row->getCustomer ? $row->getCustomer->nohp : null;
            $row->NoHP = $phone ? '0' . ltrim($phone, '0') : '-';
            return $row;
        });

        if ($format == 'excel') {
            $fileName = "Laporan_Omset_{$namaProyek}.xlsx";
            return Excel::download(new OmsetExport($data, $namaProyek, $tanggalMulai, $tanggalAkhir), $fileName);
        } elseif ($format == 'pdf') {
            $pdf = Pdf::loadView('laporan.penjualan.export', [
                'data' => $data,
                'nama_proyek' => $namaProyek,
                'tanggal_mulai' => $tanggalMulai,
                'tanggal_akhir' => $tanggalAkhir
            ])->setPaper('a4', 'landscape');
            return $pdf->stream('Laporan Penjualan.pdf');
        } else {
            return back()->with('error', 'Format export tidak dikenali.');
        }
    }

    // Laporan mutasi dana
    public function MutasiDana(Request $request)
    {
        if ($request->ajax()) {
            $query = TransaksiKeuangan::with('getNamaBank')->latest();
            if ($request->filled('tahun')) {
                $tahun = $request->input('tahun');
                $query->whereYear('Tanggal', $tahun);
            }
            if ($request->filled('bulan')) {
                $bulan = $request->input('bulan');
                $query->whereMonth('Tanggal', $bulan);
            }
            if ($request->filled('nama_bank')) {
                $namaBank = $request->input('nama_bank');
                $query->where('NamaBank', $namaBank);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('NamaBank', function ($row) {
                    return $row->getNamaBank ? $row->getNamaBank->Nama : '-';
                })
                ->editColumn('Nominal', function ($row) {
                    return RupiahFormat::currency($row->Nominal ?? 0);
                })
                ->editColumn('SaldoSetelah', function ($row) {
                    return RupiahFormat::currency($row->SaldoSetelah ?? 0);
                })
                ->make(true);
        }
        $Bank = MasterBank::get();
        return view('laporan.mutasi-dana.index', compact('Bank'));
    }

    public function DownloadMutasiDana(Request $request)
    {
        $tanggalAwal = $request->input('tanggal_awal');
        $tanggalAkhir = $request->input('tanggal_akhir');
        $namaBankFilter = $request->input('nama_bank_filter');
        $format = $request->input('format', 'excel');

        if (!$tanggalAwal || !$tanggalAkhir) {
            return back()->with('error', 'Tanggal awal dan akhir harus diisi.');
        }

        $ambilSaldoAwal = function ($bankId, $tanggalAwal) {
            $lastTransaksi = TransaksiKeuangan::where('NamaBank', $bankId)
                ->whereDate('Tanggal', '<', $tanggalAwal)
                ->orderBy('Tanggal', 'desc')
                ->orderBy('id', 'desc')
                ->first();
            return $lastTransaksi ? $lastTransaksi->SaldoSetelah : 0;  // 0 jika belum ada transaksi sebelumnya
        };

        if (!empty($namaBankFilter) && $namaBankFilter !== 'semua') {
            $query = TransaksiKeuangan::with('getNamaBank')
                ->whereDate('Tanggal', '>=', $tanggalAwal)
                ->whereDate('Tanggal', '<=', $tanggalAkhir)
                ->where('NamaBank', $namaBankFilter);

            $data = $query->get();

            $saldo_awal = $ambilSaldoAwal($namaBankFilter, $tanggalAwal);
        } else {
            $banks = MasterBank::all()->keyBy('id');
            $result = [];
            $saldo_awal_per_bank = [];

            foreach ($banks as $bankId => $bank) {
                $query = TransaksiKeuangan::with('getNamaBank')
                    ->whereDate('Tanggal', '>=', $tanggalAwal)
                    ->whereDate('Tanggal', '<=', $tanggalAkhir)
                    ->where('NamaBank', $bankId);

                $bankTransaksi = $query->get();
                $saldoAwal = $ambilSaldoAwal($bankId, $tanggalAwal);

                if ($bankTransaksi->count() > 0) {
                    $result[] = [
                        'bank' => $bank,
                        'transaksi' => $bankTransaksi,
                        'saldo_awal' => $saldoAwal,
                    ];
                }
                $saldo_awal_per_bank[$bankId] = $saldoAwal;
            }
            $data = $result;
        }

        if ($format == 'excel') {
            if (!empty($namaBankFilter) && $namaBankFilter !== 'semua') {
                $fileName = "Laporan_Mutasi_Dana_Bank_{$namaBankFilter}_{$tanggalAwal}_{$tanggalAkhir}.xlsx";
                // return Excel::download(new \App\Exports\MutasiDanaExport($data, $tanggalAwal, $tanggalAkhir, $namaBankFilter, $saldo_awal), $fileName);
            } else {
                $fileName = "Laporan_Mutasi_Dana_SemuaBank_{$tanggalAwal}_{$tanggalAkhir}.xlsx";
                // return Excel::download(new \App\Exports\MutasiDanaExport($data, $tanggalAwal, $tanggalAkhir, 'semua', $saldo_awal_per_bank), $fileName);
            }
        } elseif ($format == 'pdf') {
            if (!empty($namaBankFilter) && $namaBankFilter !== 'semua') {
                $pdf = Pdf::loadView('laporan.mutasi-dana.export', [
                    'data' => $data,
                    'tanggal_awal' => $tanggalAwal,
                    'tanggal_akhir' => $tanggalAkhir,
                    'nama_bank' => $namaBankFilter,
                    'saldo_awal' => isset($saldo_awal) ? $saldo_awal : 0,
                ])->setPaper('a4', 'portrait');
                return $pdf->stream("Laporan_Mutasi_Dana_{$namaBankFilter}_{$tanggalAwal}_{$tanggalAkhir}.pdf");
            } else {
                $pdf = Pdf::loadView('laporan.mutasi-dana.export-semua', [
                    'data' => $data,
                    'tanggal_awal' => $tanggalAwal,
                    'tanggal_akhir' => $tanggalAkhir,
                    'saldo_awal_per_bank' => isset($saldo_awal_per_bank) ? $saldo_awal_per_bank : [],
                ])->setPaper('a4', 'portrait');
                return $pdf->stream("Laporan_Mutasi_Dana_SemuaBank_{$tanggalAwal}_{$tanggalAkhir}.pdf");
            }
        } else {
            return back()->with('error', 'Format export tidak dikenali.');
        }
    }
}
