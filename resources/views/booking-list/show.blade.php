@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Batalkan Pesanan</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('booking-list.index') }}">Booking List</a></li>
                    <li class="breadcrumb-item active">Batalkan Pesanan</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        Informasi Transaksi / Pembayaran
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <h5 class="mb-2">
                            <i class="fas fa-info-circle"></i> <b>Tentang Fitur Batalkan Pesanan</b>
                        </h5>

                        <ul class="mb-0 ps-3 mt-2" style="line-height: 1.8;">
                            <li>
                                Setelah dibatalkan, status pesanan berubah menjadi <b>Cancel</b>.
                                <br>
                                Status <b>tidak dapat dikembalikan</b> ke sebelumnya.</span>
                            </li>
                            <li>
                                Semua transaksi pembayaran yang berkaitan dianggap <b>tidak aktif</b>.
                            </li>
                            <li>
                                <span class="fw-bold">Catatan:</span>
                                Jika sudah ada pembayaran pada pesanan ini, sistem secara otomatis akan mencatat
                                pengembalian dana ke transaksi keluar saat pesanan dibatalkan.
                            </li>
                            <li>
                                <b>Pastikan</b> keputusan Anda <u>sudah final</u>, karena <b>tindakan ini tidak dapat
                                    dibatalkan</b>.
                            </li>
                        </ul>
                    </div>
                    <div class="card mb-3">

                        <div class="card-body">
                            @php
                                $customer = optional($bookingList->getPenawaran->getCustomer ?? null);
                            @endphp
                            <div class="row">
                                <div class="col-xl-4 col-lg-12 col-md-4 d-flex">
                                    {{-- <div class="card w-100 shadow-sm"> --}}
                                    <div class="card-body">
                                        <h5 class="mb-4">Informasi Pelanggan</h5>
                                        <table class="table table-borderless mb-0">
                                            <tbody>
                                                <tr>
                                                    <th scope="row" class="py-1 px-0">Nama</th>
                                                    <td class="py-1 px-0">{{ $customer->name ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="py-1 px-0">Email</th>
                                                    <td class="py-1 px-0">{{ $customer->email ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="py-1 px-0">No. Telepon</th>
                                                    <td class="py-1 px-0">
                                                        @if (!empty($customer->nohp))
                                                            @php
                                                                $wa_hp = preg_replace(
                                                                    '/^0/',
                                                                    '62',
                                                                    preg_replace('/[^0-9]/', '', $customer->no_hp),
                                                                );
                                                            @endphp
                                                            <a href="https://wa.me/{{ $wa_hp }}" target="_blank"
                                                                style="text-decoration:underline;">
                                                                {{ $customer->nohp }}
                                                                <span class="ms-1" style="color:#25D366;">
                                                                    <i class="fab fa-whatsapp"></i>
                                                                </span>
                                                            </a>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="py-1 px-0">NIK</th>
                                                    <td class="py-1 px-0">{{ $customer->nik ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="py-1 px-0">Alamat</th>
                                                    <td class="py-1 px-0">{{ $customer->alamat ?? '-' }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    {{-- </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <ul class="nav nav-pills mb-3 nav-justified tab-style-5 d-sm-flex d-block" id="pills-tab" role="tablist"
                        style="background-color: #f1f1f1; border-radius: 10px;">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                                aria-selected="true">Data Booking</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile"
                                aria-selected="false">Data Down Payment (DP)</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact"
                                aria-selected="false">Transaksi / Pembayaran</button>
                        </li>

                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane show active text-muted" id="pills-home" role="tabpanel"
                            aria-labelledby="pills-home-tab" tabindex="0">
                            <div class="col-lg-12">

                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label for="NomorPenawaran" class="form-label"><strong>Nomor
                                                Penawaran</strong></label>
                                        <input type="text" class="form-control" id="NomorPenawaran"
                                            value="{{ $bookingList->getPenawaran->Nomor ?? '-' }}" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="Tanggal" class="form-label"><strong>Tanggal</strong></label>
                                        <input type="date" class="form-control" id="Tanggal"
                                            value="{{ $bookingList->Tanggal }}" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"><strong>Jenis Pembayaran</strong></label>
                                        <input type="text" class="form-control"
                                            value="{{ $bookingList->JenisPembayaran }}" readonly>
                                    </div>

                                    <div class="col-md-6" id="bankPilihanContainer" style="display: none;">
                                        <label class="form-label"><strong>Pilih Bank</strong></label>
                                        @if ($bookingList->NamaBank)
                                            @php
                                                $selectedBank = collect($bank ?? [])->firstWhere(
                                                    'id',
                                                    $bookingList->NamaBank,
                                                );
                                            @endphp
                                            <input type="text" class="form-control"
                                                value="{{ $selectedBank ? $selectedBank->Nama . ' - ' . $selectedBank->Rekening : '-' }}"
                                                readonly>
                                        @else
                                            <input type="text" class="form-control" value="-" readonly>
                                        @endif
                                    </div>

                                    <div class="col-md-6" id="dariBankContainer" style="display: none;">
                                        <label class="form-label"><strong>Dari Bank</strong></label>
                                        @if ($bookingList->DariBank)
                                            @php
                                                $dariBank = collect($bank ?? [])->firstWhere(
                                                    'id',
                                                    $bookingList->DariBank,
                                                );
                                            @endphp
                                            <input type="text" class="form-control"
                                                value="{{ $dariBank ? $dariBank->Nama : '-' }}" readonly>
                                        @else
                                            <input type="text" class="form-control" value="-" readonly>
                                        @endif
                                    </div>

                                    <div class="col-md-6" id="noRekeningContainer" style="display: none;">
                                        <label class="form-label"><strong>No. Rekening Pengirim</strong></label>
                                        <input type="text" class="form-control"
                                            value="{{ $bookingList->NoRekening ?? '-' }}" readonly>
                                    </div>

                                    <div class="col-md-12" id="buktiTfContainer" style="display: none;">
                                        <label class="form-label"><strong>Bukti Transfer</strong></label>
                                        <div style="margin-top: 5px;">
                                            @if ($bookingList->Bukti)
                                                <a href="{{ asset('uploads/bukti-tf/' . $bookingList->Bukti) }}"
                                                    target="_blank">Lihat file</a>
                                            @else
                                                Tidak ada bukti transfer.
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label"><strong>Penerima</strong></label>
                                        <input type="text" class="form-control"
                                            value="{{ $bookingList->getKaryawan->name ?? '-' }}" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"><strong>Penyetor</strong></label>
                                        <input type="text" class="form-control"
                                            value="{{ $bookingList->Penyetor ?? '-' }}" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"><strong>Total Setoran</strong></label>
                                        <input type="text" class="form-control"
                                            value="{{ 'Rp ' . number_format($bookingList->Total ?? 0, 0, ',', '.') }}"
                                            readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"><strong>Sisa Bayar</strong></label>
                                        <input type="text" class="form-control"
                                            value="{{ 'Rp ' . number_format($bookingList->SisaBayar ?? 0, 0, ',', '.') }}"
                                            readonly>
                                    </div>

                                    <div class="col-md-12 mt-4">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label class="form-label"><strong>Nomor Penawaran</strong></label>
                                                <input type="text" class="form-control"
                                                    value="{{ $bookingList->getPenawaran->Nomor ?? '-' }}" readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label"><strong>Tanggal</strong></label>
                                                <input type="text" class="form-control"
                                                    value="{{ $bookingList->getPenawaran && $bookingList->getPenawaran->Tanggal ? \Carbon\Carbon::parse($bookingList->getPenawaran->Tanggal)->translatedFormat('d F Y') : '-' }}"
                                                    readonly>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label"><strong>Nama Pelanggan</strong></label>
                                                <input type="text" class="form-control"
                                                    value="{{ optional($bookingList->getPenawaran->getCustomer ?? null)->name ?? '-' }}"
                                                    readonly>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label"><strong>Nama Produk</strong></label>
                                                <input type="text" class="form-control"
                                                    value="{{ $bookingList->getPenawaran->DetailPenawaran[0]->getProduk->Nama ?? '-' }}"
                                                    readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label"><strong>Harga</strong></label>
                                                <input type="text" class="form-control"
                                                    value="Rp {{ number_format($bookingList->getPenawaran->Total ?? 0, 0, ',', '.') }}"
                                                    readonly>
                                            </div>
                                        </div>
                                        <div class="mt-2 mb-2">
                                            <strong>Terbilang:</strong>
                                            {{ ucfirst(terbilang($bookingList->getPenawaran->Total ?? 0)) }}
                                            rupiah
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 mt-3">
                                    <label class="form-label"><strong>Keterangan</strong></label>
                                    <textarea class="form-control" rows="3" readonly>{{ $bookingList->Keterangan }}</textarea>
                                </div>



                            </div>
                        </div>
                        <div class="tab-pane text-muted" id="pills-profile" role="tabpanel"
                            aria-labelledby="pills-profile-tab" tabindex="0">
                            @if ($bookingList->getDp)
                                <div class="row mb-2">
                                    <div class="col-md-4">
                                        <label class="form-label"><strong>Nomor DP</strong></label>
                                        <input type="text" class="form-control"
                                            value="{{ $bookingList->getDp->Nomor ?? '-' }}" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label"><strong>Tanggal</strong></label>
                                        <input type="text" class="form-control"
                                            value="{{ $bookingList->getDp->Tanggal ? \Carbon\Carbon::parse($bookingList->getDp->Tanggal)->translatedFormat('d F Y') : '-' }}"
                                            readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label"><strong>Nominal DP</strong></label>
                                        <input type="text" class="form-control"
                                            value="Rp {{ number_format($bookingList->getDp->Total ?? 0, 0, ',', '.') }}"
                                            readonly>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-4">
                                        <label class="form-label"><strong>Jenis Pembayaran</strong></label>
                                        <input type="text" class="form-control"
                                            value="{{ $bookingList->getDp->JenisPembayaran ?? '-' }}" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label"><strong>Nama Bank</strong></label>
                                        <input type="text" class="form-control"
                                            value="{{ $bookingList->getDp->getBank->Nama ?? '-' }}" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label"><strong>Penerima</strong></label>
                                        <input type="text" class="form-control"
                                            value="{{ optional($bookingList->getDp->getPenerima ?? null)->name ?? '-' }}"
                                            readonly>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-4">
                                        <label class="form-label"><strong>Penyetor</strong></label>
                                        <input type="text" class="form-control"
                                            value="{{ $bookingList->getDp->Penyetor ?? '-' }}" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label"><strong>Sisa Bayar</strong></label>
                                        <input type="text" class="form-control"
                                            value="Rp {{ number_format($bookingList->getDp->SisaBayar ?? 0, 0, ',', '.') }}"
                                            readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label"><strong>Bukti Transfer</strong></label>
                                        @if ($bookingList->getDp->Bukti)
                                            <br>
                                            <a href="{{ asset('storage/' . $bookingList->getDp->Bukti) }}"
                                                target="_blank" class="btn btn-info btn-sm">Lihat Bukti</a>
                                        @else
                                            <input type="text" class="form-control" value="-" readonly>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-12">
                                        <label class="form-label"><strong>Keterangan</strong></label>
                                        <textarea class="form-control" rows="2" readonly>{{ $bookingList->getDp->Keterangan ?? '-' }}</textarea>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-warning my-2">
                                    Data Down Payment belum diinputkan.
                                </div>
                            @endif
                        </div>
                        <div class="tab-pane text-muted" id="pills-contact" role="tabpanel"
                            aria-labelledby="pills-contact-tab" tabindex="0">
                            @php
                                $transaksi = $bookingList->getTransaksiHeader;
                                // dd($transaksi);
                            @endphp

                            @if ($transaksi)
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <strong>Transaksi</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-2">
                                            <div class="col-md-4">
                                                <label class="form-label"><strong>No Transaksi</strong></label>
                                                <input type="text" class="form-control"
                                                    value="{{ $transaksi->KodeTransaksi ?? '-' }}" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label"><strong>Tanggal Transaksi</strong></label>
                                                <input type="text" class="form-control"
                                                    value="{{ $transaksi->TanggalTransaksi ?? '-' }}" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label"><strong>Total Transaksi</strong></label>
                                                <input type="text" class="form-control"
                                                    value="Rp {{ number_format($transaksi->TotalHarga ?? 0, 0, ',', '.') }}"
                                                    readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-4">
                                                <label class="form-label"><strong>Sisa Pembayaran</strong></label>
                                                <input type="text" class="form-control"
                                                    value="{{ $transaksi->SisaBayar ?? '-' }}" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label"><strong>Status</strong></label>
                                                <input type="text" class="form-control"
                                                    value="{{ ($transaksi->StatusPembayaran ?? '-') === 'BelumLunas' ? 'Belum Lunas' : $transaksi->StatusPembayaran ?? '-' }}"
                                                    readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label"><strong>Keterangan</strong></label>
                                                <input type="text" class="form-control"
                                                    value="{{ $transaksi->Keterangan ?? '-' }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mb-3">
                                    <div class="card-header">
                                        <strong>Detail Angsuran</strong>
                                    </div>
                                    <div class="card-body">

                                        @if ($transaksi->getTransaksi && count($transaksi->getTransaksi))
                                            <div class="table-responsive">
                                                <table class="table datanew cell-border compact stripe" id="produkTable"
                                                    width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Kode Bayar</th>
                                                            <th>Tanggal Bayar</th>
                                                            <th>Jumlah Angsuran</th>
                                                            <th>Lunas ?</th>
                                                            <th>Keterangan</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($transaksi->getTransaksi as $i => $detail)
                                                            <tr>
                                                                <td>{{ $i + 1 }}</td>
                                                                <td>{{ $detail->KodeBayar ?? '-' }}</td>
                                                                <td>{{ $detail->DibayarPada ?? '-' }}</td>
                                                                <td>Rp
                                                                    {{ number_format($detail->BesarCicilan ?? 0, 0, ',', '.') }}
                                                                </td>
                                                                <td>{{ $detail->Status ?? '-' }}</td>
                                                                <td>{{ $detail->Keterangan ?? '-' }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <div class="alert alert-warning d-flex justify-content-center align-items-center"
                                                style="min-height: 150px;">
                                                <h1 class="display-5 text-center w-100" style="font-size: 2.5rem;">
                                                    Belum
                                                    ada detail angsuran.</h1>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-warning d-flex justify-content-center align-items-center"
                                    style="min-height: 150px;">
                                    <h1 class="display-6 text-center w-100" style="font-size: 2rem;">Pelanggan belum
                                        melakukan pembayaran.</h1>
                                </div>
                            @endif
                        </div>

                    </div>
                    <div class="card-footer d-flex justify-content-end align-items-center">
                        <a href="{{ route('booking-list.index') }}" class="btn btn-secondary me-2">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                            data-bs-target="#cancelOrderModal">
                            <i class="fas fa-times-circle"></i> Batalkan Pesanan
                        </button>

                        <!-- Modal Batalkan Pesanan -->
                        <div class="modal fade" id="cancelOrderModal" tabindex="-1"
                            aria-labelledby="cancelOrderModalLabel" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog modal-lg" style="margin-top: 40px;">
                                <div class="modal-content">
                                    <form method="POST"
                                        action="{{ route('booking-list.cancel', encrypt($bookingList->id)) }}">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="cancelOrderModalLabel">Konfirmasi Pembatalan
                                                Pesanan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Tutup"></button>
                                        </div>
                                        <input type="hidden" name="IdBooking" value="{{ $bookingList->id }}">
                                        <div class="modal-body">
                                            <div class="alert alert-warning">
                                                <strong>Perhatian!</strong> Tindakan ini akan membatalkan pesanan dan tidak
                                                dapat dikembalikan.
                                            </div>
                                            <div class="mb-2">
                                                <label for="KodeBooking" class="form-label"><strong>Kode
                                                        Booking:</strong></label>
                                                <input type="text" id="KodeBooking" class="form-control"
                                                    name="KodeBooking" value="{{ $bookingList->Nomor ?? '-' }}" readonly>
                                            </div>

                                            <div class="mb-3">
                                                <label for="AlasanCancel" class="form-label">Alasan Pembatalan
                                                    (opsional)</label>
                                                <textarea class="form-control" id="AlasanCancel" name="AlasanCancel" rows="3"
                                                    placeholder="Masukkan alasan pembatalan..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-times-circle"></i> Batalkan Pesanan
                                            </button>
                                        </div>
                                    </form>
                                </div>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const jenisPembayaran = document.getElementById('JenisPembayaran');
            const bankPilihanContainer = document.getElementById('bankPilihanContainer');
            const dariBankContainer = document.getElementById('dariBankContainer');
            const noRekeningContainer = document.getElementById('noRekeningContainer');
            const buktiTfContainer = document.getElementById('buktiTfContainer');

            function toggleTransferFields() {
                if (jenisPembayaran.value === 'Transfer') {
                    if (bankPilihanContainer) bankPilihanContainer.style.display = '';
                    if (dariBankContainer) dariBankContainer.style.display = '';
                    if (noRekeningContainer) noRekeningContainer.style.display = '';
                    if (buktiTfContainer) buktiTfContainer.style.display = '';
                } else {
                    if (bankPilihanContainer) bankPilihanContainer.style.display = 'none';
                    if (dariBankContainer) dariBankContainer.style.display = 'none';
                    if (noRekeningContainer) noRekeningContainer.style.display = 'none';
                    if (buktiTfContainer) buktiTfContainer.style.display = 'none';
                }
            }

            jenisPembayaran.addEventListener('change', toggleTransferFields);
            toggleTransferFields();
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const totalSetoranInput = document.getElementById('TotalSetoran');
            const totalPenawaran = "{{ $bookingList->getPenawaran->Total ?? 0 }}";
            const sisaBayarInput = document.getElementById('SisaBayar');

            function formatRupiah(angka, prefix) {
                let number_string = angka.replace(/[^,\d]/g, '').toString(),
                    split = number_string.split(','),
                    sisa = split[0].length % 3,
                    rupiah = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                    rupiah += (sisa ? '.' : '') + ribuan.join('.');
                }

                rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
                return prefix === undefined ? rupiah : (rupiah ? prefix + ' ' + rupiah : '');
            }

            function parseRupiah(str) {
                if (!str) return 0;
                return parseInt(str.replace(/[^0-9]/g, ''), 10) || 0;
            }

            function updateSisaBayar() {
                const totalPenawaranValue = parseInt(totalPenawaran, 10) || 0;
                const totalSetoranValue = parseRupiah(totalSetoranInput.value);

                let sisa = totalPenawaranValue - totalSetoranValue;
                if (sisa < 0) sisa = 0;
                sisaBayarInput.value = formatRupiah(sisa.toString(), 'Rp');
            }

            totalSetoranInput.addEventListener('input', function(e) {
                let value = this.value;
                let newValue = formatRupiah(value, 'Rp');
                this.value = newValue;
                this.setSelectionRange(newValue.length, newValue.length);
                updateSisaBayar();
            });

            if (totalSetoranInput.value) {
                totalSetoranInput.value = formatRupiah(parseRupiah(totalSetoranInput.value).toString(), 'Rp');
            }
            updateSisaBayar();
        });
    </script>
    <script>
        // Dropzone simple for input file (match create)
        document.addEventListener('DOMContentLoaded', function() {
            const dropzone = document.getElementById('dropzoneBuktiTf');
            const fileInput = document.getElementById('BuktiTf');
            const preview = document.getElementById('buktiTfPreview');
            const textSpan = document.getElementById('dropzoneTextBuktiTf');

            if (dropzone && fileInput) {
                dropzone.addEventListener('click', function() {
                    fileInput.click();
                });

                dropzone.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    dropzone.style.background = '#eaeaea';
                });

                dropzone.addEventListener('dragleave', function(e) {
                    e.preventDefault();
                    dropzone.style.background = '#fcfcfc';
                });

                dropzone.addEventListener('drop', function(e) {
                    e.preventDefault();
                    dropzone.style.background = '#fcfcfc';
                    if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
                        fileInput.files = e.dataTransfer.files;
                        triggerPreview();
                    }
                });

                fileInput.addEventListener('change', triggerPreview);

                function triggerPreview() {
                    const file = fileInput.files[0];
                    if (file) {
                        let el = '';
                        if (file.type.match('image.*')) {
                            const reader = new FileReader();
                            reader.onload = function(event) {
                                el =
                                    `<img src="${event.target.result}" alt="Preview" style="max-width:180px;max-height:180px;">`;
                                preview.innerHTML = el;
                            };
                            reader.readAsDataURL(file);
                        } else if (file.type === 'application/pdf') {
                            el = `<span><i class="fa fa-file-pdf"></i> ${file.name}</span>`;
                            preview.innerHTML = el;
                        } else {
                            preview.innerHTML = '<small>File terpilih: ' + file.name + '</small>';
                        }
                    } else {
                        preview.innerHTML = '';
                    }
                }
            }
        });
    </script>
@endpush
