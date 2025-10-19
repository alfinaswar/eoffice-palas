@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Penawaran Harga</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('penawaran-harga.index') }}">Penawaran Harga</a></li>
                    <li class="breadcrumb-item active">Persetujuan Penawaran</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white">
                    <h4 class="card-title mb-0">Persetujuan Penawaran Harga</h4>
                    <small class="card-text mb-0">Periksa data penawaran berikut sebelum melakukan persetujuan atau
                        penolakan.</small>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tanggal</label>
                            <input type="text" class="form-control"
                                value="{{ \Carbon\Carbon::parse($penawaran->Tanggal)->format('d-m-Y') }}" readonly>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Nama Pelanggan</label>
                            <input type="text" class="form-control" value="{{ $penawaran->NamaPelanggan }}" readonly>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Keterangan</label>
                            <textarea class="form-control" rows="2" readonly>{{ $penawaran->Keterangan }}</textarea>
                        </div>
                    </div>

                    <hr class="mb-4">

                    <h5 class="mb-3 fw-bold">Detail Produk</h5>
                    <div class="table-responsive mb-2">
                        <table class="table table-sm table-hover align-middle border rounded-1" id="table-produk">
                            <thead class="text-center table-light align-middle">
                                <tr>
                                    <th style="width: 28%">Produk</th>
                                    <th style="width: 10%">Harga</th>
                                    <th style="width: 15%">Harga Yang Ditawarkan</th>
                                    <th style="width: 11%">Diskon</th>
                                    <th style="width: 10%">Jenis</th>
                                    <th style="width: 15%">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($penawaran->DetailPenawaran as $d)
                                    <tr>
                                        <td>
                                            @php
                                                $produkItem = $produk->firstWhere('id', $d->IdProduk);
                                            @endphp
                                            <input type="text" class="form-control"
                                                value="{{ $produkItem ? $produkItem->Nama : '-' }}" readonly>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control text-end"
                                                value="{{ number_format($d->Harga, 0, ',', '.') }}" readonly>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control text-end"
                                                value="{{ number_format($d->Harga, 0, ',', '.') }}" readonly>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control text-end" value="{{ $d->Diskon }}"
                                                readonly>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control text-center"
                                                value="{{ $d->JenisDiskon == 'Persen' ? '%' : $d->JenisDiskon }}" readonly>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control text-end"
                                                value="{{ number_format($d->subtotal, 0, ',', '.') }}" readonly>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row justify-content-end align-items-center">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Total Penawaran</label>
                            <input type="text" class="form-control text-end fw-bold"
                                value="{{ number_format($penawaran->Total, 0, ',', '.') }}" readonly>
                        </div>
                    </div>

                    <div class="col-12 text-end mt-4">
                        <a href="{{ route('penawaran-harga.index') }}" class="btn btn-secondary me-2">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                        <form action="{{ route('penawaran-harga.persetujuan', encrypt($penawaran->id)) }}" method="POST"
                            class="d-inline">
                            @csrf
                            <button type="submit" name="status" value="diterima" class="btn btn-success">
                                <i class="fa fa-check"></i> Setujui Penawaran
                            </button>
                            <button type="submit" name="status" value="ditolak" class="btn btn-danger ms-2">
                                <i class="fa fa-times"></i> Tolak Penawaran
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
