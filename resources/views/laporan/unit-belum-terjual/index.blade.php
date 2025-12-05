@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Laporan Unit Belum Terjual</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Laporan Unit Belum Terjual</li>
                </ul>
            </div>
        </div>
    </div>



    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <h4 class="card-title">List Produk</h4>
                    <p class="card-text">
                        Tabel ini berisi semua data produk yang ada.
                    </p>
                </div>
                <div class="card-body">
                    {{-- FILTER PROYEK --}}
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="filterProyek" class="form-label">Filter Proyek</label>
                            <select class="form-control select2" id="filterProyek">
                                <option value="">Semua Proyek</option>

                                @foreach ($proyeks as $proyek)
                                    <option value="{{ $proyek->id }}">{{ $proyek->NamaProyek }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2" style="margin-top: 30px;">
                            <button class="btn btn-primary" id="btnTampilkan"><i class="fa fa-search"></i>
                                Tampilkan</button>
                        </div>
                        <div class="col text-end" style="margin-top: 30px;">

                            <button class="btn btn-success" id="btnShowExportModal"
                                onclick="$('#modalExportOmset').modal('show')">
                                <i class="fa fa-file-excel"></i> Cetak Laporan
                            </button>

                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table datanew cell-border compact stripe" id="produkTable" width="100%">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Grade</th>
                                    <th>Jenis</th>
                                    <th>Proyek</th>
                                    <th>Luas</th>
                                    <th>H. Meter</th>
                                    <th>Dp</th>
                                    <th>Angsuran</th>
                                    <th>H. Normal</th>
                                    <th>Tersedia ?</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('laporan.unit-belum-terjual.modal-cetak')
@endsection

@push('js')
    @if (Session::get('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ Session::get('success') }}',
                iconColor: '#4BCC1F',
                confirmButtonText: 'Oke',
                confirmButtonColor: '#4BCC1F',
            });
        </script>
    @endif
    <script>
        $(document).ready(function() {
            function loadDataTable(proyekId = '') {
                $('#produkTable').DataTable({
                    responsive: true,
                    serverSide: true,
                    processing: true,
                    bDestroy: true,
                    ajax: {
                        url: "{{ route('laporan-unit-belum-terjual.index') }}",
                        data: function(d) {
                            d.proyek_id = proyekId;
                        }
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
                            data: 'Kode',
                            name: 'Kode'
                        },
                        {
                            data: 'Nama',
                            name: 'Nama'
                        },
                        {
                            data: 'Grade',
                            name: 'Grade'
                        },
                        {
                            data: 'Jenis',
                            name: 'Jenis'
                        },
                        {
                            data: 'Proyek',
                            name: 'Proyek'
                        },
                        {
                            data: 'Luas',
                            name: 'Luas'
                        },
                        {
                            data: 'HargaPerMeter',
                            name: 'HargaPerMeter'
                        },
                        {
                            data: 'Dp',
                            name: 'Dp'
                        },
                        {
                            data: 'BesarAngsuran',
                            name: 'BesarAngsuran'
                        },
                        {
                            data: 'HargaNormal',
                            name: 'HargaNormal'
                        },
                        {
                            data: 'Status',
                            name: 'Status'
                        },
                    ]
                });
            }

            // Initial load
            loadDataTable();

            // Handle filter
            $('#btnTampilkan').click(function() {
                var proyekId = $('#filterProyek').val();
                $('#produkTable').DataTable().destroy();
                loadDataTable(proyekId);
            });
        });
    </script>
@endpush
