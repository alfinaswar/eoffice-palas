@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Booking List</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('booking-list.index') }}">Booking List</a></li>
                    <li class="breadcrumb-item active">Edit Booking</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <h4 class="card-title mb-0">Edit Booking List</h4>
                    <p class="card-text mb-0">
                        Silakan ubah data booking di bawah ini.
                    </p>
                </div>
                <div class="card-body">
                    <form action="{{ route('booking-list.update', encrypt($bookingList->id)) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="Nomor" class="form-label"><strong>Nomor Penawaran</strong></label>
                                <input type="text" class="form-control" id="Nomor" placeholder="Nomor"
                                    value="{{ $bookingList->getPenawaran->Nomor ?? '-' }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="Tanggal" class="form-label"><strong>Tanggal</strong></label>
                                <input type="date" name="Tanggal"
                                    class="form-control @error('Tanggal') is-invalid @enderror" id="Tanggal"
                                    value="{{ old('Tanggal', $bookingList->Tanggal) }}">
                                @error('Tanggal')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="JenisPembayaran" class="form-label"><strong>Jenis Pembayaran</strong></label>
                                <select name="JenisPembayaran" id="JenisPembayaran"
                                    class="form-control @error('JenisPembayaran') is-invalid @enderror">
                                    <option value="">Pilih Jenis Pembayaran</option>
                                    <option value="Tunai"
                                        {{ old('JenisPembayaran', $bookingList->JenisPembayaran) == 'Tunai' ? 'selected' : '' }}>
                                        Tunai
                                    </option>
                                    <option value="Transfer"
                                        {{ old('JenisPembayaran', $bookingList->JenisPembayaran) == 'Transfer' ? 'selected' : '' }}>
                                        Transfer
                                    </option>
                                </select>
                                @error('JenisPembayaran')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6" id="bankPilihanContainer" style="display: none;">
                                <label for="Bank" class="form-label"><strong>Pilih Bank</strong></label>
                                <select name="Bank" id="Bank"
                                    class="form-control @error('Bank') is-invalid @enderror">
                                    <option value="">Pilih Bank</option>
                                    @foreach ($bank ?? [] as $b)
                                        <option value="{{ $b->id }}"
                                            {{ old('Bank', $bookingList->NamaBank) == $b->id ? 'selected' : '' }}>
                                            {{ $b->Nama }} - {{ $b->Rekening }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('Bank')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="Penerima" class="form-label"><strong>Penerima</strong></label>
                                <input type="text" class="form-control id=" Penerima" placeholder="Penerima"
                                    value="{{ old('Penerima', $bookingList->getKaryawan->name) }}" readonly>
                                @error('Penerima')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="Penyetor" class="form-label"><strong>Penyetor</strong></label>
                                <input type="text" name="Penyetor"
                                    class="form-control @error('Penyetor') is-invalid @enderror" id="Penyetor"
                                    placeholder="Penyetor" value="{{ old('Penyetor', $bookingList->Penyetor) }}">
                                @error('Penyetor')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="TotalSetoran" class="form-label"><strong>Total Setoran</strong></label>
                                <input type="text" name="TotalSetoran"
                                    class="form-control @error('TotalSetoran') is-invalid @enderror" id="TotalSetoran"
                                    placeholder="Total Setoran"
                                    value="{{ old('TotalSetoran', 'Rp ' . number_format($bookingList->Total ?? 0, 0, ',', '.')) }}"
                                    autocomplete="off" min="0">
                                @error('TotalSetoran')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="SisaBayar" class="form-label"><strong>Sisa Bayar</strong></label>
                                <input type="text" name="SisaBayar"
                                    class="form-control @error('SisaBayar') is-invalid @enderror" id="SisaBayar"
                                    placeholder="Sisa Bayar"
                                    value="{{ old('SisaBayar', 'Rp ' . number_format($bookingList->SisaBayar ?? 0, 0, ',', '.')) }}"
                                    autocomplete="off" readonly>
                                @error('SisaBayar')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mt-4">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="NomorPenawaran" class="form-label"><strong>Nomor
                                                Penawaran</strong></label>
                                        <input type="text" class="form-control" id="NomorPenawaran"
                                            name="NomorPenawaran" value="{{ $bookingList->getPenawaran->Nomor ?? '-' }}"
                                            readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="TanggalPenawaran" class="form-label"><strong>Tanggal</strong></label>
                                        <input type="text" class="form-control" id="TanggalPenawaran"
                                            name="TanggalPenawaran"
                                            value="{{ $bookingList->getPenawaran && $bookingList->getPenawaran->Tanggal ? \Carbon\Carbon::parse($bookingList->getPenawaran->Tanggal)->translatedFormat('d F Y') : '-' }}"
                                            readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="NamaPelangganPenawaran" class="form-label"><strong>Nama
                                                Pelanggan</strong></label>
                                        <input type="text" class="form-control" id="NamaPelangganPenawaranDisplay"
                                            value="{{ optional($bookingList->getPenawaran->getCustomer ?? null)->name ?? '-' }}"
                                            readonly>
                                        <input type="hidden" name="NamaPelangganPenawaran"
                                            value="{{ old('NamaPelangganPenawaran', $bookingList->getPenawaran->NamaPelanggan ?? '') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="NamaProdukPenawaran" class="form-label"><strong>Nama
                                                Produk</strong></label>
                                        <input type="text" class="form-control" id="NamaProdukPenawaran"
                                            name="NamaProdukPenawaran"
                                            value="{{ $bookingList->getPenawaran->DetailPenawaran[0]->getProduk->Nama ?? '-' }}"
                                            readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="TotalPenawaran" class="form-label"><strong>Harga</strong></label>
                                        <input type="text" class="form-control" id="TotalPenawaran"
                                            name="TotalPenawaran"
                                            value="Rp {{ number_format($bookingList->getPenawaran->Total ?? 0, 0, ',', '.') }}"
                                            readonly>
                                    </div>
                                </div>

                                <div class="mt-2 mb-2">
                                    <strong>Terbilang:</strong>
                                    {{ ucfirst(terbilang($bookingList->getPenawaran->Total ?? 0)) }} rupiah
                                </div>
                            </div>

                        </div>
                        <div class="col-md-12 mt-3">
                            <label for="Keterangan" class="form-label"><strong>Keterangan</strong></label>
                            <textarea name="Keterangan" class="form-control @error('Keterangan') is-invalid @enderror" id="Keterangan"
                                placeholder="Keterangan" rows="3">{{ old('Keterangan', $bookingList->Keterangan) }}</textarea>
                            @error('Keterangan')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 text-end mt-3">
                            <a href="{{ route('booking-list.index') }}" class="btn btn-secondary me-2">
                                <i class="fa fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Simpan
                            </button>
                        </div>
                </div>
                <input type="hidden" name="IdPenawaran" value="{{ $bookingList->IdPenawaran }}">
                <input type="hidden" name="IdProduk"
                    value="{{ $bookingList->IdProduk ?? ($bookingList->getPenawaran->DetailPenawaran[0]->getProduk->id ?? '-') }}">

                </form>
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

            function toggleBankPilihan() {
                if (jenisPembayaran.value === 'Transfer') {
                    bankPilihanContainer.style.display = 'block';
                } else {
                    bankPilihanContainer.style.display = 'none';
                }
            }
            jenisPembayaran.addEventListener('change', toggleBankPilihan);
            toggleBankPilihan();
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const totalSetoranInput = document.getElementById('TotalSetoran');
            const totalPenawaranInput = document.getElementById('TotalPenawaran');
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

            // Ambil nilai numeric dari string 'Rp xx.xxx'
            function parseRupiah(str) {
                if (!str) return 0;
                return parseInt(str.replace(/[^0-9]/g, ''), 10) || 0;
            }

            function updateSisaBayar() {
                const totalPenawaranValue = parseRupiah(totalPenawaranInput.value);
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
@endpush
