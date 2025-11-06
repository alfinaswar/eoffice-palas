@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Data Transaksi</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Transaksi</li>
                </ul>
            </div>

        </div>
    </div>
    <div class="row mb-3">
        <div class="col text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAmbilBooking">
                Ambil Data Booking
            </button>
        </div>
    </div>

    <!-- Modal Ambil Data Booking -->
    <div class="modal fade" id="modalAmbilBooking" tabindex="-1" aria-labelledby="modalAmbilBookingLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <!-- Modal tengah horizontal dan vertikal -->
            <div class="modal-content" style="margin: auto;">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title w-100 text-center" id="modalAmbilBookingLabel">Ambil Data Booking</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table datanew cell-border compact stripe" id="bookingTable">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nomor Booking</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Produk</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Penyetor</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @foreach($booking as $item)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $item->Nomor ?? '-' }}</td>
                                        <td>{{ $item->NamaPelanggan ?? '-' }}</td>
                                        <td>
                                            @if(isset($item->getProduk))
                                                {{ $item->getProduk->Nama }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $item->Tanggal ? \Carbon\Carbon::parse($item->Tanggal)->format('d-m-Y') : '-' }}
                                        </td>
                                        <td class="text-end">Rp{{ number_format($item->Total ?? 0, 0, ',', '.') }}</td>
                                        <td>{{ $item->Penyetor ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('transaksi.create', encrypt($item->id)) }}"
                                                class="btn btn-sm btn-success">
                                                Ambil
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <script>
                        $(function () {
                            $('#bookingTable').DataTable({
                                ordering: true,
                                pageLength: 5,
                                lengthChange: false,
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <h4 class="card-title">Daftar Pelanggan</h4>
                    <p class="card-text">
                        Tabel ini berisi semua transaksi pelanggan.
                    </p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table datanew cell-border compact stripe" id="usersTable" width="100%">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Dibuat Pada</th>
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
            function loadDataTable() {
                $('#usersTable').DataTable({
                    responsive: true,
                    serverSide: true,
                    processing: true,
                    bDestroy: true,
                    ajax: {
                        url: "{{ route('transaksi.index') }}",
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
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
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