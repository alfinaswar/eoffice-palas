<?php

namespace App\Http\Controllers;

use App\Models\BookingList;
use App\Models\DownPayment;
use App\Models\MasterBank;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DownPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DownPayment::with('getPenerima', 'getProduk', 'getCustomer')->latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $encryptedId = encrypt($row->id);
                    return '
                        <a href="' . route('dp.edit', $encryptedId) . '" class="btn btn-sm btn-warning">Edit</a>
                        <a href="' . route('dp.print', $encryptedId) . '" class="btn btn-sm btn-primary" target="_blank">Cetak Kwitansi</a>
                        <button class="btn btn-sm btn-danger btn-delete" data-id="' . $encryptedId . '">Hapus</button>
                    ';
                })
                ->editColumn('Total', function ($row) {
                    return 'Rp ' . number_format($row->Total, 0, ',', '.');
                })
                ->editColumn('Penerima', function ($row) {
                    return $row->getPenerima ? $row->getPenerima->name : '-';
                })
                ->editColumn('produk_id', function ($row) {
                    return $row->getProduk ? $row->getProduk->Nama : '-';
                })
                ->editColumn('customer_id', function ($row) {
                    return $row->getCustomer ? $row->getCustomer->name : '-';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $booking = BookingList::get();
        return view('down-payments.index', compact('booking'));
    }

    /**
     * Cetak kwitansi Down Payment.
     */
    public function print($id)
    {
        $id = decrypt($id);
        $dp = DownPayment::with('getPenerima', 'getProduk', 'getCustomer')->findOrFail($id);
        return view('down-payments.print', compact('dp'));
    }

    /**
     * Show the form for creating a new DP.
     */
    public function create($id = null)
    {
        $id = decrypt($id);
        $booking = BookingList::with('getProduk', 'getCustomer')->where('id', $id)->latest()->first();
        $produk = Produk::get();
        $bank = MasterBank::all();
        $customer = User::where('jenis_user', 'Customer')->get();
        return view('down-payments.create', compact('produk', 'bank', 'customer', 'booking'));
    }

    /**
     * Store a newly created DP in storage.
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'Tanggal' => 'required|date',
            'JenisPembayaran' => 'required',
            'Bank' => 'required',
            'Penerima' => 'required|string',
            'Penyetor' => 'required|string',
            'TotalSetoran' => 'required|string',
            'SisaBayar' => 'required|string',
            'Keterangan' => 'nullable|string',
        ]);
        // dd($request->all());
        $dp = DownPayment::create([
            'IdBooking' => $request->get('IdBooking'),
            'Nomor' => $this->generateKodeDP(),
            'IdProduk' => $request->get('IdProduk'),
            'NamaPelanggan' => $request->get('NamaPelanggan'),
            'Tanggal' => $request->get('Tanggal'),
            'Total' => preg_replace('/\D/', '', $request->get('TotalSetoran')),
            'SisaBayar' => $request->get('SisaBayarRaw'),
            'JenisPembayaran' => $request->get('JenisPembayaran'),
            'Keterangan' => $request->get('Keterangan'),
            'Penerima' => auth()->user()->id,
            'DiterimaPada' => $request->get('DiterimaPada') ?? now(),
            'Penyetor' => $request->get('Penyetor'),
            'DiserahkanPada' => $request->get('DiserahkanPada'),
            'UserCreated' => $request->get('UserCreated') ?? auth()->user()->id,
        ]);

        activity()
            ->causedBy(auth()->user()->id)
            ->withProperties(['ip' => request()->ip()])
            ->log('Menambah down payment baru ID: ' . $dp->id . ', atas nama customer ID: ' . $dp->customer_id);

        return redirect()
            ->route('dp.index')
            ->with('success', 'Down Payment berhasil disimpan.');
    }

    private function generateKodeDP()
    {
        $prefix = 'DP-';
        $dateSegment = date('Ym');

        // Get the last DP for this month
        $lastDP = DownPayment::whereRaw("DATE_FORMAT(created_at, '%Y%m') = ?", [$dateSegment])
            ->orderBy('id', 'desc')->first();

        if ($lastDP && isset($lastDP->Nomor)) {
            $parts = explode('-', $lastDP->Nomor);
            $lastNumber = isset($parts[2]) ? intval($parts[2]) : 0;
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $numberPadded = str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        return $prefix . $dateSegment . '-' . $numberPadded;
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $id = decrypt($id);
        $dp = DownPayment::with('getPenerima', 'getProduk', 'getCustomer')->findOrFail($id);
        return view('down-payments.show', compact('dp'));
    }

    /**
     * Show the form for editing the specified DP.
     */
    public function edit($id)
    {
        $id = decrypt($id);
        $dp = DownPayment::with('getProduk', 'getCustomer', 'getPenerima')->findOrFail($id);
        // $produk = MasterProduk::all();
        // $bank = MasterBank::all();
        // $customer = User::where('jenis_user', 'Customer')->get();
        // return view('down-payments.edit', compact('dp', 'produk', 'bank', 'customer'));
        return view('down-payments.edit', compact('dp'));
    }

    /**
     * Update the specified DP in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'customer_id' => 'required',
            'produk_id' => 'required',
            'tanggal' => 'required|date',
            'total' => 'required|numeric',
            'jenis_pembayaran' => 'required',
            'nama_bank' => 'nullable|string',
            'keterangan' => 'nullable',
            'penyetor' => 'required',
        ]);

        $id = decrypt($id);
        $dp = DownPayment::findOrFail($id);

        $dp->update([
            'customer_id' => $validatedData['customer_id'],
            'produk_id' => $validatedData['produk_id'],
            'tanggal' => $validatedData['tanggal'],
            'total' => $validatedData['total'],
            'jenis_pembayaran' => $validatedData['jenis_pembayaran'],
            'nama_bank' => $validatedData['nama_bank'] ?? null,
            'keterangan' => $validatedData['keterangan'] ?? null,
            'penerima' => auth()->user()->id,
            'penyetor' => $validatedData['penyetor'],
            'diterima_pada' => now(),
        ]);

        activity()
            ->causedBy(auth()->user()->id)
            ->withProperties(['ip' => request()->ip()])
            ->log('Mengedit Down Payment ID: ' . $dp->id . ', atas nama customer ID: ' . $dp->customer_id);

        return redirect()
            ->route('down-payments.index')
            ->with('success', 'Down Payment berhasil diupdate.');
    }

    /**
     * Remove the specified DP from storage.
     */
    public function destroy($id)
    {
        $id = decrypt($id);
        $dp = DownPayment::find($id);

        if (!$dp) {
            return response()->json(['status' => 404, 'message' => 'Data tidak ditemukan']);
        }
        activity()
            ->causedBy(auth()->user()->id)
            ->withProperties(['ip' => request()->ip()])
            ->log('Menghapus down payment ID: ' . $dp->id);
        $dp->delete();

        return response()->json(['status' => 200, 'message' => 'Down Payment berhasil dihapus']);
    }
}
