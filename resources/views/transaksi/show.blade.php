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
                                    <th scope="row" class="py-1 px-0">Nomor Identitas</th>
                                    <td class="py-1 px-0">{{ $data->nik }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="py-1 px-0">Nama</th>
                                    <td class="py-1 px-0">{{ $data->name }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="py-1 px-0">Email</th>
                                    <td class="py-1 px-0">
                                        <a href="mailto:{{ $data->email }}">{{ $data->email }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row" class="py-1 px-0">No. Telepon</th>
                                    <td class="py-1 px-0">{{ $data->nohp }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="py-1 px-0">Alamat</th>
                                    <td class="py-1 px-0">{{ $data->alamat }}</td>
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
                    <!-- <div class="total-employees">
                                                                                                                                                                                  <h6><i data-feather="users" class="feather-user"></i>Total Employees <span>21</span></h6>
                                                                                                                                                                                 </div> -->
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
                        <div class="layout-hide-box">
                            <a href="javascript:void(0);" class="me-3 layout-box"><i data-feather="layout"
                                    class="feather-search"></i></a>
                            <div class="layout-drop-item card">
                                <div class="drop-item-head">
                                    <h5>Want to manage datatable?</h5>
                                    <p>Please drag and drop your column to reorder your table and enable see option
                                        as you want.</p>
                                </div>
                                <ul>
                                    <li>
                                        <div
                                            class="status-toggle modal-status d-flex justify-content-between align-items-center">
                                            <span class="status-label"><i data-feather="menu"
                                                    class="feather-menu"></i>Shop</span>
                                            <input type="checkbox" id="option1" class="check" checked>
                                            <label for="option1" class="checktoggle"> </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div
                                            class="status-toggle modal-status d-flex justify-content-between align-items-center">
                                            <span class="status-label"><i data-feather="menu"
                                                    class="feather-menu"></i>Product</span>
                                            <input type="checkbox" id="option2" class="check" checked>
                                            <label for="option2" class="checktoggle"> </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div
                                            class="status-toggle modal-status d-flex justify-content-between align-items-center">
                                            <span class="status-label"><i data-feather="menu"
                                                    class="feather-menu"></i>Reference No</span>
                                            <input type="checkbox" id="option3" class="check" checked>
                                            <label for="option3" class="checktoggle"> </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div
                                            class="status-toggle modal-status d-flex justify-content-between align-items-center">
                                            <span class="status-label"><i data-feather="menu"
                                                    class="feather-menu"></i>Date</span>
                                            <input type="checkbox" id="option4" class="check" checked>
                                            <label for="option4" class="checktoggle"> </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div
                                            class="status-toggle modal-status d-flex justify-content-between align-items-center">
                                            <span class="status-label"><i data-feather="menu"
                                                    class="feather-menu"></i>Responsible Person</span>
                                            <input type="checkbox" id="option5" class="check" checked>
                                            <label for="option5" class="checktoggle"> </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div
                                            class="status-toggle modal-status d-flex justify-content-between align-items-center">
                                            <span class="status-label"><i data-feather="menu"
                                                    class="feather-menu"></i>Notes</span>
                                            <input type="checkbox" id="option6" class="check" checked>
                                            <label for="option6" class="checktoggle"> </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div
                                            class="status-toggle modal-status d-flex justify-content-between align-items-center">
                                            <span class="status-label"><i data-feather="menu"
                                                    class="feather-menu"></i>Quantity</span>
                                            <input type="checkbox" id="option7" class="check" checked>
                                            <label for="option7" class="checktoggle"> </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div
                                            class="status-toggle modal-status d-flex justify-content-between align-items-center">
                                            <span class="status-label"><i data-feather="menu"
                                                    class="feather-menu"></i>Actions</span>
                                            <input type="checkbox" id="option8" class="check" checked>
                                            <label for="option8" class="checktoggle"> </label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-sort">
                    <i data-feather="sliders" class="info-img"></i>
                    <select class="select">
                        <option>Sort by Date</option>
                        <option>Newest</option>
                        <option>Oldest</option>
                    </select>
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
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="input-blocks">
                                <i data-feather="stop-circle" class="info-img"></i>
                                <select class="select">
                                    <option>Choose Status</option>
                                    <option>Present</option>
                                    <option>Absent</option>
                                    <option>Holiday </option>
                                </select>
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
                <table class="table  datanew" id="transaksi-table">
                    <thead>
                        <tr>
                            <th class="no-sort">
                                <label class="checkboxs">
                                    <input type="checkbox" id="select-all">
                                    <span class="checkmarks"></span>
                                </label>
                            </th>
                            <th>KodeTransaksi</th>
                            <th>TanggalTransaksi</th>
                            <th>IdPelanggan</th>
                            <th>JenisTransaksi</th>
                            <th>TotalHarga</th>
                            <th>TipePembayaran</th>
                            <th>DurasiPembayaran</th>
                            <th>SisaBayar</th>
                        </tr>
                    </thead>
                    <tbody>


                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- /Main Wrapper -->
@endsection
@push('js')
    <script>
        function loadDataTable() {
            $('#transaksi-table').DataTable({
                responsive: true,
                serverSide: true,
                processing: true,
                bDestroy: true,
                ajax: {
                    url: "{{ route('transaksi.list-tagihan') }}",
                },
                language: {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Memuat...</span>',
                    paginate: {
                        next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                        previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>'
                    }
                },
                columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'KodeTransaksi',
                    name: 'KodeTransaksi'
                },
                {
                    data: 'TanggalTransaksi',
                    name: 'TanggalTransaksi'
                },
                {
                    data: 'IdPelanggan',
                    name: 'IdPelanggan'
                },
                {
                    data: 'JenisTransaksi',
                    name: 'JenisTransaksi'
                },
                {
                    data: 'TotalHarga',
                    name: 'TotalHarga'
                },
                {
                    data: 'TipePembayaran',
                    name: 'TipePembayaran'
                },
                {
                    data: 'DurasiPembayaran',
                    name: 'DurasiPembayaran'
                },
                {
                    data: 'SisaBayar',
                    name: 'SisaBayar'
                }
                ]
            });
        }

        loadDataTable();
                                                                    });
    </script>
@endpush