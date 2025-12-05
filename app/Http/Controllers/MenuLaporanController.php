<?php

namespace App\Http\Controllers;

use App\Exports\OmsetExport;
use App\Exports\UnitBelumTerjualExport;
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
use Yajra\DataTables\DataTables as YajraDataTables;
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

    public function OmsetHarian(Request $request)
    {
        if ($request->ajax()) {
            // Ambil bulan dan tahun, default bulan/tahun hari ini
            $bulan = $request->input('bulan', date('m'));
            $tahun = $request->input('tahun', date('Y'));

            // Tentukan jumlah hari di bulan tersebut
            $jumlahHari = cal_days_in_month(CAL_GREGORIAN, intval($bulan), intval($tahun));

            $tanggalList = [];
            for ($hari = 1; $hari <= $jumlahHari; $hari++) {
                $tanggalList[] = sprintf('%04d-%02d-%02d', $tahun, $bulan, $hari);
            }

            // Ambil data IN (omset) dan OUT (keluar), group per Tanggal
            $dataOmset = TransaksiKeuangan::selectRaw('DATE(Tanggal) as TglNum, SUM(Nominal) as TotalOmset')
                ->where('Jenis', 'IN')
                ->whereMonth('Tanggal', $bulan)
                ->whereYear('Tanggal', $tahun)
                ->groupBy('TglNum')
                ->orderBy('TglNum', 'asc')
                ->get()
                ->keyBy('TglNum');

            $dataKeluar = TransaksiKeuangan::selectRaw('DATE(Tanggal) as TglNum, SUM(Nominal) as TotalKeluar')
                ->where('Jenis', 'OUT')
                ->whereMonth('Tanggal', $bulan)
                ->whereYear('Tanggal', $tahun)
                ->groupBy('TglNum')
                ->orderBy('TglNum', 'asc')
                ->get()
                ->keyBy('TglNum');

            // Siapkan hasil: 1 baris per hari dalam bulan
            $result = [];
            foreach ($tanggalList as $tgl) {
                $totalOmset = isset($dataOmset[$tgl]) ? $dataOmset[$tgl]->TotalOmset : 0;
                $totalKeluar = isset($dataKeluar[$tgl]) ? $dataKeluar[$tgl]->TotalKeluar : 0;
                $result[] = (object) [
                    'Tanggal' => $tgl,
                    'TotalOmset' => $totalOmset,
                    'TotalKeluar' => $totalKeluar,
                ];
            }

            return DataTables::of(collect($result))
                ->addIndexColumn()
                ->addColumn('Tanggal', function ($row) {
                    // Format: 01 Januari 2024
                    $bulan = [
                        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
                        '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli',
                        '08' => 'Agustus', '09' => 'September', '10' => 'Oktober',
                        '11' => 'November', '12' => 'Desember'
                    ];
                    $exp = explode('-', $row->Tanggal);
                    if (count($exp) != 3)
                        return $row->Tanggal;
                    return ltrim($exp[2], '0') . ' ' . ($bulan[$exp[1]] ?? $exp[1]) . ' ' . $exp[0];
                })
                ->addColumn('TotalOmset', function ($row) {
                    return 'Rp ' . number_format($row->TotalOmset, 0, ',', '.');
                })
                ->addColumn('TotalKeluar', function ($row) {
                    return 'Rp ' . number_format($row->TotalKeluar, 0, ',', '.');
                })
                ->rawColumns(['Tanggal', 'TotalOmset', 'TotalKeluar'])
                ->make(true);
        }
        $proyek = MasterProjek::get();
        return view('laporan.omset-harian.index', compact('proyek'));
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

        // Query ke database untuk omset per bulan-tahun dari TransaksiKeuangan IN
        $transaksi = TransaksiKeuangan::selectRaw('YEAR(Tanggal) as tahun, MONTH(Tanggal) as bulan, SUM(Nominal) as total')
            ->where('Jenis', 'IN')
            ->whereBetween(DB::raw('YEAR(Tanggal)'), [$tahunDari, $tahunSampai])
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
            ])->setPaper('a4', 'portrait');
            return $pdf->stream("Laporan_Omset_{$tahunDari}_{$tahunSampai}.pdf");
        } else {
            return back()->with('error', 'Format export tidak dikenali.');
        }
    }

    public function DownloadOmsetHarian(Request $request)
    {
        $tanggalDari = $request->input('tanggal_dari', date('Y-m-01'));
        $tanggalSampai = $request->input('tanggal_sampai', date('Y-m-d'));
        $format = $request->input('format', 'excel');

        if (strtotime($tanggalDari) > strtotime($tanggalSampai)) {
            return back()->with('error', 'Rentang tanggal tidak valid.');
        }

        $period = new \DatePeriod(
            new \DateTime($tanggalDari),
            new \DateInterval('P1D'),
            (new \DateTime($tanggalSampai))->modify('+1 day')
        );

        $omsetData = [];
        foreach ($period as $date) {
            $tgl = $date->format('Y-m-d');
            $omsetData[$tgl] = [
                'tanggal' => $tgl,
                'total' => 0
            ];
        }

        // Query database untuk total omset per hari
        $transaksi = TransaksiDetail::selectRaw('DATE(DibayarPada) as tanggal, SUM(TotalPembayaran) as total')
            ->where('Status', 'Lunas')
            ->whereBetween(DB::raw('DATE(DibayarPada)'), [$tanggalDari, $tanggalSampai])
            ->groupBy(DB::raw('DATE(DibayarPada)'))
            ->orderBy('tanggal')
            ->get();

        foreach ($transaksi as $t) {
            if (isset($omsetData[$t->tanggal])) {
                $omsetData[$t->tanggal]['total'] = $t->total;
            }
        }

        $rows = array_values($omsetData);

        if ($format == 'excel') {
            $fileName = "Laporan_Omset_Harian_{$tanggalDari}_sd_{$tanggalSampai}.xlsx";
            // Jika ada OmsetHarianExport gunakan, jika belum, bisa gunakan OmsetExport juga.
            return Excel::download(new OmsetHarianExport($rows, $tanggalDari, $tanggalSampai), $fileName);
        } elseif ($format == 'pdf') {
            $pdf = Pdf::loadView('laporan.omset-harian.export', [
                'data' => $rows,
                'tanggal_dari' => $tanggalDari,
                'tanggal_sampai' => $tanggalSampai
            ])->setPaper('a4', 'landscape');
            return $pdf->stream("Laporan_Omset_Harian_{$tanggalDari}_sd_{$tanggalSampai}.pdf");
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
            $query
                ->whereDate('TanggalTransaksi', '>=', $tanggalMulai)
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
            } else {
                $fileName = "Laporan_Mutasi_Dana_SemuaBank_{$tanggalAwal}_{$tanggalAkhir}.xlsx";
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

    public function UnitBelumTerjual(Request $request)
    {
        if ($request->ajax()) {
            $data = Produk::with(['getGrade', 'getJenis', 'getProyek', 'getDataBooking'])
                ->whereDoesntHave('getDataBooking')
                ->when($request->filled('proyek_id'), function ($query) use ($request) {
                    $query->whereHas('getProyek', function ($q) use ($request) {
                        $q->where('id', $request->proyek_id);
                    });
                })
                ->latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('Jenis', function ($row) {
                    return $row->getJenis ? $row->getJenis->Nama : '-';
                })
                ->editColumn('Grade', function ($row) {
                    return $row->getGrade ? $row->getGrade->Nama : '-';
                })
                ->editColumn('Proyek', function ($row) {
                    return $row->getProyek ? $row->getProyek->NamaProyek : '-';
                })
                ->editColumn('HargaPerMeter', function ($row) {
                    return RupiahFormat::currency($row->HargaPerMeter);
                })
                ->editColumn('Dp', function ($row) {
                    return RupiahFormat::currency($row->Dp);
                })
                ->editColumn('BesarAngsuran', function ($row) {
                    return RupiahFormat::currency($row->BesarAngsuran);
                })
                ->editColumn('HargaNormal', function ($row) {
                    return RupiahFormat::currency($row->HargaNormal);
                })
                ->addColumn('Status', function ($row) {
                    return '<span class="badge bg-success">Tersedia</span>';
                })
                ->rawColumns(['Status'])
                ->make(true);
        }
        $proyeks = MasterProjek::get();
        return view('laporan.unit-belum-terjual.index', compact('proyeks'));
    }

    public function DownloadUnitBelumTerjual(Request $request)
    {
        $format = $request->input('format', 'excel');
        $proyekId = $request->input('proyek_id');

        // Ambil data unit yang belum terjual (tidak punya relasi booking)
        $query = Produk::with(['getGrade', 'getJenis', 'getProyek'])
            ->whereDoesntHave('getDataBooking')
            ->when($proyekId, function ($query) use ($proyekId) {
                $query->whereHas('getProyek', function ($q) use ($proyekId) {
                    $q->where('id', $proyekId);
                });
            });

        $products = $query->get();

        if ($format === 'excel') {
            return Excel::download(new UnitBelumTerjualExport($products), 'unit-belum-terjual.xlsx');
        } elseif ($format === 'pdf') {
            $pdf = Pdf::loadView('laporan.unit-belum-terjual.cetak-pdf', [
                'data' => $products
            ])->setPaper('a4', 'landscape');
            return $pdf->stream('unit-belum-terjual.pdf');
        } else {
            return back()->with('error', 'Format export tidak dikenali.');
        }
    }

    public function Refund(Request $request)
    {
        if ($request->ajax()) {
            $query = TransaksiKeuangan::query()
                ->where('Jenis', 'OUT')
                ->where(function ($q) {
                    $q
                        ->Where('Kategori', 'LIKE', 'Refund');
                })
                ->when($request->proyek_id, function ($q) use ($request) {
                    $q->whereHas('proyek', function ($sub) use ($request) {
                        $sub->where('id', $request->proyek_id);
                    });
                })
                // filter tahun jika diberikan
                ->when($request->tahun, function ($q) use ($request) {
                    $q->whereYear('Tanggal', $request->tahun);
                })
                ->orderBy('Tanggal', 'desc');

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('id', function ($row) {
                    return $row->id ?? '-';
                })
                ->editColumn('Tanggal', function ($row) {
                    return $row->Tanggal ? date('d-m-Y', strtotime($row->Tanggal)) : '-';
                })
                ->editColumn('Jenis', function ($row) {
                    return $row->Jenis ?? '-';
                })
                ->editColumn('Kategori', function ($row) {
                    return $row->Kategori ?? '-';
                })
                ->editColumn('Deskripsi', function ($row) {
                    return $row->Deskripsi ?? '-';
                })
                ->editColumn('Nominal', function ($row) {
                    return RupiahFormat::currency($row->Nominal);
                })
                ->editColumn('NamaBank', function ($row) {
                    return $row->NamaBank ?? '-';
                })
                ->editColumn('RefType', function ($row) {
                    return $row->RefType ?? '-';
                })
                ->editColumn('RefId', function ($row) {
                    return $row->RefId ?? '-';
                })
                ->rawColumns(['Deskripsi'])
                ->make(true);
        }

        $proyeks = MasterProjek::get();
        return view('laporan.refund.index', compact('proyeks'));
    }

    public function DownloadRefund(Request $request)
    {
        $format = $request->input('format', 'excel');
        $proyekId = $request->input('proyek_id');

        // Ambil data refund (jenis OUT & keterangan mengandung 'ref')
        $query = TransaksiKeuangan::with(['produk', 'proyek'])
            ->where('Jenis', 'OUT')
            ->where('Keterangan', 'LIKE', '%ref%')
            ->when($proyekId, function ($query) use ($proyekId) {
                $query->whereHas('proyek', function ($q) use ($proyekId) {
                    $q->where('id', $proyekId);
                });
            })
            ->orderBy('Tanggal', 'desc');

        $refunds = $query->get();

        if ($format === 'excel') {
            // Anda harus membuat RefundExport sesuai kebutuhan data Anda
            return Excel::download(new \App\Exports\RefundExport($refunds), 'refund.xlsx');
        } elseif ($format === 'pdf') {
            $pdf = Pdf::loadView('laporan.refund.cetak-pdf', [
                'refunds' => $refunds
            ])->setPaper('a4', 'landscape');
            return $pdf->stream('refund.pdf');
        } else {
            return back()->with('error', 'Format export tidak dikenali.');
        }
    }
}
