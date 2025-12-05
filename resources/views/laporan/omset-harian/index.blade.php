@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Laporan Omset Harian</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Laporan Omset Harian</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <h4 class="card-title">Tabel Omset Harian</h4>
                    <p class="card-text">
                        Tabel ini berisi data omset harian perusahaan.
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
                                        Informasi Omset Harian
                                        <button type="button" class="btn-close p-0" data-bs-dismiss="alert"
                                            aria-label="Close"><i class="fas fa-xmark"></i></button>
                                    </div>
                                    <div class="fs-12 op-8 mb-1">
                                        Data omset pada tabel ini diambil dari pembayaran pelanggan yang telah <b>Lunas</b>,
                                        yaitu dari <b>Booking Fee</b> dan <b>Down Payment</b>.
                                        Omset harian dihitung berdasarkan total pembayaran yang diterima per tanggal.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Filter Proyek -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form id="filterProyekForm" class="form-inline">
                                <div class="form-group">
                                    <label for="proyekFilter" class="me-2">Filter Proyek:</label>
                                    <select id="proyekFilter" name="id_proyek" class="form-control select2"
                                        style="min-width:190px;">
                                        <option value="">Semua Proyek</option>
                                        @foreach ($proyek as $p)
                                            <option value="{{ $p->id }}">{{ $p->NamaProyek }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6 text-end">
                            <button class="btn btn-success" id="btnShowExportModal"
                                onclick="$('#modalExportOmset').modal('show')">
                                <i class="fa fa-file-excel"></i> Cetak Laporan
                            </button>
                        </div>
                    </div>
                    <!-- End Filter Proyek -->
                    <div class="table-responsive">
                        <table class="table datanew cell-border compact stripe" id="omsetHarianTable" width="100%">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Tanggal</th>
                                    <th>Total Omset</th>
                                    <th>Total Pengeluaran</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th class="text-end">Total Omset:</th>
                                    <th id="totalOmsetHarian" class="text-end">Rp 0</th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th class="text-end">Total Pengeluaran:</th>
                                    <th id="totalKeluarHarian" class="text-end">Rp 0</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('laporan.omset-harian.modal-cetak')
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
            // Inisialisasi select2 untuk filter proyek
            $('#proyekFilter').select2();

            var table = $('#omsetHarianTable').DataTable({
                responsive: true,
                serverSide: true,
                processing: true,
                bDestroy: true,
                pageLength: 31,
                ajax: {
                    url: "{{ route('laporan-omset-harian.index') }}",
                    data: function(d) {
                        d.id_proyek = $('#proyekFilter').val();
                    },
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
                        data: 'TotalOmset',
                        name: 'TotalOmset',
                        className: 'text-end'
                    },
                    {
                        data: 'TotalKeluar',
                        name: 'TotalKeluar',
                        className: 'text-end'
                    },
                ],
                drawCallback: function(settings) {
                    var api = this.api();
                    var totalOmset = 0;
                    var totalKeluar = 0;
                    var now = new Date();
                    var nowDate = now.toISOString().slice(0, 10);

                    // Hapus highlight sebelumnya
                    $('#omsetHarianTable tbody tr').removeClass('table-success').removeClass(
                        'table-hari-ini');

                    api.rows({
                        page: 'current'
                    }).every(function(rowIdx, tableLoop, rowLoop) {
                        var data = this.data();

                        // Ambil nilai TotalOmset
                        if (data.TotalOmset !== undefined) {
                            var num = data.TotalOmset.replace(/[^\d,]/g, '').replace(',', '.');
                            totalOmset += parseFloat(num) || 0;
                        }
                        // Ambil nilai TotalKeluar
                        if (data.TotalKeluar !== undefined) {
                            var numKeluar = data.TotalKeluar.replace(/[^\d,]/g, '').replace(',',
                                '.');
                            totalKeluar += parseFloat(numKeluar) || 0;
                        }

                        if (data.Tanggal !== undefined) {
                            // Tanggal pada data kemungkinan dalam format "1 Januari 2024" atau mirip
                            // atau, idealnya, hidden field/attr untuk raw tanggal (YYYY-MM-DD)
                            // Agar yakin, cek apakah data asli tersedia dalam atribut render/hidden, ambil dari data asli backend
                            var tanggalAsli = '';

                            // Cek jika table dimunculkan dengan tanggal yg bisa dibandingkan
                            // Cara robust: Simpan hidden field di kolom, atau ambil tanggal asli dari raw (per cek).
                            // Karena pada backend: "Tanggal" dikonversi jadi "01 Januari 2024". Jadi kita perlu kembali ke format date.
                            // Solusi mudah: substring tahun dan bulan/harinya dari display, reconstruct ke YYYY-MM-DD lalu bandingkan
                            // Misal: "20 Juni 2024"
                            var text = $('<div>').html(data.Tanggal).text().trim();
                            // Convert text -> date string (YYYY-MM-DD) for comparison.
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
                            var match = text.match(/^(\d{1,2}) ([A-Za-z]+) (\d{4})$/);
                            if (match) {
                                var dd = match[1].padStart(2, '0');
                                var mm = bulanMap[match[2]] ? bulanMap[match[2]] : '01';
                                var yyyy = match[3];
                                tanggalAsli = yyyy + '-' + mm + '-' + dd;
                            } else {
                                tanggalAsli = text;
                            }

                            if (tanggalAsli == nowDate) {
                                $(this.node()).addClass('table-success table-hari-ini');
                                $(this.node()).css({
                                    'outline': '2px solid #1ecd52',
                                    'box-shadow': '0 0 4px #1ecd52'
                                });
                            } else {
                                $(this.node()).removeClass('table-success table-hari-ini');
                                $(this.node()).css({
                                    'outline': '',
                                    'box-shadow': ''
                                });
                            }
                        }
                    });

                    function formatRupiah(angka) {
                        var number_string = angka.toString().split(',');
                        var sisa = number_string[0].length % 3;
                        var rupiah = number_string[0].substr(0, sisa);
                        var ribuan = number_string[0].substr(sisa).match(/\d{3}/g);
                        if (ribuan) {
                            var separator = sisa ? '.' : '';
                            rupiah += separator + ribuan.join('.');
                        }
                        return rupiah;
                    }
                    $('#totalOmsetHarian').html('Rp ' + formatRupiah(totalOmset));
                    $('#totalKeluarHarian').html('Rp ' + formatRupiah(totalKeluar));
                }
            });

            // trigger reload jika filter proyek berubah
            $('#proyekFilter').on('change', function() {
                table.ajax.reload();
            });

            table.on('draw', function() {
                var $hariIni = $('#omsetHarianTable tbody tr.table-hari-ini');
                if ($hariIni.length > 0) {
                    $('html,body').animate({
                        scrollTop: $hariIni.offset().top - 200
                    }, 350);
                }
            });
        });
    </script>
@endpush
