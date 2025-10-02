@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Edit Produk</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('produk.index') }}">Produk</a></li>
                    <li class="breadcrumb-item active">Edit Produk</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-12">
            {{-- FORM START --}}
            <form action="{{ route('produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h4 class="card-title mb-0">Formulir Edit Produk</h4>
                        <small>Silakan ubah data produk di bawah ini.</small>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            {{-- Kode --}}
                            <div class="col-md-4">
                                <label for="kode" class="form-label"><strong>Kode</strong></label>
                                <input type="text" name="Kode" class="form-control @error('Kode') is-invalid @enderror"
                                    id="kode" placeholder="Kode" value="{{ old('Kode', $produk->Kode) }}">
                                @error('Kode')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Nama --}}
                            <div class="col-md-8">
                                <label for="nama" class="form-label"><strong>Nama</strong></label>
                                <input type="text" name="Nama" class="form-control @error('Nama') is-invalid @enderror"
                                    id="nama" placeholder="Nama" value="{{ old('Nama', $produk->Nama) }}">
                                @error('Nama')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Jenis --}}
                            <div class="col-md-4">
                                <label for="jenis" class="form-label"><strong>Jenis</strong></label>
                                <select name="Jenis" id="jenis"
                                    class="form-select select2 @error('Jenis') is-invalid @enderror">
                                    <option value="">-- Pilih Jenis --</option>
                                    @foreach($jenis as $j)
                                        <option value="{{ $j->id }}" {{ old('Jenis', $produk->Jenis) == $j->id ? 'selected' : '' }}>
                                            {{ $j->Nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('Jenis')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Grade --}}
                            <div class="col-md-4">
                                <label for="grade" class="form-label"><strong>Grade</strong></label>
                                <select name="Grade" id="grade"
                                    class="form-select select2 @error('Grade') is-invalid @enderror">
                                    <option value="">-- Pilih Grade --</option>
                                    @foreach($grade as $g)
                                        <option value="{{ $g->id }}" {{ old('Grade', $produk->Grade) == $g->id ? 'selected' : '' }}>
                                            {{ $g->Nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('Grade')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Proyek --}}
                            <div class="col-md-4">
                                <label for="proyek" class="form-label"><strong>Proyek</strong></label>
                                <select name="Proyek" id="proyek"
                                    class="form-select select2 @error('Proyek') is-invalid @enderror">
                                    <option value="">-- Pilih Proyek --</option>
                                    @foreach($proyek as $p)
                                        <option value="{{ $p->id }}" {{ old('Proyek', $produk->Proyek) == $p->id ? 'selected' : '' }}>
                                            {{ $p->NamaProyek }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('Proyek')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Luas --}}
                            <div class="col-md-4">
                                <label for="luas" class="form-label"><strong>Luas</strong></label>
                                <div class="input-group">
                                    <input type="number" name="Luas"
                                        class="form-control @error('Luas') is-invalid @enderror" id="luas"
                                        placeholder="Luas" value="{{ old('Luas', $produk->Luas) }}" min="0" step="any">
                                    <span class="input-group-text">m<sup>2</sup></span>
                                </div>
                                <small class="text-muted">* Satuan luas tanah dalam meter persegi (m<sup>2</sup>)</small>
                                @error('Luas')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- DP --}}
                            <div class="col-md-4">
                                <label for="dp" class="form-label"><strong>DP</strong></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" name="Dp"
                                        class="form-control rupiah @error('Dp') is-invalid @enderror" id="dp"
                                        placeholder="DP" value="{{ old('Dp', $produk->Dp) }}">
                                </div>
                                @error('Dp')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Diskon --}}
                            <div class="col-md-4">
                                <label for="diskon" class="form-label"><strong>Diskon (%)</strong></label>
                                <input type="text" name="Diskon" class="form-control @error('Diskon') is-invalid @enderror"
                                    id="diskon" placeholder="Diskon (%)" value="{{ old('Diskon', $produk->Diskon) }}">
                                @error('Diskon')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Keterangan --}}
                            <div class="col-md-12">
                                <label for="keterangan" class="form-label"><strong>Keterangan</strong></label>
                                <textarea name="Keterangan" class="form-control @error('Keterangan') is-invalid @enderror"
                                    id="keterangan" placeholder="Keterangan" rows="3">{{ old('Keterangan', $produk->Keterangan) }}</textarea>
                                @error('Keterangan')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Upload Gambar --}}
                            <div class="col-md-12">
                                <label for="gambar" class="form-label"><strong>Gambar</strong></label>
                                <div id="drop-area" class="border border-2 rounded p-3 text-center" style="cursor:pointer;">
                                    <p class="mb-2"><i class="fa fa-cloud-upload fa-2x"></i></p>
                                    <p class="mb-2">Seret & lepas gambar atau klik untuk memilih file</p>
                                    <input type="file" name="Gambar"
                                        class="form-control d-none @error('Gambar') is-invalid @enderror" id="gambar"
                                        accept="image/*">
                                    <div id="preview-gambar" class="mt-2">
                                        @if($produk->Gambar)
                                            <img src="{{ asset('storage/' . $produk->Gambar) }}" class="img-thumbnail" style="max-width: 200px; max-height: 200px;" />
                                        @endif
                                    </div>
                                </div>
                                @error('Gambar')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Status --}}
                            <div class="col-md-4">
                                <label for="status" class="form-label"><strong>Status</strong></label>
                                <select name="Status" id="status" class="form-select @error('Status') is-invalid @enderror">
                                    <option value="">-- Pilih Status --</option>
                                    <option value="Y" {{ old('Status', $produk->Status) == 'Y' ? 'selected' : '' }}>Aktif</option>
                                    <option value="N" {{ old('Status', $produk->Status) == 'N' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                                @error('Status')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CARD: HARGA --}}
                <div class="col-md-12 mt-4">
                    <div class="card">
                        <div class="card-header bg-dark text-white">
                            <h4 class="card-title mb-0">Harga Produk</h4>
                            <small>Silakan isi harga produk di bawah ini.</small>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 250px;">Tipe Harga</th>
                                            <th>Harga</th>
                                            <th>Harga Per Meter</th>
                                            <th>Harga Kredit</th>
                                            <th>Besar Angsuran</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>Harga Normal</strong></td>
                                            <td>
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="text" name="HargaNormal"
                                                        class="form-control rupiah @error('HargaNormal') is-invalid @enderror"
                                                        placeholder="Harga Normal" value="{{ old('HargaNormal', $produk->HargaNormal) }}"
                                                        id="harga_normal">
                                                </div>
                                                @error('HargaNormal')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="text" name="HargaPerMeter"
                                                        class="form-control rupiah @error('HargaPerMeter') is-invalid @enderror"
                                                        placeholder="Harga Per Meter" value="{{ old('HargaPerMeter', $produk->HargaPerMeter) }}"
                                                        id="harga_per_meter" readonly>
                                                </div>
                                                @error('HargaPerMeter')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="text" name="HargaKredit"
                                                        class="form-control rupiah @error('HargaKredit') is-invalid @enderror"
                                                        placeholder="Harga Kredit" value="{{ old('HargaKredit', $produk->HargaKredit) }}"
                                                        id="harga_kredit" readonly>
                                                </div>
                                                @error('HargaKredit')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="text" name="BesarAngsuran"
                                                        class="form-control rupiah @error('BesarAngsuran') is-invalid @enderror"
                                                        placeholder="Besar Angsuran" value="{{ old('BesarAngsuran', $produk->BesarAngsuran) }}">
                                                </div>
                                                @error('BesarAngsuran')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Harga Diskon</strong></td>
                                            <td>
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="text" name="HargaDiskon"
                                                        class="form-control rupiah @error('HargaDiskon') is-invalid @enderror"
                                                        placeholder="Harga Diskon" value="{{ old('HargaDiskon', $produk->HargaDiskon) }}"
                                                        id="harga_diskon" readonly>
                                                </div>
                                                @error('HargaDiskon')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="text" name="HargaPerMeter2"
                                                        class="form-control rupiah @error('HargaPerMeter2') is-invalid @enderror"
                                                        placeholder="Harga Per Meter 2" value="{{ old('HargaPerMeter2', $produk->HargaPerMeter2) }}"
                                                        id="harga_per_meter2" readonly>
                                                </div>
                                                @error('HargaPerMeter2')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="text" name="HargaKredit2"
                                                        class="form-control rupiah @error('HargaKredit2') is-invalid @enderror"
                                                        placeholder="Harga Kredit 2" value="{{ old('HargaKredit2', $produk->HargaKredit2) }}"
                                                        id="harga_kredit2" readonly>
                                                </div>
                                                @error('HargaKredit2')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="text" name="BesarAngsuran2"
                                                        class="form-control rupiah @error('BesarAngsuran2') is-invalid @enderror"
                                                        placeholder="Besar Angsuran 2" value="{{ old('BesarAngsuran2', $produk->BesarAngsuran2) }}">
                                                </div>
                                                @error('BesarAngsuran2')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-12 text-end mt-3">
                                <a href="{{ route('produk.index') }}" class="btn btn-secondary me-2">
                                    <i class="fa fa-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            {{-- FORM END --}}
        </div>
    </div>
@endsection

@push('js')
    <script>
        function formatRupiah(angka) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                var separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return rupiah;
        }

        function parseRupiah(str) {
            // Remove all non-digit and non-comma
            str = (str || '').replace(/\./g, '').replace(/[^0-9,]/g, '');
            if (str === '') return 0;
            // Only take before comma for integer
            let parts = str.split(',');
            return parseFloat(parts[0]);
        }

        function parsePersen(str) {
            // Remove all non-digit and non-dot and non-comma
            str = (str || '').replace(/[^0-9.,]/g, '').replace(',', '.');
            if (str === '') return 0;
            return parseFloat(str);
        }

        function updateHargaPerMeterDanKredit() {
            let hargaNormal = parseRupiah(document.getElementById('harga_normal').value);
            let luas = parseRupiah(document.getElementById('luas').value);

            // Harga Per Meter dan Harga Kredit (Normal)
            let hargaPerMeter = 0;
            let hargaKredit = 0;
            if (hargaNormal > 0 && luas > 0) {
                hargaPerMeter = Math.round(hargaNormal / luas);
                hargaKredit = Math.round(hargaNormal / luas); // Otomatis sama dengan harga permeter
            }
            document.getElementById('harga_per_meter').value = hargaPerMeter > 0 ? formatRupiah(hargaPerMeter.toString()) : '';
            document.getElementById('harga_kredit').value = hargaKredit > 0 ? formatRupiah(hargaKredit.toString()) : '';

            // Harga Diskon (dalam persen)
            let diskonPersen = parsePersen(document.getElementById('diskon').value);
            let hargaDiskon = 0;
            if (hargaNormal > 0 && diskonPersen > 0) {
                hargaDiskon = Math.round(hargaNormal - (hargaNormal * diskonPersen / 100));
            } else if (hargaNormal > 0) {
                hargaDiskon = hargaNormal;
            }
            document.getElementById('harga_diskon').value = hargaDiskon > 0 ? formatRupiah(hargaDiskon.toString()) : '';

            // Harga Per Meter 2 dan Harga Kredit 2 (berdasarkan HargaDiskon)
            let hargaPerMeter2 = 0;
            let hargaKredit2 = 0;
            if (hargaDiskon > 0 && luas > 0) {
                hargaPerMeter2 = Math.round(hargaDiskon / luas);
                hargaKredit2 = Math.round(hargaDiskon / luas); // Otomatis sama dengan harga permeter diskon
            }
            document.getElementById('harga_per_meter2').value = hargaPerMeter2 > 0 ? formatRupiah(hargaPerMeter2.toString()) : '';
            document.getElementById('harga_kredit2').value = hargaKredit2 > 0 ? formatRupiah(hargaKredit2.toString()) : '';
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('input.rupiah').forEach(function (input) {
                input.addEventListener('input', function (e) {
                    let value = this.value.replace(/\./g, '');
                    if (value) {
                        this.value = formatRupiah(value);
                    } else {
                        this.value = '';
                    }
                });
            });

            // Gambar preview
            const dropArea = document.getElementById('drop-area');
            const inputGambar = document.getElementById('gambar');
            const preview = document.getElementById('preview-gambar');

            if (dropArea && inputGambar && preview) {
                dropArea.addEventListener('click', function () {
                    inputGambar.click();
                });

                dropArea.addEventListener('dragover', function (e) {
                    e.preventDefault();
                    dropArea.classList.add('bg-light');
                });

                dropArea.addEventListener('dragleave', function (e) {
                    e.preventDefault();
                    dropArea.classList.remove('bg-light');
                });

                dropArea.addEventListener('drop', function (e) {
                    e.preventDefault();
                    dropArea.classList.remove('bg-light');
                    if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                        inputGambar.files = e.dataTransfer.files;
                        showPreview(e.dataTransfer.files[0]);
                    }
                });

                inputGambar.addEventListener('change', function (e) {
                    if (inputGambar.files && inputGambar.files[0]) {
                        showPreview(inputGambar.files[0]);
                    }
                });

                function showPreview(file) {
                    if (!file.type.startsWith('image/')) {
                        preview.innerHTML = '<span class="text-danger">File bukan gambar!</span>';
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        preview.innerHTML = '<img src="' + e.target.result +
                            '" class="img-thumbnail" style="max-width: 200px; max-height: 200px;" />';
                    }
                    reader.readAsDataURL(file);
                }
            }

            // Otomatis hitung Harga Per Meter, Harga Kredit, Harga Diskon, Harga Per Meter 2, Harga Kredit 2
            const hargaNormalInput = document.getElementById('harga_normal');
            const luasInput = document.getElementById('luas');
            const diskonInput = document.getElementById('diskon');

            if (hargaNormalInput && luasInput && diskonInput) {
                hargaNormalInput.addEventListener('input', updateHargaPerMeterDanKredit);
                luasInput.addEventListener('input', updateHargaPerMeterDanKredit);
                diskonInput.addEventListener('input', updateHargaPerMeterDanKredit);

                // Inisialisasi saat load
                updateHargaPerMeterDanKredit();
            }
        });
    </script>
@endpush
