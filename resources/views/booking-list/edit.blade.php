@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Edit Booking</h3>
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
                    <span class="card-text mb-0">Silakan ubah data booking di bawah ini.</span>
                </div>
                <div class="card-body">

                    <form action="{{ route('booking-list.update', encrypt($bookingList->id)) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label for="NomorPenawaran" class="form-label"><strong>Nomor Penawaran</strong></label>
                                <input type="text" class="form-control" id="NomorPenawaran"
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
                                    class="form-select @error('JenisPembayaran') is-invalid @enderror">
                                    <option value="">Pilih Jenis Pembayaran</option>
                                    <option value="Tunai"
                                        {{ old('JenisPembayaran', $bookingList->JenisPembayaran) == 'Tunai' ? 'selected' : '' }}>
                                        Tunai</option>
                                    <option value="Transfer"
                                        {{ old('JenisPembayaran', $bookingList->JenisPembayaran) == 'Transfer' ? 'selected' : '' }}>
                                        Transfer</option>
                                </select>
                                @error('JenisPembayaran')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6" id="bankPilihanContainer" style="display: none;">
                                <label for="Bank" class="form-label"><strong>Pilih Bank</strong></label>
                                <select name="Bank" id="Bank"
                                    class="form-select @error('Bank') is-invalid @enderror">
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

                            <div class="col-md-6" id="dariBankContainer" style="display: none;">
                                <label for="DariBank" class="form-label"><strong>Dari Bank</strong></label>
                                <select name="DariBank" id="DariBank"
                                    class="form-control @error('DariBank') is-invalid @enderror">
                                    <option value="">Pilih Bank</option>
                                    @foreach ($bank ?? [] as $b)
                                        <option value="{{ $b->id }}"
                                            {{ old('DariBank', $bookingList->DariBank) == $b->id ? 'selected' : '' }}>
                                            {{ $b->Nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('DariBank')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6" id="noRekeningContainer" style="display: none;">
                                <label for="NoRekening" class="form-label"><strong>No. Rekening Pengirim</strong></label>
                                <input type="text" name="NoRekening"
                                    class="form-control @error('NoRekening') is-invalid @enderror" id="NoRekening"
                                    placeholder="Masukkan no rekening pengirim"
                                    value="{{ old('NoRekening', $bookingList->NoRekening) }}">
                                @error('NoRekening')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12" id="buktiTfContainer" style="display: none;">
                                <label for="BuktiTf" class="form-label"><strong>Upload Bukti Transfer</strong></label>
                                <div id="dropzoneBuktiTf" class="dropzone-custom @error('BuktiTf') is-invalid @enderror"
                                    style="border: 2px dashed #cccccc; border-radius: 6px; padding: 30px; text-align: center; cursor: pointer; background: #fcfcfc;">
                                    <span id="dropzoneTextBuktiTf">
                                        <i class="fa fa-upload"></i> Drag & Drop Atau Klik Untuk Mengunggah Bukti TF
                                    </span>
                                    <input type="file" name="Bukti" id="BuktiTf" accept="image/*,.pdf"
                                        style="display: none;">
                                </div>
                                <div id="buktiTfPreview" style="margin-top: 5px;">
                                    @if ($bookingList->Bukti)
                                        <a href="{{ asset('uploads/bukti-tf/' . $bookingList->Bukti) }}"
                                            target="_blank">Lihat file lama</a>
                                    @endif
                                </div>
                                @error('BuktiTf')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="Penerima" class="form-label"><strong>Penerima</strong></label>
                                <input type="text" class="form-control" id="Penerima"
                                    value="{{ $bookingList->getKaryawan->name ?? '-' }}" readonly>
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
                                        <input type="hidden" name="NamaPelangganPenawaran"
                                            value="{{ old('NamaPelangganPenawaran', $bookingList->getPenawaran->NamaPelanggan ?? '') }}">
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
