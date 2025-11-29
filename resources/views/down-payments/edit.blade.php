@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Down Payment</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dp.index') }}">Down Payment</a></li>
                    <li class="breadcrumb-item active">Edit Down Payment</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <h4 class="card-title mb-0">Edit Down Payment</h4>
                    <p class="card-text mb-0">
                        Silakan ubah data down payment di bawah ini.
                    </p>
                </div>
                <div class="card-body">
                    <form action="{{ route('dp.update', encrypt($dp->id)) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="Nomor" class="form-label"><strong>Nomor Down Payment</strong></label>
                                <input type="text" class="form-control" id="Nomor" placeholder="Nomor"
                                    value="{{ $dp->Nomor ?? '-' }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="NamaPelanggan" class="form-label"><strong>Nama Pelanggan</strong></label>
                                <select name="NamaPelanggan" id="NamaPelanggan"
                                    class="form-control select2 @error('NamaPelanggan') is-invalid @enderror">
                                    <option value="">Pilih Nama Pelanggan</option>
                                    @foreach ($customer ?? [] as $cust)
                                        <option value="{{ $cust->id }}"
                                            {{ old('NamaPelanggan', $dp->NamaPelanggan ?? '') == $cust->id ? 'selected' : '' }}>
                                            {{ $cust->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('NamaPelanggan')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="Tanggal" class="form-label"><strong>Tanggal</strong></label>
                                <input type="date" name="Tanggal"
                                    class="form-control @error('Tanggal') is-invalid @enderror" id="Tanggal"
                                    value="{{ old('Tanggal', $dp->Tanggal ? date('Y-m-d', strtotime($dp->Tanggal)) : date('Y-m-d')) }}">
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
                                        {{ old('JenisPembayaran', $dp->JenisPembayaran) == 'Tunai' ? 'selected' : '' }}>
                                        Tunai
                                    </option>
                                    <option value="Transfer"
                                        {{ old('JenisPembayaran', $dp->JenisPembayaran) == 'Transfer' ? 'selected' : '' }}>
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
                                            {{ old('Bank', $dp->NamaBank) == $b->id ? 'selected' : '' }}>
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
                                <input type="text" name="Penerima" class="form-control" id="Penerima"
                                    placeholder="Penerima" value="{{ $dp->getPenerima->name ?? auth()->user()->name }}"
                                    readonly>
                                @error('Penerima')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="Penyetor" class="form-label"><strong>Penyetor</strong></label>
                                <input type="text" name="Penyetor"
                                    class="form-control @error('Penyetor') is-invalid @enderror" id="Penyetor"
                                    placeholder="Penyetor" value="{{ old('Penyetor', $dp->Penyetor) }}">
                                @error('Penyetor')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="Total" class="form-label"><strong>Nominal Down Payment</strong></label>
                                <input type="text" name="Total"
                                    class="form-control @error('Total') is-invalid @enderror" id="TotalSetoran"
                                    placeholder="Nominal Down Payment"
                                    value="{{ old('Total', 'Rp ' . number_format($dp->Total ?? 0, 0, ',', '.')) }}"
                                    autocomplete="off" min="0">
                                @error('Total')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="SisaBayar" class="form-label"><strong>Sisa Bayar</strong></label>
                                <input type="text" name="SisaBayar"
                                    class="form-control @error('SisaBayar') is-invalid @enderror" id="SisaBayar"
                                    placeholder="Sisa Bayar" value="" autocomplete="off" readonly>
                                @error('SisaBayar')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                                <input type="hidden" id="SisaBayarRaw" name="SisaBayarRaw" value="">
                            </div>

                            <div class="col-md-12 mt-4">
                                <div class="row">
                                    <div class="col-md-3 mb-2">
                                        <label for="NomorBooking" class="form-label"><strong>Nomor
                                                Booking</strong></label>
                                        <input type="text" class="form-control" name="NomorBooking" id="NomorBooking"
                                            value="{{ $dp->getBooking->Nomor ?? '-' }}" readonly>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label for="TanggalBooking" class="form-label"><strong>Tanggal</strong></label>
                                        <input type="text" class="form-control" name="TanggalBooking"
                                            id="TanggalBooking"
                                            value="{{ $dp->getBooking->Tanggal ? \Carbon\Carbon::parse($dp->getBooking->Tanggal)->translatedFormat('d F Y') : '-' }}"
                                            readonly>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label for="NamaPelangganBooking" class="form-label"><strong>Nama
                                                Pelanggan</strong></label>
                                        <input type="text" class="form-control" name="NamaPelangganBooking"
                                            id="NamaPelangganBooking"
                                            value="{{ $dp->getBooking->getCustomer->name ?? '-' }}" readonly>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label for="NamaProdukBooking" class="form-label"><strong>Nama
                                                Produk</strong></label>
                                        <input type="text" class="form-control" name="NamaProdukBooking"
                                            id="NamaProdukBooking" value="{{ $dp->getBooking->getProduk->Nama ?? '-' }}"
                                            readonly>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label for="TotalBooking" class="form-label"><strong>Total
                                                Booking</strong></label>
                                        <input type="text" class="form-control" name="TotalBooking" id="TotalBooking"
                                            value="{{ 'Rp ' . number_format($dp->getBooking->Total ?? 0, 0, ',', '.') }}"
                                            readonly>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label for="SisaBayarBooking" class="form-label"><strong>Sisa Bayar dari
                                                Booking</strong></label>
                                        <input type="text" class="form-control" name="SisaBayarBooking"
                                            id="SisaBayarBooking"
                                            value="{{ 'Rp ' . number_format($dp->getBooking->SisaBayar ?? 0, 0, ',', '.') }}"
                                            readonly>
                                    </div>
                                </div>
                                <input type="hidden" name="IdBooking" value="{{ $dp->getBooking->id }}">
                                <input type="hidden" name="IdProduk"
                                    value="{{ $dp->getBooking->getProduk->id ?? '-' }}">
                            </div>
                        </div>
                        <div class="col-md-12 mt-3">
                            <label for="Keterangan" class="form-label"><strong>Keterangan</strong></label>
                            <textarea name="Keterangan" class="form-control @error('Keterangan') is-invalid @enderror" id="Keterangan"
                                placeholder="Keterangan" rows="3">{{ old('Keterangan', $dp->Keterangan) }}</textarea>
                            @error('Keterangan')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 text-end mt-3">
                            <a href="{{ route('dp.index') }}" class="btn btn-secondary me-2">
                                <i class="fa fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Update
                            </button>
                        </div>
                </div>
                <input type="hidden" name="IdBooking" value="{{ $dp->getBooking->id }}">
                <input type="hidden" name="IdProduk" value="{{ $dp->getBooking->getProduk->id ?? '-' }}">

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
            const sisaBayarBookingInput = document.getElementById('SisaBayarBooking');
            const sisaBayarInput = document.getElementById('SisaBayar');
            const sisaBayarRawInput = document.getElementById('SisaBayarRaw');

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
                const sisaBookingValue = parseRupiah(sisaBayarBookingInput.value);
                const totalSetoranValue = parseRupiah(totalSetoranInput.value);

                let sisa = sisaBookingValue - totalSetoranValue;
                if (sisa < 0) sisa = 0;
                sisaBayarInput.value = formatRupiah(sisa.toString(), 'Rp');
                sisaBayarRawInput.value = sisa;
            }

            totalSetoranInput.addEventListener('input', function(e) {
                let value = this.value;
                let newValue = formatRupiah(value, 'Rp');
                this.value = newValue;
                this.setSelectionRange(newValue.length, newValue.length);
                updateSisaBayar();
            });

            // On page load, format value, dan set Sisa Bayar
            if (totalSetoranInput.value) {
                totalSetoranInput.value = formatRupiah(totalSetoranInput.value, 'Rp');
            }
            updateSisaBayar();
        });
    </script>
@endpush
