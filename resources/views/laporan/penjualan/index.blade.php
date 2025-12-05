@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Laporan Penjualan</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Laporan Penjualan</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- FILTER PROYEK, PRODUK & TAHUN --}}

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <h4 class="card-title">Data Penjualan</h4>
                    <p class="card-text">
                        Tabel ini berisi semua data penjualan/transaksi.
                    </p>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        {{-- Filter Proyek --}}
                        <div class="col-md-3">
                            <label for="filter_proyek" class="form-label mb-1">Proyek</label>
                            <select id="filter_proyek" class="select2">
                                <option value="">Semua Proyek</option>
                                @foreach ($Proyek as $proyek)
                                    <option value="{{ $proyek->id }}">{{ $proyek->NamaProyek ?? ($proyek->nama ?? '-') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Filter Produk --}}
                        <div class="col-md-3">
                            <label for="filter_produk" class="form-label mb-1">Produk</label>
                            <select id="filter_produk" class="select2">
                                <option value="">Semua Produk</option>
                                @foreach ($MasterProduk as $produk)
                                    <option value="{{ $produk->id }}">{{ $produk->Nama }}</option>
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
                                <button class="btn btn-success" id="btnShowExportModal"
                                    onclick="$('#modalExportOmset').modal('show')">
                                    <i class="fa fa-file-excel"></i> Cetak Laporan
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table datanew cell-border compact stripe" id="penjualanTable" width="100%">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nama</th>
                                    <th>Total Harga</th>
                                    <th>Produk & Grade</th>
                                    <th>Luas</th>
                                    <th>Booking Fee</th>
                                    <th>Dp</th>
                                    <th>Sisa Pembayaran</th>
                                    <th>Total Uang Masuk</th>
                                    <th>Tanggal Booking</th>
                                    <th>No HP</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('laporan.penjualan.modal-cetak')
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
                $('#penjualanTable').DataTable({
                    responsive: true,
                    serverSide: true,
                    processing: true,
                    bDestroy: true,
                    ajax: {
                        url: "{{ route('laporan-penjualan.index') }}",
                        data: function(d) {
                            d.proyek = $('#filter_proyek').val();
                            d.produk = $('#filter_produk').val();
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
                            data: 'NamaPelanggan',
                            name: 'NamaPelanggan',
                            defaultContent: '-'
                        },
                        {
                            data: 'TotalHarga',
                            name: 'TotalHarga',
                            defaultContent: '-'
                        },
                        {
                            data: 'ProdukGrade',
                            name: 'ProdukGrade',
                            defaultContent: '-'
                        },
                        {
                            data: 'Luas',
                            name: 'Luas',
                            defaultContent: '-'
                        },
                        {
                            data: 'BookingFee',
                            name: 'BookingFee',
                            defaultContent: '-'
                        },
                        {
                            data: 'Dp',
                            name: 'Dp',
                            defaultContent: '-'
                        },
                        {
                            data: 'SisaPembayaran',
                            name: 'SisaPembayaran',
                            defaultContent: '-'
                        },
                        {
                            data: 'TotalUangMasuk',
                            name: 'TotalUangMasuk',
                            defaultContent: '-'
                        },
                        {
                            data: 'TanggalBooking',
                            name: 'TanggalBooking',
                            defaultContent: '-'
                        },
                        {
                            data: 'NoHP',
                            name: 'NoHP',
                            defaultContent: '-'
                        },
                    ]
                });
            }

            // Initial load
            loadDataTable();

            // Terapkan filter saat tombol tampilkan ditekan
            $('#btn-tampilkan-filter').on('click', function() {
                $('#penjualanTable').DataTable().ajax.reload();
            });

            // Jika ingin reload otomatis saat ganti filter, bisa uncomment baris berikut:
            // $('#filter_proyek, #filter_produk, #filter_tahun').on('change', function() { $('#penjualanTable').DataTable().ajax.reload(); });
        });
    </script>
@endpush
