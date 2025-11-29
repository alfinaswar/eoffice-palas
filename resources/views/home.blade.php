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
                        <h3 class="counters">
                            Rp {{ number_format($totalUangMasukHariIni, 0, ',', '.') }}
                        </h3>
                        <p class="sales-range">
                            <span class="text-success"><i data-feather="arrow-down-circle"
                                    class="feather-16"></i>&nbsp;</span>
                            Total uang masuk hari ini
                        </p>
                    </div>
                    {{-- <img src="assets/img/icons/cash-in.svg" alt="img"> --}}
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card color-info bg-danger mb-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-white">Uang Keluar</h6>
                        <h3 class="counters">
                            Rp {{ number_format($totalUangKeluarHariIni, 0, ',', '.') }}
                        </h3>
                        <p class="sales-range">
                            <span class="text-danger"><i data-feather="arrow-up-circle" class="feather-16"></i>&nbsp;</span>
                            Total uang keluar hari ini
                        </p>
                    </div>
                    {{-- <img src="assets/img/icons/cash-out.svg" alt="img"> --}}
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card color-info bg-primary mb-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-white">Kas Hari Ini</h6>
                        <h3 class="counters">
                            Rp {{ number_format($totalUangMasukHariIni, 0, ',', '.') }}
                        </h3>
                        <p class="sales-range">
                            <span class="text-primary"><i data-feather="activity" class="feather-16"></i>&nbsp;</span>
                            Saldo kas tunai hari ini
                        </p>
                    </div>
                    {{-- <img src="assets/img/icons/cash-today.svg" alt="img"> --}}
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card color-info bg-secondary mb-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h6>Rekap Bulan Ini</h6>
                        <h3 class="counters">
                            Rp {{ number_format($totalUangMasukBulanIni, 0, ',', '.') }}
                        </h3>
                        <p class="sales-range">
                            <span class="text-info"><i data-feather="calendar" class="feather-16"></i>&nbsp;</span>
                            Total saldo bulan berjalan
                        </p>
                    </div>
                    {{-- <img src="assets/img/icons/monthly-recap.svg" alt="img"> --}}
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
                        <div class="card-title">Grafik Uang Keluar</div>
                    </div>
                    <div class="card-body">
                        <div>
                            <canvas id="chartBarKeluar" class="h-300"></canvas>
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
    <script>
        var ctx2 = document.getElementById('chartBar2').getContext('2d');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: @json($chartBar2Labels),
                datasets: [{
                    label: 'Uang Masuk',
                    data: @json($chartBar2Data),
                    backgroundColor: '#44c4fa'
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: {
                                size: 10
                            }
                        }
                    },
                    x: {
                        barPercentage: 0.6,
                        ticks: {
                            beginAtZero: true,
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });

        // Chart untuk Uang Keluar
        var ctxKeluar = document.getElementById('chartBarKeluar').getContext('2d');
        new Chart(ctxKeluar, {
            type: 'bar',
            data: {
                labels: @json(isset($chartBarKeluarLabels) ? $chartBarKeluarLabels : $chartBar2Labels),
                datasets: [{
                    label: 'Uang Keluar',
                    data: @json(isset($chartBarKeluarData) ? $chartBarKeluarData : []),
                    backgroundColor: '#fa446b'
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: {
                                size: 10
                            }
                        }
                    },
                    x: {
                        barPercentage: 0.6,
                        ticks: {
                            beginAtZero: true,
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });
    </script>
@endpush
