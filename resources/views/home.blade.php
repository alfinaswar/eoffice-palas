@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="welcome d-lg-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center welcome-text">
                <h3 class="d-flex align-items-center">
                    <img src="assets/img/icons/hi.svg" alt="img">&nbsp;
                    Hi, {{ auth()->user()->name }}!
                </h3>
                &nbsp;
                <h6>Anda telah berhasil masuk ke dalam sistem E-Office Tanah Emas Indonesia.</h6>
            </div>
            <div class="d-flex align-items-center">
                <div class="position-relative me-3">
                    <span id="tanggal-hari-ini" class="fw-bold"></span>
                    <i data-feather="calendar" class="feather-14 ms-1"></i>
                </div>
                <div class="position-relative">
                    <span id="jam-hari-ini" class="fw-bold"></span>
                    <i data-feather="clock" class="feather-14 ms-1"></i>
                </div>
            </div>

        </div>

        <div class="row sales-cards">
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card d-flex align-items-center justify-content-between default-cover mb-4">
                    <div>
                        <h6>Uang Masuk</h6>
                        <h3 class="counters" data-count="2500000">
                            Rp 2.500.000
                        </h3>
                        <p class="sales-range">
                            <span class="text-success"><i data-feather="arrow-down-circle"
                                    class="feather-16"></i>&nbsp;</span>
                            Total uang masuk hari ini
                        </p>
                    </div>
                    <img src="assets/img/icons/cash-in.svg" alt="img">
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card color-info bg-danger mb-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h6>Uang Keluar</h6>
                        <h3 class="counters" data-count="1500000">
                            Rp 1.500.000
                        </h3>
                        <p class="sales-range">
                            <span class="text-danger"><i data-feather="arrow-up-circle" class="feather-16"></i>&nbsp;</span>
                            Total uang keluar hari ini
                        </p>
                    </div>
                    <img src="assets/img/icons/cash-out.svg" alt="img">
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card color-info bg-primary mb-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h6>Kas Hari Ini</h6>
                        <h3 class="counters" data-count="1000000">
                            Rp 1.000.000
                        </h3>
                        <p class="sales-range">
                            <span class="text-primary"><i data-feather="activity" class="feather-16"></i>&nbsp;</span>
                            Saldo kas tunai hari ini
                        </p>
                    </div>
                    <img src="assets/img/icons/cash-today.svg" alt="img">
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card color-info bg-secondary mb-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h6>Rekap Bulan Ini</h6>
                        <h3 class="counters" data-count="3600000">
                            Rp 3.600.000
                        </h3>
                        <p class="sales-range">
                            <span class="text-info"><i data-feather="calendar" class="feather-16"></i>&nbsp;</span>
                            Total saldo bulan berjalan
                        </p>
                    </div>
                    <img src="assets/img/icons/monthly-recap.svg" alt="img">
                </div>
            </div>
        </div>
        {{-- Chart --}}
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Grafik Uang Masuk </div>
                    </div>
                    <div class="card-body">
                        <div>
                            <canvas id="chartBar2" class="h-300"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Grafik Uang Keluar </div>
                    </div>
                    <div class="card-body">
                        <div>
                            <canvas id="chartBar2" class="h-300"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- end char --}}
        <div class="row">
            <div class="col-sm-12 col-md-12 col-xl-8 d-flex">
                <div class="card flex-fill default-cover w-100 mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Riwayat Transaksi Pemabayaran</h4>
                        <div class="dropdown">
                            <a href="" class="view-all d-flex align-items-center">
                                Lihat Semua Transaksi<span class="ps-2 d-flex align-items-center"><i
                                        data-feather="arrow-right" class="feather-16"></i></span>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless recent-transactions">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Kode Transaksi</th>
                                        <th>Tanggal</th>
                                        <th>Nama Produk</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($RiwayatTransaksi as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $item->KodeBayar }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->DibayarPada)->translatedFormat('d F Y H:i') }}
                                            </td>
                                            <td>{{ $item->transaksi->getProduk->Nama }}</td>
                                            <td><span class="badge bg-success">Lunas</span></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                <i data-feather="info" class="feather-16 me-2"></i>
                                                Tidak ada transaksi hari ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 d-flex">
                <div class="card flex-fill w-100 mb-4">
                    <div class="card-header">
                        <h5 class="card-title">Produk Terpopuler</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Produk</th>
                                        <th>Total Terjual</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @forelse ($ProdukTerpopuler as $no => $item)
                                        <tr>
                                            <td>{{ $no + 1 }}</td>
                                            <td>{{ $item->getProduk->Nama }}</td>
                                            <td>{{ $item->total_terjual }}</td>
                                        </tr>
                                    @empty
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
@endsection
@push('js')
    <!-- Chart JS -->
    <script src="{{ asset('') }}assets/plugins/chartjs/chart.min.js"></script>
    <script src="{{ asset('') }}assets/plugins/chartjs/chart-data.js"></script>
    <script>
        function updateTanggalJam() {
            const hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const bulan = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];
            const now = new Date();
            const tanggal = hari[now.getDay()] + ', ' + now.getDate() + ' ' + bulan[now.getMonth()] + ' ' + now
                .getFullYear();
            const jam = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            document.getElementById('tanggal-hari-ini').textContent = tanggal;
            document.getElementById('jam-hari-ini').textContent = jam;
        }
        updateTanggalJam();
        setInterval(updateTanggalJam, 1000);
    </script>
@endpush
