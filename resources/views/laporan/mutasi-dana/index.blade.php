@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Laporan Mutasi Dana</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Laporan Mutasi Dana</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Filter berdasarkan Bank --}}

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <h4 class="card-title">List Mutasi Dana</h4>
                    <p class="card-text">
                        Tabel ini berisi semua data mutasi dana
                    </p>
                </div>
                <div class="card-body">
                    <div class="row mb-3">

                        <div class="col-md-8">
                            <form id="filterMutasiForm" class="row g-2 align-items-end">
                                <div class="col">
                                    <label for="tahun" class="form-label">Pilih Tahun</label>
                                    <select id="tahun" name="tahun" class="select2">
                                        @php
                                            $tahunSekarang = date('Y');
                                        @endphp
                                        @for ($tahun = 2010; $tahun <= $tahunSekarang; $tahun++)
                                            <option value="{{ $tahun }}"
                                                {{ $tahun == $tahunSekarang ? 'selected' : '' }}>
                                                {{ $tahun }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="bulan" class="form-label">Pilih Bulan</label>
                                    <select id="bulan" name="bulan" class="select2">
                                        <option value="">-- Semua Bulan --</option>
                                        @php
                                            $bulanList = [
                                                1 => 'Januari',
                                                2 => 'Februari',
                                                3 => 'Maret',
                                                4 => 'April',
                                                5 => 'Mei',
                                                6 => 'Juni',
                                                7 => 'Juli',
                                                8 => 'Agustus',
                                                9 => 'September',
                                                10 => 'Oktober',
                                                11 => 'November',
                                                12 => 'Desember',
                                            ];
                                            $bulanSekarang = date('n');
                                        @endphp
                                        @foreach ($bulanList as $bln => $namaBulan)
                                            <option value="{{ $bln }}"
                                                {{ $bln == $bulanSekarang ? 'selected' : '' }}>
                                                {{ $namaBulan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="nama_bank" class="form-label">Nama Bank</label>
                                    <select id="nama_bank" name="nama_bank" class="select2">
                                        <option value="">-- Semua Bank --</option>
                                        @foreach ($Bank as $bank)
                                            <option value="{{ $bank->id }}">{{ $bank->Nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <button type="submit" class="btn btn-primary">Tampilkan</button>
                                </div>
                            </form>
                        </div>
                        <div class="col text-end">
                            <button class="btn btn-success" id="btnShowExportModal"
                                onclick="$('#modalExportOmset').modal('show')">
                                <i class="fa fa-file-excel"></i> Cetak Laporan
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table datanew cell-border compact stripe" id="mutasiDanaTable" width="100%">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Tanggal</th>
                                    <th>Jenis</th>
                                    <th>Kategori</th>
                                    <th>Deskripsi</th>
                                    <th>Nominal</th>
                                    <th>Nama Bank</th>
                                    <th>Ref Type</th>
                                    <th>Ref Id</th>
                                    <th>Saldo Setelah</th>
                                    <th>User Create</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('laporan.mutasi-dana.modal-mutasi')
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

            // Function to load DataTable with filter params
            function loadDataTable(filters = {}) {
                $('#mutasiDanaTable').DataTable({
                    responsive: true,
                    serverSide: true,
                    processing: true,
                    bDestroy: true,
                    ajax: {
                        url: "{{ route('laporan-mutasi.index') }}",
                        data: function(d) {
                            // Ambil data dari filter
                            d.tahun = $('#tahun').val();
                            d.bulan = $('#bulan').val();
                            d.nama_bank = $('#nama_bank').val();
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
                            name: 'Tanggal'
                        },
                        {
                            data: 'Jenis',
                            name: 'Jenis'
                        },
                        {
                            data: 'Kategori',
                            name: 'Kategori'
                        },
                        {
                            data: 'Deskripsi',
                            name: 'Deskripsi'
                        },
                        {
                            data: 'Nominal',
                            name: 'Nominal'
                        },
                        {
                            data: 'NamaBank',
                            name: 'NamaBank'
                        },
                        {
                            data: 'RefType',
                            name: 'RefType'
                        },
                        {
                            data: 'RefId',
                            name: 'RefId'
                        },
                        {
                            data: 'SaldoSetelah',
                            name: 'SaldoSetelah'
                        },
                        {
                            data: 'UserCreate',
                            name: 'UserCreate'
                        }
                    ],
                    createdRow: function(row, data, dataIndex) {
                        let jenis = '';

                        if (data.Jenis) {
                            jenis = String(data.Jenis).trim();
                        }

                        if (jenis === "IN") {
                            $(row).css('color', 'green');
                        } else if (jenis === "OUT") {
                            $(row).css('color', 'red');
                        }
                    }
                });
            }

            // Initial load
            loadDataTable();

            // Filter form submit handler
            $('#filterMutasiForm').on('submit', function(e) {
                e.preventDefault();
                $('#mutasiDanaTable').DataTable().destroy();
                loadDataTable();
            });

            // If select2 is used, you can trigger submit on change (optional)
            $('#tahun, #bulan, #nama_bank').on('change', function() {
                $('#filterMutasiForm').submit();
            });

        });
    </script>
@endpush
