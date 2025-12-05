@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Laporan Refund</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Laporan Refund</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- FILTER PROYEK & TAHUN --}}
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <h4 class="card-title">Data Refund</h4>
                    <p class="card-text">
                        Tabel ini berisi semua data refund/pengembalian dana.
                    </p>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        {{-- Filter Proyek --}}
                        <div class="col-md-4">
                            <label for="filter_proyek" class="form-label mb-1">Proyek</label>
                            <select id="filter_proyek" class="select2">
                                <option value="">Semua Proyek</option>
                                @foreach ($proyeks as $proyek)
                                    <option value="{{ $proyek->id }}">{{ $proyek->NamaProyek ?? ($proyek->nama ?? '-') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Filter Tahun --}}
                        <div class="col-md-2">
                            <label for="filter_tahun" class="form-label mb-1">Tahun</label>
                            <select id="filter_tahun" class="select2">
                                <option value="">Semua Tahun</option>
                                @php
                                    $tahun_sekarang = date('Y');
                                    $tahun_awal = $tahun_sekarang - 5;
                                @endphp
                                @for ($tahun = $tahun_sekarang; $tahun >= $tahun_awal; $tahun--)
                                    <option value="{{ $tahun }}">{{ $tahun }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-2 align-self-end">
                            <button type="button" class="btn btn-primary" id="btn-tampilkan-filter">
                                <i class="fas fa-filter me-1"></i> Tampilkan
                            </button>
                        </div>
                        <div class="col text-end align-self-end">
                            <div class="col text-end">
                                <a href="{{ route('laporan-refund.download', ['format' => 'excel']) }}"
                                    class="btn btn-success" id="btnExportExcel">
                                    <i class="fa fa-file-excel"></i> Cetak Laporan
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table datanew cell-border compact stripe" id="refundTable" width="100%">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Tanggal</th>
                                    <th>Nominal</th>
                                    <th>Kategori</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                $('#refundTable').DataTable({
                    responsive: true,
                    serverSide: true,
                    processing: true,
                    bDestroy: true,
                    ajax: {
                        url: "{{ route('laporan-refund.index') }}",
                        data: function(d) {
                            d.proyek_id = $('#filter_proyek').val();
                            d.tahun = $('#filter_tahun').val();
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
                            data: 'Tanggal',
                            name: 'Tanggal',
                            defaultContent: '-'
                        },
                        {
                            data: 'Nominal',
                            name: 'Nominal',
                            defaultContent: '-'
                        },
                        {
                            data: 'Kategori',
                            name: 'Kategori',
                            defaultContent: '-'
                        },
                        {
                            data: 'Deskripsi',
                            name: 'Deskripsi',
                            defaultContent: '-'
                        }
                    ]
                });
            }

            // Initial load
            loadDataTable();

            // Terapkan filter saat tombol tampilkan ditekan
            $('#btn-tampilkan-filter').on('click', function() {
                $('#refundTable').DataTable().ajax.reload();
            });

            // Export Excel Filtered
            $('#btnExportExcel').on('click', function(e) {
                e.preventDefault();
                let base = $(this).attr('href');
                let proyek = $('#filter_proyek').val() || '';
                let tahun = $('#filter_tahun').val() || '';
                let url = base + '?proyek_id=' + proyek + '&tahun=' + tahun + '&format=excel';
                window.location.href = url;
            });

            // Optional: Uncomment for auto refresh on filter change
            // $('#filter_proyek, #filter_tahun').on('change', function() { $('#refundTable').DataTable().ajax.reload(); });
        });
    </script>
@endpush
