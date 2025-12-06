@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Progres Pengurusan Tanah</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Progres Pengurusan Tanah</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col text-end">
            <a class="btn btn-primary" href="{{ route('pengurusan-tanah.create') }}">Tambah Pengurusan Tanah Baru</a>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <h4 class="card-title">List Progres Pengurusan Tanah</h4>
                    <p class="card-text">
                        Tabel ini berisi semua data progres pengurusan surat tanah yang ada.
                    </p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table datanew cell-border compact stripe" id="pengurusanTanahTable" width="100%">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Proyek</th>
                                    <th>Tanggal</th>
                                    <th>Deskripsi</th>
                                    <th>Legal</th>
                                    <th>Keterangan</th>
                                    <th>Dibuat Oleh</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-end">Total Akumulasi Biaya Legal:</th>
                                    <th id="totalLegal" class="text-end"></th>
                                    <th colspan="3"></th>
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
            if (isNaN(angka)) return 'Rp 0';
            return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        $(document).ready(function() {
            $('body').on('click', '.btn-delete', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Hapus Data?',
                    text: "Apakah Anda yakin ingin menghapus progres pengurusan tanah ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('pengurusan-tanah.destroy', ':id') }}'.replace(
                                ':id', id),
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.status === 200) {
                                    Swal.fire('Dihapus!', response.message, 'success');
                                    $('#pengurusanTanahTable').DataTable().ajax
                                        .reload();
                                } else {
                                    Swal.fire('Gagal!', response.message, 'error');
                                }
                            },
                            error: function(xhr) {
                                Swal.fire('Gagal!', xhr.responseJSON?.message ??
                                    'Terjadi kesalahan saat menghapus.', 'error');
                            }
                        });
                    }
                });
            });

            function loadDataTable() {
                $('#pengurusanTanahTable').DataTable({
                    responsive: true,
                    serverSide: true,
                    processing: true,
                    bDestroy: true,
                    ajax: {
                        url: "{{ route('pengurusan-tanah.index') }}",
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
                            name: 'KodeProyek'
                        },

                        {
                            data: 'Tanggal',
                            name: 'Tanggal'
                        },
                        {
                            data: 'Deskripsi',
                            name: 'Deskripsi'
                        },
                        {
                            data: 'Legal',
                            name: 'Legal'
                        },
                        {
                            data: 'Keterangan',
                            name: 'Keterangan'
                        },
                        {
                            data: 'UserCreated',
                            name: 'UserCreated'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ],
                    drawCallback: function(settings) {
                        var api = this.api();
                        var total = 0;
                        api.rows({
                            search: 'applied'
                        }).every(function(rowIdx, tableLoop, rowLoop) {
                            var data = this.data();
                            var legal = data.Legal;
                            // Ambil angka saja dari Legal, asumsikan dipisahkan dengan angka/koma
                            if (legal) {
                                // Jika user mengisi nilai rupiah/angka saja tanpa format
                                var num = legal.toString().replace(/[^0-9.,]/g, '').replace(
                                    /,/g, '.');
                                num = parseFloat(num) || 0;
                                total += num;
                            }
                        });
                        $('#totalLegal').html(formatRupiah(Math.round(total)));
                    }
                });
            }

            loadDataTable();
        });
    </script>
@endpush
