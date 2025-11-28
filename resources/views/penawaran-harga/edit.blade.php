@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Penawaran Harga</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('penawaran-harga.index') }}">Penawaran Harga</a></li>
                    <li class="breadcrumb-item active">Edit Penawaran</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white">
                    <h4 class="card-title mb-0">Edit Penawaran Harga</h4>
                    <small class="card-text mb-0">Ubah data penawaran dan produk yang ditawarkan.</small>
                </div>
                <div class="card-body">
                    <form action="{{ route('penawaran-harga.update', $penawaran->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label for="Tanggal" class="form-label fw-semibold">Tanggal</label>
                                <input type="date" name="Tanggal" id="Tanggal"
                                    class="form-control @error('Tanggal') is-invalid @enderror"
                                    value="{{ old('Tanggal', $penawaran->Tanggal) }}">
                                @error('Tanggal')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-8">
                                <label for="NamaPelanggan" class="form-label fw-semibold">Nama Pelanggan</label>
                                <select name="NamaPelanggan" id="NamaPelanggan"
                                    class="form-select select2 @error('NamaPelanggan') is-invalid @enderror"
                                    data-placeholder="Pilih Pelanggan">
                                    <option value="">Pilih Pelanggan</option>
                                    @foreach ($customer as $cust)
                                        <option value="{{ $cust->id }}"
                                            {{ old('NamaPelanggan', $penawaran->NamaPelanggan) == $cust->id ? 'selected' : '' }}>
                                            {{ $cust->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('NamaPelanggan')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="Keterangan" class="form-label fw-semibold">Keterangan</label>
                                <textarea name="Keterangan" id="Keterangan" rows="2"
                                    class="form-control @error('Keterangan') is-invalid @enderror" placeholder="Keterangan tambahan">{{ old('Keterangan', $penawaran->Keterangan) }}</textarea>
                                @error('Keterangan')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="mb-4">
                        <h5 class="mb-3 fw-bold">Detail Produk</h5>
                        <div class="table-responsive mb-2">
                            <table class="table table-sm table-hover align-middle border rounded-1" id="table-produk">
                                <thead class="text-center table-light align-middle">
                                    <tr>
                                        <th style="width: 28%">Produk</th>
                                        <th style="width: 10%">Harga</th>
                                        <th style="width: 15%">Harga Yang Ditawarkan</th>
                                        <th style="width: 11%">Diskon</th>
                                        <th style="width: 10%">Jenis</th>
                                        <th style="width: 15%">Total</th>
                                        <th style="width: 7%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse (old('IdProduk', $penawaran->details->pluck('IdProduk')->toArray()) as $i => $idProduk)
                                        @php
                                            $produkSelected = $produk->firstWhere(
                                                'id',
                                                old('IdProduk.' . $i, $penawaran->details[$i]->IdProduk ?? null),
                                            );
                                            $hargaAsli = $produkSelected?->HargaNormal ?? 0;
                                            $hargaTawaran = old('Harga.' . $i, $penawaran->details[$i]->Harga ?? 0);
                                            $diskon = old('Diskon.' . $i, $penawaran->details[$i]->Diskon ?? 0);
                                            $jenisDiskon = old(
                                                'JenisDiskon.' . $i,
                                                $penawaran->details[$i]->JenisDiskon ?? 'Rp',
                                            );
                                            $subtotal = old('Subtotal.' . $i, $penawaran->details[$i]->Subtotal ?? 0);
                                        @endphp
                                        <tr>
                                            <td>
                                                <select name="IdProduk[]" class="form-select produk-select" required>
                                                    <option value="">Pilih Produk</option>
                                                    @foreach ($produk as $p)
                                                        <option value="{{ $p->id }}"
                                                            data-harga="{{ $p->HargaNormal }}"
                                                            {{ $idProduk == $p->id ? 'selected' : '' }}>
                                                            {{ $p->Nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="HargaAsli[]"
                                                    class="form-control harga-asli-input text-end"
                                                    value="{{ number_format($hargaAsli, 0, ',', '.') }}" readonly>
                                            </td>
                                            <td>
                                                <input type="text" name="Harga[]"
                                                    class="form-control harga-input text-end"
                                                    value="{{ number_format($hargaTawaran, 0, ',', '.') }}" required
                                                    autocomplete="off">
                                            </td>
                                            <td>
                                                <input type="text" name="Diskon[]"
                                                    class="form-control diskon-input text-end"
                                                    value="{{ number_format($diskon, 0, ',', '.') }}" min="0"
                                                    autocomplete="off">
                                            </td>
                                            <td>
                                                <select name="JenisDiskon[]" class="form-select jenis-diskon-input">
                                                    <option value="Rp" {{ $jenisDiskon == 'Rp' ? 'selected' : '' }}>Rp
                                                    </option>
                                                    <option value="Persen"
                                                        {{ $jenisDiskon == 'Persen' ? 'selected' : '' }}>%</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="Subtotal[]"
                                                    class="form-control total-input text-end"
                                                    value="{{ number_format($subtotal, 0, ',', '.') }}" readonly>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-link text-danger p-0 btn-remove"
                                                    title="Hapus Baris">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td>
                                                <select name="IdProduk[]" class="form-select produk-select" required>
                                                    <option value="">Pilih Produk</option>
                                                    @foreach ($produk as $p)
                                                        <option value="{{ $p->id }}"
                                                            data-harga="{{ $p->HargaNormal }}">
                                                            {{ $p->Nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="HargaAsli[]"
                                                    class="form-control harga-asli-input text-end" value="0"
                                                    readonly>
                                            </td>
                                            <td>
                                                <input type="text" name="Harga[]"
                                                    class="form-control harga-input text-end" value="0" required
                                                    autocomplete="off">
                                            </td>
                                            <td>
                                                <input type="text" name="Diskon[]"
                                                    class="form-control diskon-input text-end" value="0"
                                                    min="0" autocomplete="off">
                                            </td>
                                            <td>
                                                <select name="JenisDiskon[]" class="form-select jenis-diskon-input">
                                                    <option value="Rp">Rp</option>
                                                    <option value="Persen">%</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="Subtotal[]"
                                                    class="form-control total-input text-end" value="0" readonly>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-link text-danger p-0 btn-remove"
                                                    title="Hapus Baris">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforelse
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
                                <label for="Total" class="form-label fw-semibold">Total Penawaran</label>
                                <input type="text" name="Total" id="Total"
                                    class="form-control text-end fw-bold"
                                    value="{{ number_format(old('Total', $penawaran->Total), 0, ',', '.') }}" readonly>
                            </div>
                        </div>

                        <div class="col-12 text-end mt-4">
                            <a href="{{ route('penawaran-harga.index') }}" class="btn btn-secondary me-2">
                                <i class="fa fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Update Penawaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Script dinamis --}}
    <script>
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

        function unformatRupiah(str) {
            if (!str) return 0;
            let unformat = (str + '').replace(/\./g, '').replace(',', '.');
            return parseFloat(unformat);
        }

        function hitungTotalBaris(row) {
            // Harga asli diambil dari kolom harga-asli-input, yang readonly dan berisi harga produk asli
            let jumlah = 1; // HargaAsli kolom bukan jumlah qty, jadi 1 (readonly harga asli)
            let harga = unformatRupiah(row.querySelector('.harga-input').value) || 0;
            let diskon = unformatRupiah(row.querySelector('.diskon-input').value) || 0;
            let jenisDiskon = row.querySelector('.jenis-diskon-input').value;

            let hargaSetelahDiskon = harga;
            if (jenisDiskon === 'Rp') {
                hargaSetelahDiskon -= diskon;
            } else if (jenisDiskon === 'Persen') {
                hargaSetelahDiskon -= harga * (diskon / 100);
            }

            hargaSetelahDiskon = hargaSetelahDiskon < 0 ? 0 : hargaSetelahDiskon;
            let total = jumlah * hargaSetelahDiskon;
            row.querySelector('.total-input').value = formatRupiah(total.toFixed(0));
        }

        function refreshTotalPenawaran() {
            let totalPenawaran = 0;
            document.querySelectorAll('#table-produk .total-input').forEach(function(input) {
                let val = (input.value || "").replaceAll('.', '').replace(',', '.');
                totalPenawaran += parseFloat(val) || 0;
            });
            document.getElementById('Total').value = formatRupiah(totalPenawaran.toString());
        }

        // Tambah baris baru
        document.getElementById('btn-add').addEventListener('click', function() {
            const tbody = document.querySelector('#table-produk tbody');
            const row = tbody.rows[0].cloneNode(true);

            // Bersihkan value input
            row.querySelectorAll('input').forEach(i => {
                if (i.type === 'number' || i.type === 'text') {
                    if (i.classList.contains('harga-asli-input')) {
                        i.value = '0';
                    } else if (i.classList.contains('harga-input')) {
                        i.value = '0';
                    } else if (i.classList.contains('total-input')) {
                        i.value = '0';
                    } else if (i.classList.contains('diskon-input')) {
                        i.value = '0';
                    }
                }
            });
            row.querySelector('.produk-select').selectedIndex = 0;
            row.querySelector('.jenis-diskon-input').selectedIndex = 0;
            tbody.appendChild(row);
        });

        // Remove baris produk
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-remove') || (e.target.parentElement && e.target.parentElement
                    .classList.contains('btn-remove'))) {
                const tr = e.target.closest('tr');
                const tbody = tr.closest('tbody');
                if (tbody.rows.length > 1) {
                    tr.remove();
                    refreshTotalPenawaran();
                }
            }
        });

        // Event: Saat memilih produk, set harga default dari data-harga pada kolom HargaAsli dan kolom harga yang ditawarkan
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('produk-select')) {
                const selected = e.target.options[e.target.selectedIndex];
                const harga = selected.getAttribute('data-harga') || 0;
                const tr = e.target.closest('tr');

                // Set HargaAsli kolom (readonly)
                tr.querySelector('.harga-asli-input').value = formatRupiah(harga);

                // Set harga yang ditawarkan juga default sama dengan harga asli
                tr.querySelector('.harga-input').value = formatRupiah(harga);

                hitungTotalBaris(tr);
                refreshTotalPenawaran();
            }
        });

        // Event: Otomatis pakai titik pada harga yang ditawarkan & diskon saat diketik
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('harga-input') || e.target.classList.contains('diskon-input')) {
                const caretPosition = e.target.selectionStart;
                const oldLength = e.target.value.length;

                // Remove all chars except number
                let value = e.target.value.replace(/[^0-9]/g, '');

                if (value.length === 0) {
                    e.target.value = "";
                } else {
                    e.target.value = formatRupiah(value);
                }

                const newLength = e.target.value.length;
                // Maintain caret position while typing
                if (caretPosition !== null && caretPosition === oldLength) {
                    e.target.setSelectionRange(newLength, newLength);
                }

                // setelah format, tetap hitung total & refresh
                const tr = e.target.closest('tr');
                hitungTotalBaris(tr);
                refreshTotalPenawaran();
            }
        });

        // Event: Saat harga/diskon/jenis diskon berubah, hitung total baris & summary
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('jenis-diskon-input')) {
                const tr = e.target.closest('tr');
                hitungTotalBaris(tr);
                refreshTotalPenawaran();
            }
        });

        // Hitung total awal saat reload
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('#table-produk tbody tr').forEach(function(row) {
                hitungTotalBaris(row);
            });
            refreshTotalPenawaran();
        });
    </script>
@endsection
if (e.target.classList.contains('harga-input')) {
e.target.value = formatRupiah(e.target.value.replace(/\./g, ''));
}
}, true);
*/

// Hitung total awal saat reload
document.addEventListener('DOMContentLoaded', function() {
document.querySelectorAll('#table-produk tbody tr').forEach(function(row) {
hitungTotalBaris(row);
});
refreshTotalPenawaran();
});
</script>
@endsection
