<?php

namespace App\Http\Controllers;

use App\Models\BookingList;
use App\Models\MasterBank;
use App\Models\PenawaranHarga;
use App\Models\User;
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
                ->addColumn('action', function ($row) {
                    $encryptedId = encrypt($row->id);
                    return '
                        <a href="' . route('booking-list.edit', $encryptedId) . '" class="btn btn-sm btn-warning">Edit</a>
                        <a href="' . route('booking-list.print', $encryptedId) . '" class="btn btn-sm btn-primary" target="_blank">Cetak Kwitansi</a>
                        <button class="btn btn-sm btn-danger btn-delete" data-id="' . $encryptedId . '">Hapus</button>
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
                ->rawColumns(['action'])
                ->make(true);
        }
        $penawaran = PenawaranHarga::with('getCustomer')->latest()->get();
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
        // dd($data);
        $validatedData = $request->validate([
            'NamaPelangganPenawaran' => 'required',
            'Tanggal' => 'required',
            'TotalSetoran' => 'required',
            'JenisPembayaran' => 'required',
            'Keterangan' => 'nullable',
            'Penyetor' => 'required',
        ]);

        $nomorBooking = $this->generateNomorBooking();
        BookingList::create([
            'IdPenawaran' => $data['IdPenawaran'],
            'Nomor' => $nomorBooking,
            'IdProduk' => $data['IdProduk'],
            'NamaPelanggan' => $data['NamaPelangganPenawaran'],
            'Tanggal' => $data['Tanggal'],
            'Total' => preg_replace('/[^\d]/', '', $data['TotalSetoran']),
            'JenisPembayaran' => $data['JenisPembayaran'],
            'NamaBank' => $data['Bank'],
            'Keterangan' => $data['Keterangan'] ?? null,
            'Penerima' => auth()->user()->id,
            'DiterimaPada' => now(),
            'Penyetor' => $data['Penyetor'],
        ]);

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
    public function show(BookingList $bookingList)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $id = decrypt($id);
        $bookingList = BookingList::with('getPenawaran')->findOrFail($id);
        return view('booking-list.edit', compact('bookingList'));
    }

    public function PrintKwitansi($id)
    {
        $id = decrypt($id);
        $data = BookingList::find($id);

        // Custom paper size: A4 width, half A4 height (21cm x 14.85cm)
        $customPaper = array(0, 0, 595.28, 419.53);  // in points (A4 width x half A4 height)
        // 1 cm = 28.3465 points; 21cm = 595.28pt, 14.85cm = 419.53pt

        $pdf = \PDF::loadView('booking-list.cetak-kwitansi', compact('data'))
            ->setPaper($customPaper);

        return $pdf->download('kwitansi-booking-' . $data->Nomor . '.pdf');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BookingList $bookingList)
    {
        //
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
