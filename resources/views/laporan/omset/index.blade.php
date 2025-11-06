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
                    <div class="mb-3">
                        <div class="alert alert-secondary border border-secondary mb-0 p-3">
                            <div class="d-flex align-items-start">
                                <div class="me-2">
                                    <i class="feather-info flex-shrink-0"></i>
                                </div>
                                <div class="text-secondary w-100">
                                    <div class="fw-semibold d-flex justify-content-between">
                                        Informasi Omset
                                        <button type="button" class="btn-close p-0" data-bs-dismiss="alert"
                                            aria-label="Close"><i class="fas fa-xmark"></i></button>
                                    </div>
                                    <div class="fs-12 op-8 mb-1">
                                        Data omset pada tabel ini diambil dari data pembayaran pelanggan yang telah
                                        berstatus <b>Lunas</b>. Omset dihitung berdasarkan total pembayaran yang diterima
                                        perusahaan per bulan.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">

                        <div class="col-md-6">
                            <form id="filterOmsetForm" class="row g-2 align-items-end">
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
                        <table class="table datanew cell-border compact stripe" id="omsetTable" width="100%">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Bulan</th>
                                    <th>Total Omset</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Data dinamis (dari server/datatable ajax) --}}
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2" class="text-end">Total Omset:</th>
                                    <th id="totalOmset" class="text-end">Rp 0</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('laporan.omset.modal-cetak')
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
            var table = $('#omsetTable').DataTable({
                responsive: true,
                serverSide: true,
                processing: true,
                bDestroy: true,
                pageLength: 12, // Pagination is set to 12
                ajax: {
                    url: "{{ route('laporan-omset.index') }}",
                    data: function(d) {
                        d.tahun = $('#tahun').val(); // kirim parameter tahun
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
                        data: 'Bulan',
                        name: 'Bulan'
                    },
                    {
                        data: 'TotalOmset',
                        name: 'TotalOmset'
                    },
                ],
                drawCallback: function(settings) {
                    // Hitung total omset setelah data di-load
                    var api = this.api();
                    var total = 0;
                    var now = new Date();
                    var nowMonth = (now.getMonth() + 1).toString().padStart(2, '0');
                    var nowYear = now.getFullYear().toString();

                    // Ambil tahun filter yang sedang digunakan (jika ada)
                    var tahunFilter = $('#tahun').val();
                    if (!tahunFilter) {
                        tahunFilter = nowYear;
                    }

                    api.rows({
                        page: 'current'
                    }).every(function(rowIdx, tableLoop, rowLoop) {
                        var data = this.data();
                        // Ambil nilai TotalOmset, hilangkan "Rp", titik dan spasi, lalu parse ke integer
                        if (data.TotalOmset !== undefined) {
                            var num = data.TotalOmset.replace(/[^\d,]/g, '').replace(',', '.');
                            total += parseFloat(num) || 0;
                        }

                        // Highlight baris jika bulan dan tahun sama dengan saat ini
                        if (data.Bulan !== undefined) {
                            // Format "Januari 2024" dst dari server, ambil bulan & tahun
                            // Namun sebenarnya data.Bulan aslinya dikirim lewat kolom, dan view tampilkan yang sudah diformat.
                            // Kita bisa parsing dari data.Bulan, atau lebih aman: minta data.Bulan asli hidden (tapi sekarang manual parsing)
                            var text = $('<div>').html(data.Bulan).text()
                                .trim(); // safe: strip HTML
                            // Ambil nama bulan (berbahasa Indonesia) dan tahun
                            var parts = text.split(' ');
                            if (parts.length === 2) {
                                var namaBulan = parts[0];
                                var tahun = parts[1];

                                // mapping bulan Indonesia ke nomor
                                var bulanMap = {
                                    'Januari': '01',
                                    'Februari': '02',
                                    'Maret': '03',
                                    'April': '04',
                                    'Mei': '05',
                                    'Juni': '06',
                                    'Juli': '07',
                                    'Agustus': '08',
                                    'September': '09',
                                    'Oktober': '10',
                                    'November': '11',
                                    'Desember': '12'
                                };

                                if (bulanMap[namaBulan] && bulanMap[namaBulan] === nowMonth &&
                                    tahun === nowYear && tahun === tahunFilter) {
                                    $(this.node()).addClass('table-success');
                                } else {
                                    $(this.node()).removeClass('table-success');
                                }
                            }
                        }
                    });
                    // Format total ke rupiah format
                    function formatRupiah(angka) {
                        var number_string = angka.toString(),
                            sisa = number_string.length % 3,
                            rupiah = number_string.substr(0, sisa),
                            ribuan = number_string.substr(sisa).match(/\d{3}/g);
                        if (ribuan) {
                            var separator = sisa ? '.' : '';
                            rupiah += separator + ribuan.join('.');
                        }
                        return rupiah;
                    }
                    $('#totalOmset').html('Rp ' + formatRupiah(total));
                }
            });

            $('#filterOmsetForm').on('submit', function(e) {
                e.preventDefault();
                table.ajax.reload();
            });
        });
    </script>
@endpush
