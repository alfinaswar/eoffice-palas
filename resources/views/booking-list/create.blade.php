@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Booking List</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('booking-list.index') }}">Booking List</a></li>
                    <li class="breadcrumb-item active">Tambah Booking</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <h4 class="card-title mb-0">Formulir Booking List</h4>
                    <p class="card-text mb-0">
                        Silakan isi data booking baru di bawah ini.
                    </p>
                </div>
                <div class="card-body">
                    <form action="{{ route('booking-list.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="Nomor" class="form-label"><strong>Nomor Penawaran</strong></label>
                                <input type="text" class="form-control" id="Nomor" placeholder="Nomor"
                                    value="{{ $penawaran->Nomor }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="Tanggal" class="form-label"><strong>Tanggal</strong></label>
                                <input type="date" name="Tanggal"
                                    class="form-control @error('Tanggal') is-invalid @enderror" id="Tanggal"
                                    value="{{ old('Tanggal') }}">
                                @error('Tanggal')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="JenisPembayaran" class="form-label"><strong>Jenis Pembayaran</strong></label>
                                <select name="JenisPembayaran" id="JenisPembayaran"
                                    class="form-control @error('JenisPembayaran') is-invalid @enderror">
                                    <option value="">Pilih Jenis Pembayaran</option>
                                    <option value="Tunai" {{ old('JenisPembayaran') == 'Tunai' ? 'selected' : '' }}>Tunai
                                    </option>
                                    <option value="Transfer" {{ old('JenisPembayaran') == 'Transfer' ? 'selected' : '' }}>
                                        Transfer
                                    </option>
                                </select>
                                @error('JenisPembayaran')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- BANK TUJUAN (Visible only jika Transfer) -->
                            <div class="col-md-6" id="bankPilihanContainer" style="display: none;">
                                <label for="Bank" class="form-label"><strong>Pilih Bank</strong></label>
                                <select name="Bank" id="Bank"
                                    class="form-control @error('Bank') is-invalid @enderror">
                                    <option value="">Pilih Bank</option>
                                    @foreach ($bank ?? [] as $b)
                                        <option value="{{ $b->id }}" {{ old('Bank') == $b->id ? 'selected' : '' }}>
                                            {{ $b->Nama }} - {{ $b->Rekening }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('Bank')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- DARI BANK & NO REKENING (Visible only jika Transfer) -->
                            <div class="col-md-6" id="dariBankContainer" style="display: none;">
                                <label for="DariBank" class="form-label"><strong>Dari Bank</strong></label>
                                <select name="DariBank" id="DariBank"
                                    class="form-control @error('DariBank') is-invalid @enderror">
                                    <option value="">Pilih Pilih Bank</option>
                                    @foreach ($bank ?? [] as $b)
                                        <option value="{{ $b->id }}" {{ old('Bank') == $b->id ? 'selected' : '' }}>
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
                                    placeholder="No. Rekening Pengirim" value="{{ old('NoRekening') }}">
                                @error('NoRekening')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12" id="buktiTfContainer" style="display: none;">
                                <label for="BuktiTf" class="form-label"><strong>Upload Bukti Transfer</strong></label>
                                <div id="dropzoneBuktiTf" class="dropzone-custom @error('BuktiTf') is-invalid @enderror"
                                    style="border: 2px dashed #cccccc; border-radius: 6px; padding: 30px; text-align: center; cursor: pointer; background: #fcfcfc;">
                                    <span id="dropzoneTextBuktiTf"><i class="fa fa-upload"></i> Drag & Drop Atau Klik Untuk
                                        Mengunggah Bukti TF</span>
                                    <input type="file" name="Bukti" id="BuktiTf" accept="image/*,.pdf"
                                        style="display: none;">
                                </div>
                                <div id="buktiTfPreview" style="margin-top: 5px;"></div>
                                @error('BuktiTf')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="Penerima" class="form-label"><strong>Penerima</strong></label>
                                <input type="text" name="Penerima" class="form-control id=" Penerima"
                                    placeholder="Penerima" value="{{ auth()->user()->name }}" readonly>
                                @error('Penerima')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="Penyetor" class="form-label"><strong>Penyetor</strong></label>
                                <input type="text" name="Penyetor"
                                    class="form-control @error('Penyetor') is-invalid @enderror" id="Penyetor"
                                    placeholder="Penyetor" value="{{ old('Penyetor') }}">
                                @error('Penyetor')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tambahkan Total Setoran -->
                            <div class="col-md-6">
                                <label for="TotalSetoran" class="form-label"><strong>Total Setoran</strong></label>
                                <input type="text" name="TotalSetoran"
                                    class="form-control @error('TotalSetoran') is-invalid @enderror" id="TotalSetoran"
                                    placeholder="Total Setoran" value="{{ old('TotalSetoran') }}" autocomplete="off"
                                    min="0">
                                @error('TotalSetoran')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Sisa Bayar (otomatis) -->
                            <div class="col-md-6">
                                <label for="SisaBayar" class="form-label"><strong>Sisa Bayar</strong></label>
                                <input type="text" name="SisaBayar"
                                    class="form-control @error('SisaBayar') is-invalid @enderror" id="SisaBayar"
                                    placeholder="Sisa Bayar" value="" autocomplete="off" readonly>
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
                                            name="NomorPenawaran" value="{{ $penawaran->Nomor ?? '-' }}" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="TanggalPenawaran" class="form-label"><strong>Tanggal</strong></label>
                                        <input type="text" class="form-control" id="TanggalPenawaran"
                                            name="TanggalPenawaran"
                                            value="{{ $penawaran->Tanggal ? \Carbon\Carbon::parse($penawaran->Tanggal)->translatedFormat('d F Y') : '-' }}"
                                            readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="NamaPelangganPenawaran" class="form-label"><strong>Nama
                                                Pelanggan</strong></label>
                                        <input type="text" class="form-control" id="NamaPelangganPenawaranDisplay"
                                            value="{{ optional($customer->where('id', old('NamaPelangganPenawaran', $penawaran->NamaPelanggan ?? null))->first())->name ?? '-' }}"
                                            readonly>
                                        <input type="hidden" name="NamaPelangganPenawaran"
                                            value="{{ old('NamaPelangganPenawaran', $penawaran->NamaPelanggan ?? '') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="NamaProdukPenawaran" class="form-label"><strong>Nama
                                                Produk</strong></label>
                                        <input type="text" class="form-control" id="NamaProdukPenawaran"
                                            name="NamaProdukPenawaran"
                                            value="{{ $penawaran->DetailPenawaran[0]->getProduk->Nama ?? '-' }}" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="TotalPenawaran" class="form-label"><strong>Harga</strong></label>
                                        <input type="text" class="form-control" id="TotalPenawaran"
                                            name="TotalPenawaran"
                                            value="Rp {{ number_format($penawaran->Total ?? 0, 0, ',', '.') }}" readonly>
                                    </div>
                                </div>

                                <div class="mt-2 mb-2">
                                    <strong>Terbilang:</strong> {{ ucfirst(terbilang($penawaran->Total ?? 0)) }} rupiah
                                </div>
                            </div>

                        </div>
                        <div class="col-md-12 mt-3">
                            <label for="Keterangan" class="form-label"><strong>Keterangan</strong></label>
                            <textarea name="Keterangan" class="form-control @error('Keterangan') is-invalid @enderror" id="Keterangan"
                                placeholder="Keterangan" rows="3">{{ old('Keterangan') }}</textarea>
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
                <input type="hidden" name="IdPenawaran" value="{{ $penawaran->id }}">
                <input type="hidden" name="IdProduk"
                    value="{{ $penawaran->DetailPenawaran[0]->getProduk->id ?? '-' }}">

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
                    bankPilihanContainer.style.display = 'block';
                    dariBankContainer.style.display = 'block';
                    noRekeningContainer.style.display = 'block';
                    buktiTfContainer.style.display = 'block';
                } else {
                    bankPilihanContainer.style.display = 'none';
                    dariBankContainer.style.display = 'none';
                    noRekeningContainer.style.display = 'none';
                    buktiTfContainer.style.display = 'none';
                }
            }
            jenisPembayaran.addEventListener('change', toggleTransferFields);
            toggleTransferFields();

            // Dropzone for BuktiTF
            const dropzone = document.getElementById('dropzoneBuktiTf');
            const fileInput = document.getElementById('BuktiTf');
            const dropzoneText = document.getElementById('dropzoneTextBuktiTf');
            const preview = document.getElementById('buktiTfPreview');

            function showPreview(file) {
                preview.innerHTML = '';
                if (file.type.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.style.maxWidth = '140px';
                    img.style.maxHeight = '140px';
                    img.className = "mt-2";
                    img.file = file;
                    preview.appendChild(img);

                    const reader = new FileReader();
                    reader.onload = (function(aImg) {
                        return function(e) {
                            aImg.src = e.target.result;
                        };
                    })(img);
                    reader.readAsDataURL(file);
                } else if (file.type === 'application/pdf') {
                    const a = document.createElement('a');
                    a.href = URL.createObjectURL(file);
                    a.target = "_blank";
                    a.textContent = "Lihat Bukti Transfer (PDF)";
                    preview.appendChild(a);
                } else {
                    preview.textContent = "Unggah file gambar atau PDF saja.";
                }
            }

            if (dropzone && fileInput) {
                dropzone.addEventListener('click', function(e) {
                    fileInput.click();
                });

                dropzone.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.style.borderColor = '#007bff';
                });

                dropzone.addEventListener('dragleave', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.style.borderColor = '#cccccc';
                });

                dropzone.addEventListener('drop', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.style.borderColor = '#cccccc';
                    if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
                        fileInput.files = e.dataTransfer.files;
                        showPreview(e.dataTransfer.files[0]);
                    }
                });

                fileInput.addEventListener('change', function(e) {
                    if (this.files && this.files[0]) {
                        showPreview(this.files[0]);
                    }
                });
            }
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

            // Update otomatis Sisa Bayar jika TotalSetoran diubah
            function updateSisaBayar() {
                const totalPenawaranValue = parseRupiah(totalPenawaranInput.value);
                const totalSetoranValue = parseRupiah(totalSetoranInput.value);

                let sisa = totalPenawaranValue - totalSetoranValue;
                if (sisa < 0) sisa = 0;
                sisaBayarInput.value = formatRupiah(sisa.toString(), 'Rp');
            }

            totalSetoranInput.addEventListener('input', function(e) {
                // To avoid cursor jump, store selection pos
                let caret = this.selectionStart;
                let value = this.value;
                let newValue = formatRupiah(value, 'Rp');
                this.value = newValue;
                // Try to reposition caret if entering the middle
                this.setSelectionRange(newValue.length, newValue.length);

                updateSisaBayar();
            });

            // On page load, format old value, dan set Sisa Bayar
            if (totalSetoranInput.value) {
                totalSetoranInput.value = formatRupiah(totalSetoranInput.value, 'Rp');
            }
            updateSisaBayar();
        });
    </script>
@endpush
