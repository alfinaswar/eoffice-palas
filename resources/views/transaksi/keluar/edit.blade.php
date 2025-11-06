@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Transaksi Keluar</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('transaksi-keluar.index') }}">Transaksi Keluar</a></li>
                    <li class="breadcrumb-item active">Edit Transaksi Keluar</li>
                </ul>
            </div>
        </div>
    </div>

    @php
        $currentUserName = auth()->user()->name;
    @endphp

    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white">
                    <h4 class="card-title mb-0">Edit Transaksi Keluar</h4>
                    <small class="card-text mb-0">Ubah data transaksi keluar dan detail barang.</small>
                </div>
                <div class="card-body">
                    <form action="{{ route('transaksi-keluar.update', $transaksiKeluar->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label for="Jenis" class="form-label fw-semibold">Jenis</label>
                                <select name="Jenis" id="Jenis" class="select2 @error('Jenis') is-invalid @enderror"
                                    required>
                                    <option value="">Pilih Jenis</option>
                                    @foreach ($jenis as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ (old('Jenis') ?? $transaksiKeluar->Jenis) == $value->id ? 'selected' : '' }}>
                                            {{ $value->Nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('Jenis')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="Tanggal" class="form-label fw-semibold">Tanggal</label>
                                <input type="date" name="Tanggal" id="Tanggal"
                                    class="form-control @error('Tanggal') is-invalid @enderror"
                                    value="{{ old('Tanggal', $transaksiKeluar->Tanggal ? \Carbon\Carbon::parse($transaksiKeluar->Tanggal)->format('Y-m-d') : date('Y-m-d')) }}"
                                    required>
                                @error('Tanggal')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="Keterangan" class="form-label fw-semibold">Keterangan</label>
                                <textarea name="Keterangan" id="Keterangan" rows="3"
                                    class="form-control @error('Keterangan') is-invalid @enderror"
                                    placeholder="Tuliskan keterangan tambahan di sini...">{{ old('Keterangan', $transaksiKeluar->Keterangan) }}</textarea>
                                @error('Keterangan')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="mb-4">
                        <h5 class="mb-3 fw-bold">Detail Barang Keluar</h5>
                        <div class="table-responsive mb-2">
                            <table class="table table-sm table-hover align-middle border rounded-1" id="table-detail">
                                <thead class="text-center table-light align-middle">
                                    <tr>
                                        <th style="width: 18%">Nama Barang</th>
                                        <th style="width: 10%">Jumlah</th>
                                        <th style="width: 12%">Harga</th>
                                        <th style="width: 15%">Total</th>
                                        <th style="width: 20%">Keterangan</th>
                                        <th style="width: 10%">Petugas</th>
                                        <th style="width: 6%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (old('NamaBarang'))
                                        @foreach (old('NamaBarang') as $i => $namaBarang)
                                            <tr>
                                                <td>
                                                    <input type="text" name="NamaBarang[]" class="form-control"
                                                        placeholder="Nama Barang" value="{{ old('NamaBarang.' . $i) }}"
                                                        required>
                                                </td>
                                                <td>
                                                    <input type="number" name="Jumlah[]" min="1"
                                                        class="form-control jumlah-input text-end"
                                                        value="{{ old('Jumlah.' . $i, 1) }}" required>
                                                </td>
                                                <td>
                                                    <input type="text" name="Harga[]"
                                                        class="form-control harga-input text-end"
                                                        value="{{ old('Harga.' . $i, 'Rp 0') }}" required>
                                                </td>
                                                <td>
                                                    <input type="text" name="Total[]"
                                                        class="form-control total-input text-end"
                                                        value="{{ old('Total.' . $i, '0') }}" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" name="KeteranganDetail[]" class="form-control"
                                                        placeholder="Keterangan"
                                                        value="{{ old('KeteranganDetail.' . $i) }}">
                                                </td>
                                                <td>
                                                    <input type="text" name="IdPetugas[]" class="form-control"
                                                        placeholder="Nama Petugas"
                                                        value="{{ old('IdPetugas.' . $i, $currentUserName) }}" readonly
                                                        required>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-link text-danger p-0 btn-remove"
                                                        title="Hapus Baris">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @elseif(isset($transaksiKeluar->getDetail) && count($transaksiKeluar->getDetail) > 0)
                                        @foreach ($transaksiKeluar->getDetail as $detail)
                                            <tr>
                                                <td>
                                                    <input type="text" name="NamaBarang[]" class="form-control"
                                                        placeholder="Nama Barang" value="{{ $detail->NamaBarang }}"
                                                        required>
                                                </td>
                                                <td>
                                                    <input type="number" name="Jumlah[]" min="1"
                                                        class="form-control jumlah-input text-end"
                                                        value="{{ $detail->Jumlah }}" required>
                                                </td>
                                                <td>
                                                    <input type="text" name="Harga[]"
                                                        class="form-control harga-input text-end"
                                                        value="{{ 'Rp ' . number_format($detail->Harga, 0, ',', '.') }}"
                                                        required>
                                                </td>
                                                <td>
                                                    <input type="text" name="Total[]"
                                                        class="form-control total-input text-end"
                                                        value="{{ number_format($detail->Jumlah * $detail->Harga, 0, ',', '.') }}"
                                                        readonly>
                                                </td>
                                                <td>
                                                    <input type="text" name="KeteranganDetail[]" class="form-control"
                                                        placeholder="Keterangan" value="{{ $detail->KeteranganDetail }}">
                                                </td>
                                                <td>
                                                    <input type="text" name="IdPetugas[]" class="form-control"
                                                        placeholder="Nama Petugas"
                                                        value="{{ $detail->IdPetugas ?? $currentUserName }}" readonly
                                                        required>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-link text-danger p-0 btn-remove"
                                                        title="Hapus Baris">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td>
                                                <input type="text" name="NamaBarang[]" class="form-control"
                                                    placeholder="Nama Barang" required>
                                            </td>
                                            <td>
                                                <input type="number" name="Jumlah[]" min="1"
                                                    class="form-control jumlah-input text-end" value="1" required>
                                            </td>
                                            <td>
                                                <input type="text" name="Harga[]"
                                                    class="form-control harga-input text-end" value="Rp 0" required>
                                            </td>
                                            <td>
                                                <input type="text" name="Total[]"
                                                    class="form-control total-input text-end" value="0" readonly>
                                            </td>
                                            <td>
                                                <input type="text" name="KeteranganDetail[]" class="form-control"
                                                    placeholder="Keterangan">
                                            </td>
                                            <td>
                                                <input type="text" name="IdPetugas[]" class="form-control"
                                                    placeholder="Nama Petugas" value="{{ $currentUserName }}" readonly
                                                    required>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-link text-danger p-0 btn-remove"
                                                    title="Hapus Baris">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <div class="text-end mb-4">
                            <button type="button" class="btn btn-outline-primary btn-sm px-3" id="btn-add">
                                <i class="fa fa-plus"></i> Tambah Baris
                            </button>
                        </div>

                        <div class="row justify-content-end align-items-center">
                            <div class="col-md-4">
                                <label for="GrandTotal" class="form-label fw-semibold">Grand Total</label>
                                <input type="text" name="GrandTotal" id="GrandTotal"
                                    class="form-control text-end fw-bold"
                                    value="{{ old('GrandTotal',isset($transaksiKeluar->getDetail)? number_format($transaksiKeluar->getDetail->sum(function ($d) {return $d->Jumlah * $d->Harga;}),0,',','.'): '0') }}"
                                    readonly>
                            </div>
                        </div>

                        <div class="col-12 text-end mt-4">
                            <a href="{{ route('transaksi-keluar.index') }}" class="btn btn-secondary me-2">
                                <i class="fa fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Update Transaksi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Script dinamis -->
    <script>
        // Simpan nama user di JavaScript dari blade
        const currentUserName = @json($currentUserName);

        // Format Rp otomatis (dengan prefiks Rp) saat mengetik harga
        function formatRupiahWithPrefix(angka, prefix = 'Rp ') {
            angka = angka.toString().replace(/[^,\d]/g, "");
            let split = angka.split(",");
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);
            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join(".");
            }
            rupiah = split[1] !== undefined ? rupiah + "," + split[1] : rupiah;
            return prefix + rupiah;
        }

        // Format harga tanpa prefiks
        function formatRupiah(angka) {
            angka = angka.toString().replace(/[^,\d]/g, "");
            const split = angka.split(",");
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            const ribuan = split[0].substr(sisa).match(/\d{3}/gi);
            if (ribuan) {
                const separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join(".");
            }
            rupiah = split[1] !== undefined ? rupiah + "," + split[1] : rupiah;
            return rupiah;
        }

        function unformatRupiah(rpStr) {
            return (rpStr || '').replace(/^Rp\s*/i, '').replace(/\./g, '').replace(',', '.');
        }

        function hitungTotalBaris(row) {
            let jumlah = parseFloat(row.querySelector('.jumlah-input').value.replace(/\./g, '')) || 0;
            let hargaInput = row.querySelector('.harga-input').value;
            let harga = parseFloat(unformatRupiah(hargaInput)) || 0;
            let total = jumlah * harga;
            row.querySelector('.total-input').value = formatRupiah(total.toFixed(0));
        }

        function refreshGrandTotal() {
            let grandTotal = 0;
            document.querySelectorAll('#table-detail .total-input').forEach(function(input) {
                let val = (input.value || "").replaceAll('.', '').replace(',', '.');
                grandTotal += parseFloat(val) || 0;
            });
            document.getElementById('GrandTotal').value = formatRupiah(grandTotal.toString());
        }

        // Tambah baris baru
        document.getElementById('btn-add').addEventListener('click', function() {
            const tbody = document.querySelector('#table-detail tbody');
            const row = tbody.rows[0].cloneNode(true);

            // Reset input values in the cloned row
            row.querySelectorAll('input, select').forEach(input => {
                if (input.tagName === 'SELECT') {
                    input.selectedIndex = 0;
                } else if (input.type === 'number') {
                    input.value = 1;
                } else if (input.type === 'date') {
                    input.value = '{{ date('Y-m-d') }}';
                } else if (input.classList.contains('harga-input')) {
                    input.value = 'Rp 0';
                } else if (input.classList.contains('total-input')) {
                    input.value = '0';
                } else if (input.classList.contains('IdPetugas')) {
                    input.value = currentUserName;
                } else {
                    input.value = '';
                }
            });

            const petugasInput = row.querySelector('input[name="IdPetugas[]"]');
            if (petugasInput) {
                petugasInput.value = currentUserName;
            }

            tbody.appendChild(row);
        });

        // Remove baris detail
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-remove') || (e.target.parentElement && e.target.parentElement
                    .classList.contains('btn-remove'))) {
                const tr = e.target.closest('tr');
                const tbody = tr.closest('tbody');
                if (tbody.rows.length > 1) {
                    tr.remove();
                    refreshGrandTotal();
                }
            }
        });

        // Harga format otomatis
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('harga-input')) {
                let rpUnformat = e.target.value.replace(/^Rp\s*/i, '').replace(/[^,\d]/g, "");
                if (rpUnformat) {
                    e.target.value = formatRupiahWithPrefix(rpUnformat);
                } else {
                    e.target.value = 'Rp 0';
                }
                const tr = e.target.closest('tr');
                hitungTotalBaris(tr);
                refreshGrandTotal();
                e.target.setSelectionRange(e.target.value.length, e.target.value.length);
            }
            if (e.target.classList.contains('jumlah-input')) {
                const tr = e.target.closest('tr');
                hitungTotalBaris(tr);
                refreshGrandTotal();
            }
        });

        document.addEventListener('blur', function(e) {
            if (e.target.classList.contains('harga-input')) {
                let val = e.target.value.replace(/^Rp\s*/i, '').replace(/[^,\d]/g, "");
                e.target.value = val ? formatRupiahWithPrefix(val) : 'Rp 0';
                const tr = e.target.closest('tr');
                hitungTotalBaris(tr);
                refreshGrandTotal();
            }
        }, true);

        // Hitung total awal & format
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('#table-detail tbody tr').forEach(function(row) {
                let hargaInput = row.querySelector('.harga-input');
                if (hargaInput) {
                    let val = hargaInput.value.replace(/^Rp\s*/i, '').replace(/[^,\d]/g, "");
                    hargaInput.value = val ? formatRupiahWithPrefix(val) : 'Rp 0';
                }
                hitungTotalBaris(row);
                const petugasInput = row.querySelector('input[name="IdPetugas[]"]');
                if (petugasInput && (!petugasInput.value || petugasInput.value === '')) {
                    petugasInput.value = currentUserName;
                }
            });
            refreshGrandTotal();
        });
    </script>
@endsection
