@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Formulir Transaksi</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('transaksi.index') }}">Transaksi</a></li>
                    <li class="breadcrumb-item active">Tambah Transaksi</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="attendance-widget">
        <div class="row">
            <div class="col-xl-4 col-lg-12 col-md-4 d-flex">
                <div class="card w-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-4">Informasi Pelanggan</h5>
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <th scope="row" class="py-1 px-0">Nama</th>
                                    <td class="py-1 px-0">{{ $data->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="py-1 px-0">Email</th>
                                    <td class="py-1 px-0">{{ $data->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="py-1 px-0">No. Telepon</th>
                                    <td class="py-1 px-0">{{ $data->nohp ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="py-1 px-0">Alamat</th>
                                    <td class="py-1 px-0">{{ $data->alamat ?? '-' }}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        </div>
    </div>
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Daftar Tagihan</h4>
            </div>
        </div>
        <ul class="table-top-head">
            {{-- <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Pdf"><img src="assets/img/icons/pdf.svg"
                        alt="img"></a>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Excel"><img src="assets/img/icons/excel.svg"
                        alt="img"></a>
            </li> --}}
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Print"><i data-feather="printer"
                        class="feather-rotate-ccw"></i></a>
            </li>
        </ul>
    </div>
    <!-- /product list -->

    <div class="card table-list-card">
        <div class="card-header bg-dark">
            <h4 class="card-title mb-0">Daftar Transaksi</h4>
            <p class="card-text mb-0">
                Daftar Transaksi
            </p>
        </div>
        <div class="card-body pb-0">

            <div class="table-responsive">
                <table class="table datanew">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kode Transaksi</th>
                            <th>Tanggal Transaksi</th>
                            <th>Jenis Transaksi</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data->getTransaksi as $key => $value)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $value->KodeTransaksi }}</td>
                                <td>{{ $value->TanggalTransaksi }}</td>
                                <td>{{ $value->JenisTransaksi }}</td>
                                <td>{{ 'Rp ' . number_format($value->TotalHarga, 0, ',', '.') }}</td>
                                <td>
                                    @if ($value->StatusPembayaran !== 'Lunas')
                                        <span class="badge bg-warning text-dark">
                                            <i data-feather="x-circle" class="me-1"></i> {{ $value->StatusPembayaran }}
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            <i data-feather="check-circle" class="me-1"></i> Lunas
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if ($value->StatusPembayaran !== 'Lunas')
                                        <a href="{{ route('transaksi.show', encrypt($value->id)) }}"
                                            class="btn btn-success btn-sm">
                                            <i data-feather="credit-card" class="me-1"></i> Bayar
                                        </a>
                                    @else
                                        <span class="badge bg-success">
                                            <i data-feather="check-circle" class="me-1"></i> Lunas
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
