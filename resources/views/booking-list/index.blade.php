@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Booking List</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Booking List</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col text-end">
            <button class="btn btn-primary" id="btnAmbilPengajuan" type="button">Ambil Data Pengajuan</button>
        </div>
    </div>

    <!-- Modal for Penawaran Harga Table -->
    <div class="modal fade" id="modalPenawaranHarga" tabindex="-1" aria-labelledby="modalPenawaranHargaLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content p-0 bg-transparent border-0">
                <div class="card mb-0">
                    <div class="card-header bg-dark d-flex align-items-center justify-content-between">
                        <h5 class="modal-title text-white mb-0" id="modalPenawaranHargaLabel">Pilih Penawaran Harga</h5>
                        <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="card-body">
                        <!-- Tambahkan teks header sesuai permintaan -->
                        <div class="table-responsive">
                            <table class="table datanew cell-border compact stripe" id="tablePenawaranModal" width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Nomor</th>
                                        <th>Tanggal</th>
                                        <th>Customer</th>
                                        <th>Total Penawaran</th>
                                        <th width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($penawaran as $key => $p)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $p->Nomor }}</td>
                                            <td>{{ $p->Tanggal ? \Carbon\Carbon::parse($p->Tanggal)->translatedFormat('d F Y') : ($p->Tanngal ? \Carbon\Carbon::parse($p->Tanngal)->translatedFormat('d F Y') : '-') }}
                                            </td>
                                            <td>{{ $p->NamaPelanggan }}</td>
                                            <td>Rp {{ number_format($p->Total, 0, ',', '.') }}</td>
                                            <td>
                                                <a href="{{route('booking-list.create', encrypt($p->id))}}"
                                                    class="btn btn-success btn-sm pilih-penawaran"
                                                    data-id="{{ encrypt($p->id) }}">
                                                    Pilih
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <h4 class="card-title">List Booking</h4>
                    <p class="card-text">
                        Tabel ini berisi semua data booking yang ada.
                    </p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table datanew cell-border compact stripe" id="bookingListTable" width="100%">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nomor</th>
                                    <th>Id Produk</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Jenis Pembayaran</th>
                                    <th>Keterangan</th>
                                    <th>Penerima</th>
                                    <th>Diterima Pada</th>
                                    <th>Penyetor</th>
                                    <th width="15%">Aksi</th>
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
        $(document).ready(function () {
            // Fix the modal not showing issue
            $('#btnAmbilPengajuan').on('click', function () {
                // Ensure bootstrap modal JS is loaded
                var modal = $('#modalPenawaranHarga');
                // Check if modal exists to avoid js error
                if (modal.length) {
                    modal.modal('show');
                }
            });

            $('body').on('click', '.btn-delete', function () {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Hapus Data?',
                    text: "Apakah Anda yakin ingin menghapus data booking ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('booking-list.destroy', ':id') }}'.replace(':id',
                                id),
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                if (response.status === 200) {
                                    Swal.fire('Dihapus!', response.message, 'success');
                                    $('#bookingListTable').DataTable().ajax.reload();
                                } else {
                                    Swal.fire('Gagal!', response.message, 'error');
                                }
                            },
                            error: function (xhr) {
                                Swal.fire('Gagal!', xhr.responseJSON?.message ??
                                    'Terjadi kesalahan saat menghapus.', 'error');
                            }
                        });
                    }
                });
            });

            function loadDataTable() {
                $('#bookingListTable').DataTable({
                    responsive: true,
                    serverSide: true,
                    processing: true,
                    bDestroy: true,
                    ajax: {
                        url: "{{ route('booking-list.index') }}",
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
                        data: 'Nomor',
                        name: 'Nomor'
                    },
                    {
                        data: 'IdProduk',
                        name: 'IdProduk'
                    },
                    {
                        data: 'NamaPelanggan',
                        name: 'NamaPelanggan'
                    },
                    {
                        data: 'Tanggal',
                        name: 'Tanggal'
                    },
                    {
                        data: 'Total',
                        name: 'Total'
                    },
                    {
                        data: 'JenisPembayaran',
                        name: 'JenisPembayaran'
                    },
                    {
                        data: 'Keterangan',
                        name: 'Keterangan'
                    },
                    {
                        data: 'Penerima',
                        name: 'Penerima'
                    },
                    {
                        data: 'DiterimaPada',
                        name: 'DiterimaPada'
                    },
                    {
                        data: 'Penyetor',
                        name: 'Penyetor'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                    ]
                });
            }

            loadDataTable();
        });
    </script>
@endpush