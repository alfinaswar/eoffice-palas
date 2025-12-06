@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Laporan Progres Surat Tanah</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Laporan Progres Surat Tanah</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- FILTER PROYEK, KANTOR & TANGGAL --}}

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <h4 class="card-title">Data Progres Surat Tanah</h4>
                    <p class="card-text">
                        Tabel ini berisi semua data progres surat tanah.
                    </p>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        {{-- Filter Proyek --}}
                        <div class="col-md-3 mb-2 mb-md-0">
                            <label for="filter_proyek" class="form-label mb-1">Proyek</label>
                            <select id="filter_proyek" class="select2">
                                <option value="">Semua Proyek</option>
                                @foreach ($proyeks as $proyek)
                                    <option value="{{ $proyek->id }}">{{ $proyek->NamaProyek ?? ($proyek->nama ?? '-') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Filter Kantor
                        <div class="col-md-3 mb-2 mb-md-0">
                            <label for="filter_kantor" class="form-label mb-1">Kantor</label>
                            <select id="filter_kantor" class="select2">
                                <option value="">Semua Kantor</option>
                                @if (isset($kantors))
                                    @foreach ($kantors as $kantor)
                                        <option value="{{ $kantor->KodeKantor }}">
                                            {{ $kantor->NamaKantor ?? $kantor->KodeKantor }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div> --}}
                        {{-- Filter Tanggal --}}
                        <div class="col-md-5 d-flex align-items-end gap-2"><input type="date" id="filter_tanggal_awal"
                                class="form-control" placeholder="Tanggal Awal"><input type="date"
                                id="filter_tanggal_akhir" class="form-control" placeholder="Tanggal Akhir"><button
                                type="button" class="btn btn-primary" id="btn-tampilkan-filter">Tampilkan</button></div>
                        <div class="col text-end align-self-end">
                            <div class="col text-end">
                                <button class="btn btn-success" id="btnShowExportModal"
                                    onclick="$('#modalExportOmset').modal('show')">
                                    <i class="fa fa-file-excel"></i> Cetak Laporan
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive mt-3">
                        <table class="table datanew cell-border compact stripe" id="progresSuratTanahTable" width="100%">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>KodeProyek</th>

                                    <th>Tanggal</th>
                                    <th>Deskripsi</th>
                                    <th>Legal</th>
                                    <th>NamaBank</th>
                                    <th>Keterangan</th>
                                    <th>UserCreated</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('laporan.progres-surat-tanah.modal-cetak')
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

            function loadDataTable() {
                $('#progresSuratTanahTable').DataTable({
                    responsive: true,
                    serverSide: true,
                    processing: true,
                    bDestroy: true,
                    ajax: {
                        url: "{{ route('laporan-progres-surat-tanah.index') }}",
                        data: function(d) {
                            d.proyek = $('#filter_proyek').val();
                            d.tanggal_awal = $('#filter_tanggal_awal').val();
                            d.tanggal_akhir = $('#filter_tanggal_akhir').val();


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
                            data: 'KodeProyek',
                            name: 'KodeProyek',
                            defaultContent: '-'
                        },

                        {
                            data: 'Tanggal',
                            name: 'Tanggal',
                            defaultContent: '-'
                        },
                        {
                            data: 'Deskripsi',
                            name: 'Deskripsi',
                            defaultContent: '-'
                        },
                        {
                            data: 'Legal',
                            name: 'Legal',
                            defaultContent: '-'
                        },
                        {
                            data: 'NamaBank',
                            name: 'NamaBank',
                            defaultContent: '-'
                        },
                        {
                            data: 'Keterangan',
                            name: 'Keterangan',
                            defaultContent: '-'
                        },
                        {
                            data: 'UserCreated',
                            name: 'UserCreated',
                            defaultContent: '-'
                        },
                    ]
                });
            }

            // Initial load
            loadDataTable();

            // Terapkan filter saat tombol tampilkan ditekan
            $('#btn-tampilkan-filter').on('click', function() {
                $('#progresSuratTanahTable').DataTable().ajax.reload();
            });

            // Untuk reload otomatis, bisa uncomment ini
            // $('#filter_proyek, #filter_kantor, #filter_tanggal').on('change', function() { $('#progresSuratTanahTable').DataTable().ajax.reload(); });
        });
    </script>
@endpush
