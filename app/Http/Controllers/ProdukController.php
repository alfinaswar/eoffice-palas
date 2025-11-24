<?php

namespace App\Http\Controllers;

use App\Models\MasterGrade;
use App\Models\MasterJenis;
use App\Models\MasterProjek;
use App\Models\Produk;
use Illuminate\Http\Request;
use Laraindo\RupiahFormat;
use Yajra\DataTables\Facades\DataTables;

class ProdukController extends Controller
{
    public function index(Request $request)
    {

        // dd($data);
        if ($request->ajax()) {
            $data = Produk::with('getGrade', 'getJenis', 'getProyek', 'getDataBooking')->latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $encryptedId = encrypt($row->id);
                    return '
                        <a href="' . route('produk.edit', $encryptedId) . '" class="btn btn-sm btn-warning">Edit</a>
                        <button class="btn btn-sm btn-danger btn-delete" data-id="' . $encryptedId . '">Hapus</button>
                    ';
                })
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
                    if ($row->getDataBooking) {
                        $namaBooking = '-';
                        if (isset($row->getDataBooking->getCustomer) && $row->getDataBooking->getCustomer) {
                            $namaBooking = $row->getDataBooking->getCustomer->name;
                        } elseif (isset($row->getDataBooking->Nama)) {
                            $namaBooking = $row->getDataBooking->Nama;
                        }
                        return '<span class="badge bg-danger">Sudah Dibooking</span> <br><small>Customer: ' . $namaBooking . '</small>';
                    } else {
                        return '<span class="badge bg-success">Tersedia</span>';
                    }
                })
                ->rawColumns(['action', 'Status'])
                ->make(true);
        }
        return view('produk.index');
    }

    public function create()
    {
        $jenis = MasterJenis::get();
        $grade = MasterGrade::get();
        $proyek = MasterProjek::get();
        return view('produk.create', compact('jenis', 'grade', 'proyek'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Kode' => 'nullable|string|max:255',
            'Nama' => 'required|string|max:255',
            'Grade' => 'nullable|string|max:255',
            'Jenis' => 'nullable|string|max:255',
            'Proyek' => 'nullable|string|max:255',
            'Luas' => 'nullable|string|max:255',
            'HargaNormal' => 'nullable|string|max:255',
            'HargaPerMeter' => 'nullable|string|max:255',
            'HargaKredit' => 'nullable|string|max:255',
            'Dp' => 'nullable|string|max:255',
            'BesarAngsuran' => 'nullable|string|max:255',
            'Diskon' => 'nullable|string|max:255',
            'HargaDiskon' => 'nullable|string|max:255',
            'HargaPerMeter2' => 'nullable|string|max:255',
            'HargaKredit2' => 'nullable|string|max:255',
            'BesarAngsuran2' => 'nullable|string|max:255',
            'Keterangan' => 'nullable|string|max:255',
            'Gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'Status' => 'nullable|in:Y,N',
        ]);
        $data = $request->all();
        $gambarPath = null;
        if ($request->hasFile('Gambar')) {
            $gambar = $request->file('Gambar');
            $namaFile = pathinfo($gambar->getClientOriginalName(), PATHINFO_FILENAME);
            $ext = $gambar->getClientOriginalExtension();
            $namaBaru = $namaFile . '_' . microtime(true) . '.' . $ext;
            $gambarPath = $gambar->storeAs('produk', $namaBaru, 'public');
            $data['Gambar'] = basename($gambarPath);
        }
        $data['UserCreate'] = auth()->user()->name;
        if (isset($data['Dp'])) {
            $data['Dp'] = str_replace('.', '', $data['Dp']);
        }
        if (isset($data['Diskon'])) {
            $data['Diskon'] = str_replace('.', '', $data['Diskon']);
        }
        if (isset($data['HargaNormal'])) {
            $data['HargaNormal'] = str_replace('.', '', $data['HargaNormal']);
        }
        if (isset($data['HargaPerMeter'])) {
            $data['HargaPerMeter'] = str_replace('.', '', $data['HargaPerMeter']);
        }
        if (isset($data['HargaKredit'])) {
            $data['HargaKredit'] = str_replace('.', '', $data['HargaKredit']);
        }
        if (isset($data['BesarAngsuran'])) {
            $data['BesarAngsuran'] = str_replace('.', '', $data['BesarAngsuran']);
        }
        if (isset($data['HargaDiskon'])) {
            $data['HargaDiskon'] = str_replace('.', '', $data['HargaDiskon']);
        }
        if (isset($data['HargaPerMeter2'])) {
            $data['HargaPerMeter2'] = str_replace('.', '', $data['HargaPerMeter2']);
        }
        if (isset($data['HargaKredit2'])) {
            $data['HargaKredit2'] = str_replace('.', '', $data['HargaKredit2']);
        }
        if (isset($data['BesarAngsuran2'])) {
            $data['BesarAngsuran2'] = str_replace('.', '', $data['BesarAngsuran2']);
        }
        $data['KodeKantor'] = auth()->user()->KodeKantor;
        Produk::create($data);

        activity()
            ->causedBy(auth()->user()->id)
            ->withProperties(['ip' => request()->ip()])
            ->log('Menambah produk baru: ' . $request->Nama);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $id = decrypt($id);
        $produk = Produk::findOrFail($id);
        $jenis = MasterJenis::get();
        $grade = MasterGrade::get();
        $proyek = MasterProjek::get();
        return view('produk.edit', compact('produk', 'jenis', 'grade', 'proyek'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Kode' => 'nullable|string|max:255',
            'Nama' => 'required|string|max:255',
            'Grade' => 'nullable|string|max:255',
            'Jenis' => 'nullable|string|max:255',
            'Proyek' => 'nullable|stri  ng|max:255',
            'Luas' => 'nullable|string|max:255',
            'HargaNormal' => 'nullable|string|max:255',
            'HargaPerMeter' => 'nullable|string|max:255',
            'HargaKredit' => 'nullable|string|max:255',
            'Dp' => 'nullable|string|max:255',
            'BesarAngsuran' => 'nullable|string|max:255',
            'Diskon' => 'nullable|string|max:255',
            'HargaDiskon' => 'nullable|string|max:255',
            'HargaPerMeter2' => 'nullable|string|max:255',
            'HargaKredit2' => 'nullable|string|max:255',
            'BesarAngsuran2' => 'nullable|string|max:255',
            'Keterangan' => 'nullable|string|max:255',
            'Gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'Status' => 'nullable|in:Y,N',
        ]);

        $produk = Produk::findOrFail($id);
        $data = $request->all();
        $gambarPath = null;
        if ($request->hasFile('Gambar')) {
            $gambar = $request->file('Gambar');
            $namaFile = pathinfo($gambar->getClientOriginalName(), PATHINFO_FILENAME);
            $ext = $gambar->getClientOriginalExtension();
            $namaBaru = $namaFile . '_' . microtime(true) . '.' . $ext;
            $gambarPath = $gambar->storeAs('produk', $namaBaru, 'public');
            $data['Gambar'] = basename($gambarPath);
        } else {
            unset($data['Gambar']);
        }
        $data['UserUpdated'] = auth()->user()->name;
        if (isset($data['Dp'])) {
            $data['Dp'] = str_replace('.', '', $data['Dp']);
        }
        if (isset($data['Diskon'])) {
            $data['Diskon'] = str_replace('.', '', $data['Diskon']);
        }
        if (isset($data['HargaNormal'])) {
            $data['HargaNormal'] = str_replace('.', '', $data['HargaNormal']);
        }
        if (isset($data['HargaPerMeter'])) {
            $data['HargaPerMeter'] = str_replace('.', '', $data['HargaPerMeter']);
        }
        if (isset($data['HargaKredit'])) {
            $data['HargaKredit'] = str_replace('.', '', $data['HargaKredit']);
        }
        if (isset($data['BesarAngsuran'])) {
            $data['BesarAngsuran'] = str_replace('.', '', $data['BesarAngsuran']);
        }
        if (isset($data['HargaDiskon'])) {
            $data['HargaDiskon'] = str_replace('.', '', $data['HargaDiskon']);
        }
        if (isset($data['HargaPerMeter2'])) {
            $data['HargaPerMeter2'] = str_replace('.', '', $data['HargaPerMeter2']);
        }
        if (isset($data['HargaKredit2'])) {
            $data['HargaKredit2'] = str_replace('.', '', $data['HargaKredit2']);
        }
        if (isset($data['BesarAngsuran2'])) {
            $data['BesarAngsuran2'] = str_replace('.', '', $data['BesarAngsuran2']);
        }
        $data['KodeKantor'] = auth()->user()->KodeKantor;
        $produk->update($data);

        activity()
            ->causedBy(auth()->user()->id)
            ->withProperties(['ip' => request()->ip()])
            ->log('Memperbarui produk: ' . $request->Nama);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $id = decrypt($id);
        $produk = Produk::find($id);

        if (!$produk) {
            return response()->json(['status' => 404, 'message' => 'Data tidak ditemukan']);
        }
        activity()
            ->causedBy(auth()->user()->id)
            ->withProperties(['ip' => request()->ip()])
            ->log('Menghapus produk: ' . $produk->Nama);
        $produk->delete();

        return response()->json(['status' => 200, 'message' => 'Produk berhasil dihapus']);
    }
}
