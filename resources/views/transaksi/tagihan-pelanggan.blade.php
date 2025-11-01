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
                                    <td class="py-1 px-0">John Doe</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="py-1 px-0">Email</th>
                                    <td class="py-1 px-0">johndoe@email.com</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="py-1 px-0">No. Telepon</th>
                                    <td class="py-1 px-0">0812-3456-7890</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="py-1 px-0">Alamat</th>
                                    <td class="py-1 px-0">Jl. Mawar No. 123, Jakarta</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="py-1 px-0">Tanggal Bergabung</th>
                                    <td class="py-1 px-0">12 Jan 2023</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-xl-8 col-lg-12 col-md-8 d-flex">
                <div class="card w-100">
                    <div class="card-body">
                        <h5>Days Overview This Month</h5>
                        <ul class="widget-attend">
                            <li class="box-attend">
                                <div class="warming-card">
                                    <h4>31</h4>
                                    <h6>Total Working
                                        Days</h6>
                                </div>
                            </li>
                            <li class="box-attend">
                                <div class="danger-card">
                                    <h4>05</h4>
                                    <h6>Abesent
                                        Days</h6>
                                </div>
                            </li>
                            <li class="box-attend">
                                <div class="light-card">
                                    <h4>28</h4>
                                    <h6>Present
                                        Days</h6>
                                </div>
                            </li>
                            <li class="box-attend">
                                <div class="warming-card">
                                    <h4>02</h4>
                                    <h6>Half
                                        Days</h6>
                                </div>
                            </li>
                            <li class="box-attend">
                                <div class="warming-card">
                                    <h4>01</h4>
                                    <h6>Late
                                        Days</h6>
                                </div>
                            </li>
                            <li class="box-attend">
                                <div class="success-card">
                                    <h4>02</h4>
                                    <h6>Holidays</h6>
                                </div>
                            </li>
                        </ul>
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
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Pdf"><img src="assets/img/icons/pdf.svg"
                        alt="img"></a>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Excel"><img src="assets/img/icons/excel.svg"
                        alt="img"></a>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Print"><i data-feather="printer"
                        class="feather-rotate-ccw"></i></a>
            </li>
        </ul>
    </div>
    <!-- /product list -->
    <div class="card table-list-card">
        <div class="card-body pb-0">
            <div class="table-top">

                <div class="input-blocks search-set mb-0">
                    <div class="search-input">
                        <a href="" class="btn btn-searchset"><i data-feather="search" class="feather-search"></i></a>
                    </div>
                </div>
                <div class="search-path">
                    <div class="d-flex align-items-center">
                        <a class="btn btn-filter" id="filter_search">
                            <i data-feather="filter" class="filter-icon"></i>
                            <span><img src="assets/img/icons/closes.svg" alt="img"></span>
                        </a>
                    </div>
                </div>
            </div>
            <!-- /Filter -->
            <div class="card" id="filter_inputs">
                <div class="card-body pb-0">
                    <div class="row">

                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="input-blocks">
                                <i data-feather="calendar" class="info-img"></i>
                                <div class="input-groupicon">
                                    <input type="text" class="datetimepicker" placeholder="Choose Date">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-sm-6 col-12 ms-auto">
                            <div class="input-blocks">
                                <a class="btn btn-filters ms-auto"> <i data-feather="search" class="feather-search"></i>
                                    Search </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Filter -->
            <div class="table-responsive">
                <table class="table  datanew">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kode Transaksi</th>
                            <th>Kode Bayar</th>
                            <th>Pembayaran Ke</th>
                            <th>Besar Cicilan</th>
                            <th>Sudah Bayar ?</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data->getTransaksi as $key => $value)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $data->KodeTransaksi }}</td>
                                <td>{{ $value->KodeBayar }}</td>
                                <td>Bulan Ke-{{ $value->CicilanKe }}</td>
                                <td>{{ 'Rp ' . number_format($value->BesarCicilan, 0, ',', '.') }}</td>
                                <td>
                                    @if ($value->Status == 'Tidak')
                                        <span class="badge bg-warning text-dark">
                                            <i data-feather="x-circle" class="me-1"></i> Belum Bayar
                                        </span>
                                    @elseif($value->Status == 'Lunas')
                                        <span class="badge bg-success">
                                            <i data-feather="check-circle" class="me-1"></i> Lunas
                                        </span>
                                    @else
                                        {{ $value->Status }}
                                    @endif
                                </td>
                                <td>
                                    @if ($value->StatusPembayaran !== 'Lunas')
                                        <button class="btn btn-success btn-sm btn-bayar"
                                            data-id="{{ encrypt($value->id) }}" data-cicilanke="{{ $value->CicilanKe }}"
                                            data-besarcicilan="{{ 'Rp ' . number_format($value->BesarCicilan, 0, ',', '.') }}"
                                            data-bs-toggle="modal" data-bs-target="#modalBayar">
                                            <i data-feather="credit-card" class="me-1"></i> Bayar
                                        </button>
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

    <!-- MODAL BAYAR -->
    @include('transaksi.modal.modal-bayar')
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modalBayar = document.getElementById('modalBayar');
            let formBayar = document.getElementById('formBayar');

            document.querySelectorAll('.btn-bayar').forEach(function(btn) {
                btn.addEventListener('click', function(event) {
                    event.preventDefault();
                    let id = btn.getAttribute('data-id');
                    let cicilanKe = btn.getAttribute('data-cicilanke');
                    let besarCicilan = btn.getAttribute('data-besarcicilan');
                    document.getElementById('id_transaksi_detail').value = id;

                    // Pastikan elemen untuk menampilkan info sudah tersedia di modal-bayar.blade.php
                    let elCicilanKe = document.getElementById('modal-cicilan-ke');
                    let elBesarCicilan = document.getElementById('modal-besar-cicilan');
                    if (elCicilanKe) {
                        elCicilanKe.textContent = `Pembayaran Ke: ${cicilanKe}`;
                    }
                    if (elBesarCicilan) {
                        elBesarCicilan.textContent = `Besar Cicilan: ${besarCicilan}`;
                    }
                });
            });
        });
    </script>
@endpush
