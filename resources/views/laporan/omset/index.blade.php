@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Laporan Omset</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Laporan Omset</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Filter tanggal atau proyek bisa ditambahkan di sini jika dibutuhkan --}}
    <div class="row mb-3">
        {{-- Contoh: filter tanggal --}}
        <div class="col-md-6">
            <form id="filterOmsetForm" class="row g-2 align-items-end">
                <div class="col">
                    <label for="tgl_dari" class="form-label">Dari Tanggal</label>
                    <input type="date" id="tgl_dari" name="tgl_dari" class="form-control">
                </div>
                <div class="col">
                    <label for="tgl_sampai" class="form-label">Sampai Tanggal</label>
                    <input type="date" id="tgl_sampai" name="tgl_sampai" class="form-control">
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-primary">Tampilkan</button>
                </div>
            </form>
        </div>
        <div class="col text-end">
            <a class="btn btn-success" href="#" id="btnExportExcel">
                <i class="fa fa-file-excel"></i> Export Excel
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <h4 class="card-title">Tabel Omset</h4>
                    <p class="card-text">
                        Tabel ini berisi data omset perusahaan dalam periode terpilih.
                    </p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table datanew cell-border compact stripe" id="omsetTable" width="100%">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Tanggal</th>
                                    <th>Proyek</th>
                                    <th>Pelanggan</th>
                                    <th>Nilai Transaksi (Rp)</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Data dinamis (dari server/datatable ajax) --}}
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-end">Total Omset:</th>
                                    <th id="totalOmset" class="text-end">Rp 0</th>
                                    <th></th>
                                </tr>
                            </tfoot>
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
        function formatRupiah(angka) {
            if (!angka) return 'Rp 0';
            return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        $(document).ready(function() {
            var table = $('#omsetTable').DataTable({
                responsive: true,
                serverSide: true,
                processing: true,
                bDestroy: true,
                ajax: {
                    url: "{{ route('laporan-omset.index') }}",
                    data: function(d) {
                        d.tgl_dari = $('#tgl_dari').val();
                        d.tgl_sampai = $('#tgl_sampai').val();
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
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'IdTransaksi',
                        name: 'IdTransaksi'
                    },
                    {
                        data: 'KodeBayar',
                        name: 'KodeBayar'
                    },
                    {
                        data: 'IdPelanggan',
                        name: 'IdPelanggan'
                    },
                    {
                        data: 'CicilanKe',
                        name: 'CicilanKe'
                    },
                    {
                        data: 'BesarCicilan',
                        name: 'BesarCicilan',
                        className: 'text-end',
                        render: function(data, type, row) {
                            return formatRupiah(data);
                        }
                    },
                    {
                        data: 'TotalPembayaran',
                        name: 'TotalPembayaran',
                        className: 'text-end',
                        render: function(data, type, row) {
                            return formatRupiah(data);
                        }
                    },
                    {
                        data: 'TanggalJatuhTempo',
                        name: 'TanggalJatuhTempo'
                    },
                    {
                        data: 'DibayarOleh',
                        name: 'DibayarOleh'
                    },
                    {
                        data: 'DibayarPada',
                        name: 'DibayarPada'
                    },
                    {
                        data: 'Status',
                        name: 'Status'
                    }
                ],
            });

            $('#filterOmsetForm').on('submit', function(e) {
                e.preventDefault();
                table.ajax.reload();
            });

            $('#btnExportExcel').on('click', function(e) {
                e.preventDefault();
                var tgl_dari = $('#tgl_dari').val();
                var tgl_sampai = $('#tgl_sampai').val();
                let params = '?';
                if (tgl_dari) params += 'tgl_dari=' + tgl_dari + '&';
                if (tgl_sampai) params += 'tgl_sampai=' + tgl_sampai;
                window.location = "{{ route('laporan-omset.index') }}/export-excel" + params;
            });
        });
    </script>
@endpush
