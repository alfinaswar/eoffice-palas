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

    {{-- FILTER PRODUK & TAHUN --}}


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
                        <div class="col-md-3">
                            <label for="filter_produk" class="form-label mb-1">Produk</label>
                            <select id="filter_produk" class="select2">
                                <option value="">Semua Produk</option>
                                @foreach ($MasterProduk as $produk)
                                    <option value="{{ $produk->id }}">{{ $produk->Nama }}</option>
                                @endforeach
                            </select>
                        </div>
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
                            {{-- Tambahan aksi jika perlu, misal export/excel --}}
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table datanew cell-border compact stripe" id="penjualanTable" width="100%">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Kode Transaksi</th>
                                    <th>Produk</th>
                                    <th>Tanggal</th>
                                    <th>Pelanggan</th>
                                    <th>Total Harga</th>
                                    <th>Tipe Pembayaran</th>
                                    <th>Durasi Pembayaran</th>
                                    <th>Sisa Bayar</th>
                                    <th>Status</th>
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
                $('#penjualanTable').DataTable({
                    responsive: true,
                    serverSide: true,
                    processing: true,
                    bDestroy: true,
                    ajax: {
                        url: "{{ route('laporan-penjualan.index') }}",
                        data: function(d) {
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
                            data: 'KodeTransaksi',
                            name: 'KodeTransaksi'
                        },
                        {
                            data: 'IdProduk',
                            name: 'IdProduk'
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
                            data: 'TotalHarga',
                            name: 'TotalHarga'
                        },
                        {
                            data: 'JenisTransaksi',
                            name: 'JenisTransaksi'
                        },
                        {
                            data: 'DurasiPembayaran',
                            name: 'DurasiPembayaran'
                        },
                        {
                            data: 'SisaBayar',
                            name: 'SisaBayar'
                        },
                        {
                            data: 'StatusPembayaran',
                            name: 'StatusPembayaran'
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
            // $('#filter_produk, #filter_tahun').on('change', function() { $('#penjualanTable').DataTable().ajax.reload(); });
        });
    </script>
@endpush
