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
                <h4>Attendance</h4>
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
                <!-- <div class="search-path d-flex align-items-center search-path-new">
                                                             <a class="btn btn-filter" id="filter_search">
                                                              <i data-feather="filter" class="filter-icon"></i>
                                                              <span><img src="assets/img/icons/closes.svg" alt="img"></span>
                                                             </a>
                                                             <a href="employees-list.html" class="btn-list active"><i data-feather="list" class="feather-user"></i></a>
                                                             <a href="employees-grid.html" class="btn-grid"><i data-feather="grid" class="feather-user"></i></a>

                                                            </div> -->
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
                <table class="table  datanew">
                    <thead>
                        <tr>
                            <th class="no-sort">
                                <label class="checkboxs">
                                    <input type="checkbox" id="select-all">
                                    <span class="checkmarks"></span>
                                </label>
                            </th>
                            <th>Date</th>
                            <th>Clock In</th>
                            <th>Clock Out</th>
                            <th>Production</th>
                            <th>Break</th>
                            <th>Overtime</th>
                            <th>Progress</th>
                            <th>Status</th>
                            <th>Total Hours</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <label class="checkboxs">
                                    <input type="checkbox">
                                    <span class="checkmarks"></span>
                                </label>
                            </td>
                            <td>01 Jan 2023</td>
                            <td>09:15 AM</td>
                            <td>08:55 PM</td>
                            <td>9h 00m</td>
                            <td>1h 13m</td>
                            <td>00h 50m</td>
                            <td>
                                <div class="progress attendance">
                                    <div class="progress-bar progress-bar-success" role="progressbar" style="width:78%">
                                    </div>
                                    <div class="progress-bar progress-bar-warning" role="progressbar" style="width:55%">
                                    </div>
                                    <div class="progress-bar progress-bar-danger" role="progressbar" style="width:15%">
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge-linesuccess">Present</span></td>
                            <td>09h 50m</td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- /Main Wrapper -->
@endsection
