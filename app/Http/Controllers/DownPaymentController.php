<?php

namespace App\Http\Controllers;

use App\Models\BookingList;
use App\Models\DownPayment;
use App\Models\MasterBank;
use App\Models\Produk;
use App\Models\TransaksiKeuangan;
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
                ->editColumn('SisaBayar', function ($row) {
                    return 'Rp ' . number_format($row->SisaBayar, 0, ',', '.');
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
            'IdBooking' => 'required|numeric',
            'IdProduk' => 'required|numeric',
            'NamaPelanggan' => 'required|string',
            'Tanggal' => 'required|date',
            'JenisPembayaran' => 'required',
            'Bank' => 'required|string',
            'Penerima' => 'required|string',
            'Penyetor' => 'required|string',
            'TotalSetoran' => 'required|string',
            'SisaBayar' => 'required|string',
            'Keterangan' => 'nullable|string',
        ]);

        // Hitung nominal masuk (setoran DP) dan sisa bayar sesuai format
        $nominalMasuk = preg_replace('/[^\d]/', '', $request->get('TotalSetoran'));
        $sisaBayar = preg_replace('/[^\d]/', '', $request->get('SisaBayar'));

        // Ambil objek booking terkait, jika perlu informasi lanjut
        $booking = BookingList::find($request->get('IdBooking'));
        $produk = Produk::find($request->get('IdProduk'));
        $pelanggan = User::find($request->get('NamaPelanggan'));

        $dp = DownPayment::create([
            'IdBooking' => $request->get('IdBooking'),
            'Nomor' => $this->generateKodeDP(),
            'IdProduk' => $request->get('IdProduk'),
            'NamaPelanggan' => $request->get('NamaPelanggan'), // gunakan ID user customer
            'Tanggal' => $request->get('Tanggal'),
            'Total' => $nominalMasuk,
            'SisaBayar' => $sisaBayar,
            'JenisPembayaran' => $request->get('JenisPembayaran'),
            'Keterangan' => $request->get('Keterangan'),
            'Penerima' => auth()->user()->id,
            'DiterimaPada' => $request->get('DiterimaPada') ?? now(),
            'Penyetor' => $request->get('Penyetor'),
            'DiserahkanPada' => $request->get('DiserahkanPada'),
            'UserCreated' => $request->get('UserCreated') ?? auth()->user()->id,
        ]);

        // Pengelolaan transaksi keuangan (kategori: DownPayment)
        $saldoSebelumnya = TransaksiKeuangan::where('NamaBank', $request->get('Bank'))
            ->orderBy('Tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->value('SaldoSetelah');

        $saldoSebelumnya = $saldoSebelumnya ? preg_replace('/[^\d]/', '', $saldoSebelumnya) : 0;
        $saldoSetelah = (int) $saldoSebelumnya + (int) $nominalMasuk;

        TransaksiKeuangan::create([
            'Tanggal' => $request->get('Tanggal'),
            'Jenis' => 'IN',
            'Kategori' => 'DownPayment',
            'Deskripsi' => 'Down Payment untuk booking nomor: ' . ($booking ? $booking->Nomor : $request->get('IdBooking')) . ', atas nama: ' . ($pelanggan ? $pelanggan->name : '-'),
            'Nominal' => $nominalMasuk,
            'NamaBank' => $request->get('Bank'),
            'RefType' => 'DownPayment',
            'RefId' => $dp->id,
            'SaldoSetelah' => $saldoSetelah,
            'UserCreate' => auth()->user()->name,
        ]);

        $transaksisAfter = TransaksiKeuangan::where('NamaBank', $request->get('Bank'))
            ->where('id', '>', function ($query) use ($request) {
                $query->select('id')
                    ->from('transaksi_keuangans')
                    ->where('NamaBank', $request->get('Bank'))
                    ->orderBy('id', 'desc')
                    ->limit(1);
            })
            ->orderBy('id', 'asc')
            ->get();

        $saldo = $saldoSetelah;
        foreach ($transaksisAfter as $trx) {
            $delta = ($trx->Jenis === 'IN' ? 1 : -1) * preg_replace('/[^\d]/', '', $trx->Nominal);
            $saldo += $delta;
            $trx->update(['SaldoSetelah' => $saldo]);
        }

        activity()
            ->causedBy(auth()->user()->id)
            ->withProperties(['ip' => request()->ip()])
            ->log('Menambah down payment baru ID: ' . $dp->id . ', atas nama customer ID: ' . $dp->NamaPelanggan);

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
            ->orderBy('id', 'desc')
            ->first();

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
        $dp = DownPayment::with('getProduk', 'getCustomer', 'getPenerima', 'getBooking')->findOrFail($id);
        $produk = Produk::get();
        $bank = MasterBank::all();
        $customer = User::where('jenis_user', 'Customer')->get();
        return view('down-payments.edit', compact('dp', 'produk', 'bank', 'customer'));
    }

    /**
     * Update the specified DP in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'Tanggal' => 'required|date',
            'JenisPembayaran' => 'required',
            'Bank' => 'required',
            'Penerima' => 'required|string',
            'Penyetor' => 'required|string',
            'Total' => 'required|string',
            'SisaBayar' => 'required|string',
            'Keterangan' => 'nullable|string',
        ]);

        $id = decrypt($id);
        $dp = DownPayment::findOrFail($id);

        $dp->IdBooking = $request->get('IdBooking');
        $dp->IdProduk = $request->get('IdProduk');
        $dp->NamaPelanggan = $request->get('NamaPelanggan');
        $dp->Tanggal = $request->get('Tanggal');
        $dp->Total = preg_replace('/\D/', '', $request->get('Total'));
        $dp->SisaBayar = $request->get('SisaBayarRaw');
        $dp->JenisPembayaran = $request->get('JenisPembayaran');
        $dp->Keterangan = $request->get('Keterangan');
        $dp->Penyetor = $request->get('Penyetor');
        $dp->UserUpdated = auth()->user()->id;

        // simpan perubahan
        $dp->save();

        activity()
            ->causedBy(auth()->user()->id)
            ->withProperties(['ip' => request()->ip()])
            ->log('Mengedit down payment ID: ' . $dp->id . ', atas nama customer ID: ' . $dp->NamaPelanggan);

        return redirect()
            ->route('dp.index')
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
