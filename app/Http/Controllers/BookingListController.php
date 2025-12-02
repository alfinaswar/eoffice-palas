<?php

namespace App\Http\Controllers;

use App\Models\BookingList;
use App\Models\MasterBank;
use App\Models\PenawaranHarga;
use App\Models\TransaksiKeuangan;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Pdf;

class BookingListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = BookingList::with('getKaryawan', 'getProduk', 'getCustomer')->latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('KodeKantor', function ($row) {
                    return $row->getKantor ? $row->getKantor->Nama : $row->KodeKantor;
                })
                ->addColumn('action', function ($row) {
                    $encryptedId = encrypt($row->id);
                    return '
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-cogs me-1"></i> Aksi
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="' . route('booking-list.edit', $encryptedId) . '">
                                        <i class="fa fa-edit me-2"></i> Edit
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="' . route('booking-list.print', $encryptedId) . '" target="_blank">
                                        <i class="fa fa-print me-2"></i> Cetak Kwitansi
                                    </a>
                                </li>
                                <li>
                                    <button class="dropdown-item btn-delete" data-id="' . $encryptedId . '" type="button">
                                        <i class="fa fa-trash me-2"></i> Hapus
                                    </button>
                                </li>
                            </ul>
                        </div>
                    ';
                })
                ->editColumn('Total', function ($row) {
                    return 'Rp ' . number_format($row->Total, 0, ',', '.');
                })
                ->editColumn('Penerima', function ($row) {
                    return $row->getKaryawan->name;
                })
                ->editColumn('IdProduk', function ($row) {
                    return $row->getProduk->Nama;
                })
                ->editColumn('NamaPelanggan', function ($row) {
                    return $row->getCustomer->name;
                })
                ->addColumn('Nomor', function ($row) {
                    $encryptedId = encrypt($row->id);
                    return '<a href="' . route('booking-list.show', $encryptedId) . '" style="color: #007bff;">' . e($row->Nomor) . '</a>';
                })
                ->addColumn('StatusOrder', function ($row) {
                    $status = $row->StatusOrder ?? '-';
                    if ($status === 'Aktif') {
                        return '<span class="badge bg-success">Aktif</span>';
                    } else {
                        return '<span class="badge bg-danger">Cancel</span>';
                    }
                })
                ->rawColumns(['action', 'Nomor', 'StatusOrder'])
                ->make(true);
        }
        $penawaran = PenawaranHarga::whereDoesntHave('getBooking')->with('getCustomer')->latest()->get();
        return view('booking-list.index', compact('penawaran'));
    }

    /**
     * Cetak kwitansi booking.
     */
    public function print($id)
    {
        $id = decrypt($id);
        $booking = BookingList::findOrFail($id);

        // Jika ingin menambahkan data Penawaran atau relasi lain, tambahkan di sini
        // $booking = BookingList::with('Penawaran', ...)->findOrFail($id);

        return view('booking-list.print', compact('booking'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $id = decrypt($id);
        $penawaran = PenawaranHarga::with([
            'DetailPenawaran' => function ($q) {
                $q->where('Status', 'Y')->with('getProduk');
            }
        ])->find($id);
        $bank = MasterBank::get();
        $customer = User::where('jenis_user', operator: 'Customer')->get();
        return view('booking-list.create', compact('penawaran', 'bank', 'customer'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $validatedData = $request->validate([
            'NamaPelangganPenawaran' => 'required',
            'Tanggal' => 'required',
            'TotalSetoran' => 'required',
            'JenisPembayaran' => 'required',
            'Keterangan' => 'nullable',
            'Penyetor' => 'required',
        ]);

        $nomorBooking = $this->generateNomorBooking();
        if ($request->hasFile('Bukti')) {
            $file = $request->file('Bukti');
            $filename = 'bukti_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('bukti-booking', $filename, 'public');
            $data['Bukti'] = $path;
        } else {
            $data['Bukti'] = null;
        }
        $booking = BookingList::create([
            'IdPenawaran' => $data['IdPenawaran'],
            'Nomor' => $nomorBooking,
            'IdProduk' => $data['IdProduk'],
            'NamaPelanggan' => $data['NamaPelangganPenawaran'],
            'Tanggal' => $data['Tanggal'],
            'Total' => preg_replace('/[^\d]/', '', $data['TotalSetoran']),
            'SisaBayar' => preg_replace('/[^\d]/', '', $data['SisaBayar']),
            'JenisPembayaran' => $data['JenisPembayaran'],
            'NamaBank' => $data['Bank'],
            'DariBank' => $data['DariBank'] ?? null,
            'NoRekening' => $data['NoRekening'] ?? null,
            'Keterangan' => $data['Keterangan'] ?? null,
            'Penerima' => auth()->user()->id,
            'DiterimaPada' => now(),
            'Penyetor' => $data['Penyetor'],
            'KodeKantor' => auth()->user()->KodeKantor,
        ]);

        $saldoSebelumnya = TransaksiKeuangan::where('NamaBank', $data['Bank'])
            ->orderBy('Tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->value('SaldoSetelah');

        $saldoSebelumnya = $saldoSebelumnya ? preg_replace('/[^\d]/', '', $saldoSebelumnya) : 0;
        $nominalMasuk = preg_replace('/[^\d]/', '', $data['TotalSetoran']);
        $saldoSetelah = (int) $saldoSebelumnya + (int) $nominalMasuk;
        $cariNama = User::find($data['NamaPelangganPenawaran'])->name;
        TransaksiKeuangan::create([
            'Tanggal' => $data['Tanggal'],
            'Jenis' => 'IN',
            'Kategori' => 'Booking',
            'Deskripsi' => 'Penerimaan booking fee dengan nomor: ' . $nomorBooking . ', atas nama: ' . $cariNama,
            'Nominal' => $nominalMasuk,
            'NamaBank' => $data['Bank'],
            'RefType' => 'BookingList',
            'RefId' => $booking->id,
            'SaldoSetelah' => $saldoSetelah,
            'UserCreate' => auth()->user()->name,
        ]);
        activity()
            ->causedBy(auth()->user()->id)
            ->withProperties(['ip' => request()->ip()])
            ->log('Menambah booking list baru dengan nomor: ' . $nomorBooking . ', atas nama: ' . $data['NamaPelangganPenawaran']);

        return redirect()
            ->route('booking-list.index')
            ->with('success', 'Booking List berhasil disimpan.');
    }

    private function generateNomorBooking()
    {
        $year = now()->format('y');
        $month = now()->format('m');

        $lastBooking = BookingList::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->orderBy('Nomor', 'desc')
            ->first();

        if ($lastBooking && preg_match('/BY' . $year . 'M' . $month . 'N(\d{4})/', $lastBooking->Nomor, $matches)) {
            $increment = (int) $matches[1] + 1;
        } else {
            $increment = 1;
        }

        $nomorBooking = 'BY' . $year . 'M' . $month . 'N' . str_pad($increment, 4, '0', STR_PAD_LEFT);

        return $nomorBooking;
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {

        $id = decrypt($id);
        $bookingList = BookingList::with('getPenawaran', 'getKaryawan', 'getDp.getBank', 'getTransaksiHeader')->findOrFail($id);
        // dd($bookingList);
        return view('booking-list.show', compact('bookingList'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $id = decrypt($id);
        $bank = MasterBank::get();
        $bookingList = BookingList::with('getPenawaran', 'getKaryawan')->findOrFail($id);
        return view('booking-list.edit', compact('bookingList', 'bank'));
    }

    public function cancelOrder(Request $request, $id)
    {
        $bookingList = BookingList::with('getPenawaran', 'getKaryawan', 'getDp.getBank', 'getTransaksiHeader')->findOrFail($request->IdBooking);
        $alasanCancel = $request->input('AlasanCancel', null);
        $bookingList->update([
            'StatusOrder' => 'Cancel',
            'UserCancel' => auth()->user()->name,
            'TanggalCancel' => now(),
            'AlasanCancel' => $alasanCancel,
        ]);
        if ($bookingList->getDp) {
            $bookingList->getDp->update([
                'StatusOrder' => 'Cancel',
                'UserCancel' => auth()->user()->name,
                'TanggalCancel' => now(),
                'AlasanCancel' => $alasanCancel,
            ]);
        }
        if ($bookingList->getTransaksiHeader) {
            $bookingList->getTransaksiHeader->update([
                'StatusOrder' => 'Cancel',
                'UserCancel' => auth()->user()->name,
                'TanggalCancel' => now(),
                'AlasanCancel' => $alasanCancel,
            ]);
            if ($bookingList->getTransaksiHeader->getTransaksi && $bookingList->getTransaksiHeader->getTransaksi->count() > 0) {
                foreach ($bookingList->getTransaksiHeader->getTransaksi as $detail) {
                    $detail->update([
                        'StatusOrder' => 'Cancel',
                        'UserCancel' => auth()->user()->name,
                        'TanggalCancel' => now(),
                        'AlasanCancel' => $alasanCancel,
                    ]);
                }
            }
        }


        return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan.');
    }

    public function PrintKwitansi($id)
    {
        try {
            $id = decrypt($id);
            $data = BookingList::with('getCustomer', 'getProduk', 'getKaryawan')->find($id);

            if (!$data) {
                return redirect()->back()->with('error', 'Data booking tidak ditemukan');
            }
            $width = 21 * 28.35;  // 595.35 points (lebar)
            $height = 15 * 28.35;  // 425.25 points (tinggi)

            $customPaper = array(0, 0, $width, $height);

            $pdf = Pdf::loadView('booking-list.cetak-kwitansi', compact('data'))
                ->setPaper($customPaper, 'portrait')
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                    'dpi' => 96,
                    'defaultFont' => 'Arial',
                    'margin_top' => 0,
                    'margin_right' => 0,
                    'margin_bottom' => 0,
                    'margin_left' => 0
                ]);
            return $pdf->stream('kwitansi-booking-' . $data->Nomor . '.pdf');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mencetak kwitansi: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        // dd($data);
        $validatedData = $request->validate([
            'NamaPelangganPenawaran' => 'required',
            'Tanggal' => 'required',
            'TotalSetoran' => 'required',
            'JenisPembayaran' => 'required',
            'Keterangan' => 'nullable',
            'Penyetor' => 'required',
        ]);

        $id = decrypt($id);
        $bookingList = BookingList::findOrFail($id);

        // Simpan perubahan booking
        if ($request->hasFile('Bukti')) {
            $file = $request->file('Bukti');
            $filename = 'bukti_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('bukti-booking', $filename, 'public');
            $data['Bukti'] = $path;
        } else {
            $data['Bukti'] = null;
        }
        $bookingList->update([
            'NamaPelanggan' => $data['NamaPelangganPenawaran'],
            'Tanggal' => $data['Tanggal'],
            'Total' => preg_replace('/[^\d]/', '', $data['TotalSetoran']),
            'SisaBayar' => preg_replace('/[^\d]/', '', $data['SisaBayar'] ?? 0),
            'JenisPembayaran' => $data['JenisPembayaran'],
            'NamaBank' => $data['Bank'] ?? null,
            'DariBank' => $data['DariBank'] ?? null,
            'NoRekening' => $data['NoRekening'] ?? null,
            'Keterangan' => $data['Keterangan'] ?? null,
            'Penerima' => auth()->user()->id,
            'DiterimaPada' => now(),
            'Penyetor' => $data['Penyetor'],
        ]);

        $nominalMasuk = preg_replace('/[^\d]/', '', $data['TotalSetoran']);
        $cariNama = User::find($data['NamaPelangganPenawaran'])->name;

        $transaksi = TransaksiKeuangan::where('RefId', $bookingList->id)
            ->where('Kategori', 'Booking')
            ->where('RefType', 'BookingList')
            ->orderBy('id', 'desc')
            ->first();

        if ($transaksi) {
            $saldoSebelumnya = TransaksiKeuangan::where('NamaBank', $data['Bank'])
                ->where('id', '<', $transaksi->id)
                ->orderBy('id', 'desc')
                ->value('SaldoSetelah');

            $saldoSebelumnya = $saldoSebelumnya ? preg_replace('/[^\d]/', '', $saldoSebelumnya) : 0;
            $saldoSetelah = (int) $saldoSebelumnya + (int) $nominalMasuk;

            $transaksi->update([
                'Tanggal' => $data['Tanggal'],
                'Jenis' => 'IN',
                'Kategori' => 'Booking',
                'Deskripsi' => 'booking fee dengan nomor: ' . $bookingList->Nomor . ', atas nama: ' . $cariNama,
                'Nominal' => $nominalMasuk,
                'NamaBank' => $data['Bank'],
                'RefType' => 'BookingList',
                'RefId' => $bookingList->id,
                'SaldoSetelah' => $saldoSetelah,
                'UserCreate' => auth()->user()->name,
            ]);
            $transaksisAfter = TransaksiKeuangan::where('NamaBank', $data['Bank'])
                ->where('id', '>', $transaksi->id)
                ->orderBy('id', 'asc')
                ->get();

            $saldo = $saldoSetelah;
            foreach ($transaksisAfter as $trx) {
                $delta = ($trx->Jenis === 'IN' ? 1 : -1) * preg_replace('/[^\d]/', '', $trx->Nominal);
                $saldo += $delta;
                $trx->update(['SaldoSetelah' => $saldo]);
            }
        } else {
            $saldoSebelumnya = TransaksiKeuangan::where('NamaBank', $data['Bank'])
                ->orderBy('Tanggal', 'desc')
                ->orderBy('id', 'desc')
                ->value('SaldoSetelah');

            $saldoSebelumnya = $saldoSebelumnya ? preg_replace('/[^\d]/', '', $saldoSebelumnya) : 0;
            $saldoSetelah = (int) $saldoSebelumnya + (int) $nominalMasuk;
            TransaksiKeuangan::create([
                'Tanggal' => $data['Tanggal'],
                'Jenis' => 'IN',
                'Kategori' => 'Booking',
                'Deskripsi' => 'Edit booking fee dengan nomor: ' . $bookingList->Nomor . ', atas nama: ' . $cariNama,
                'Nominal' => $nominalMasuk,
                'NamaBank' => $data['Bank'],
                'RefType' => 'BookingList',
                'RefId' => $bookingList->id,
                'SaldoSetelah' => $saldoSetelah,
                'UserCreate' => auth()->user()->name,
            ]);
        }

        activity()
            ->causedBy(auth()->user()->id)
            ->withProperties(['ip' => request()->ip()])
            ->log('Mengedit booking list nomor: ' . $bookingList->Nomor . ', atas nama: ' . $data['NamaPelangganPenawaran']);

        return redirect()
            ->route('booking-list.index')
            ->with('success', 'Booking List berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $id = decrypt($id);
        $Booking = BookingList::find($id);

        if (!$Booking) {
            return response()->json(['status' => 404, 'message' => 'Data tidak ditemukan']);
        }
        activity()
            ->causedBy(auth()->user()->id)
            ->withProperties(['ip' => request()->ip()])
            ->log('Menghapus master Booking: ' . $Booking->NamaProyek);
        $Booking->delete();

        return response()->json(['status' => 200, 'message' => 'Master projek berhasil dihapus']);
    }
}
