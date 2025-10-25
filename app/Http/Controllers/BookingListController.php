<?php

namespace App\Http\Controllers;

use App\Models\BookingList;
use App\Models\MasterBank;
use App\Models\PenawaranHarga;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BookingListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = BookingList::latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $encryptedId = encrypt($row->id);
                    return '
                        <a href="' . route('booking-list.edit', $encryptedId) . '" class="btn btn-sm btn-warning">Edit</a>
                        <button class="btn btn-sm btn-danger btn-delete" data-id="' . $encryptedId . '">Hapus</button>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $penawaran = PenawaranHarga::latest()->get();
        return view('booking-list.index', compact('penawaran'));
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
        return view('booking-list.create', compact('penawaran', 'bank'));
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

        return redirect()->route('booking-list.index')
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
    public function edit(BookingList $bookingList)
    {
        //
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
    public function destroy(BookingList $bookingList)
    {
        //
    }
}
