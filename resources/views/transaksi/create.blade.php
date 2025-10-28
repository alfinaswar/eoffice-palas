@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Formulir Transaksi</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('transaksi.index') }}">Transaksi</a></li>
                    <li class="breadcrumb-item active">Tambah Transaksi</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <h4 class="card-title mb-0">Formulir Tambah Transaksi</h4>
                    <p class="card-text mb-0">
                        Silakan isi data transaksi di bawah ini.
                    </p>
                </div>
                <div class="card-body">
                    <form action="{{ route('transaksi.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">

                            <div class="col-md-4">
                                <label for="TanggalTransaksi" class="form-label"><strong>Tanggal Transaksi</strong></label>
                                <input type="date" name="TanggalTransaksi"
                                    class="form-control @error('TanggalTransaksi') is-invalid @enderror"
                                    id="TanggalTransaksi" value="{{ old('TanggalTransaksi', now()->format('Y-m-d')) }}">
                                @error('TanggalTransaksi')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="IdPelanggan" class="form-label"><strong>Pelanggan</strong></label>
                                <select name="IdPelanggan" id="IdPelanggan"
                                    class="form-control @error('IdPelanggan') is-invalid @enderror">
                                    <option value="">Pilih Pelanggan</option>
                                    @if (isset($booking) && $booking->getCustomer)
                                        <option value="{{ $booking->getCustomer->id }}" selected>
                                            {{ $booking->getCustomer->name }}
                                        </option>
                                    @endif
                                </select>
                                @error('IdPelanggan')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="IdPetugas" class="form-label"><strong>Petugas</strong></label>
                                <select name="IdPetugas" id="IdPetugas"
                                    class="form-control @error('IdPetugas') is-invalid @enderror">
                                    <option value="">Pilih Petugas</option>
                                    <option value="{{ Auth::user()->id }}" selected>{{ Auth::user()->name }}</option>
                                </select>
                                @error('IdPetugas')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="JenisTransaksi" class="form-label"><strong>Jenis Transaksi</strong></label>
                                <select name="JenisTransaksi" id="JenisTransaksi"
                                    class="form-control @error('JenisTransaksi') is-invalid @enderror">
                                    <option value="">Pilih Jenis Transaksi</option>
                                    <option value="Tunai" {{ old('JenisTransaksi') == 'Cash' ? 'selected' : '' }}>Tunai
                                    </option>
                                    <option value="Kredit" {{ old('JenisTransaksi') == 'Kredit' ? 'selected' : '' }}>Kredit
                                    </option>
                                </select>
                                @error('JenisTransaksi')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4" id="durasiKreditContainer" style="display: none;">
                                <label for="DurasiAngsuran" class="form-label"><strong>Durasi Pembayaran</strong></label>
                                <select name="DurasiAngsuran" id="DurasiAngsuran"
                                    class="form-control @error('DurasiAngsuran') is-invalid @enderror">
                                    <option value="">Pilih Durasi Pembayaran</option>
                                    @if (isset($angsuran) && count($angsuran) > 0)
                                        @foreach ($angsuran as $item)
                                            <option value="{{ $item->JumlahPembayaran }}"
                                                {{ old('DurasiAngsuran') == $item->id ? 'selected' : '' }}>
                                                {{ $item->JumlahPembayaran }} Bulan - {{ $item->KonversiTahun }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('DurasiAngsuran')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    function toggleDurasi() {
                                        const jenis = document.getElementById('JenisTransaksi').value;
                                        const durasiDiv = document.getElementById('durasiKreditContainer');
                                        if (jenis === 'Kredit') {
                                            durasiDiv.style.display = 'block';
                                        } else {
                                            durasiDiv.style.display = 'none';
                                            // Optionally reset value when hidden
                                            document.getElementById('DurasiAngsuran').value = '';
                                        }
                                    }
                                    document.getElementById('JenisTransaksi').addEventListener('change', toggleDurasi);
                                    // On load, set correct state
                                    toggleDurasi();
                                });
                            </script>
                            <div class="col-md-4">
                                <label for="TotalHarga" class="form-label"><strong>Harga</strong></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" name="TotalHarga"
                                        class="form-control @error('TotalHarga') is-invalid @enderror" id="TotalHarga"
                                        placeholder="Total Harga"
                                        value="{{ old('TotalHarga', isset($booking->getPenawaran->Total) ? number_format($booking->getPenawaran->Total, 0, ',', '.') : '') }}"
                                        autocomplete="off" readonly>
                                </div>
                                @error('TotalHarga')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="BiayaBooking" class="form-label"><strong>Biaya Booking</strong></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" name="BiayaBooking" readonly
                                        class="form-control @error('BiayaBooking') is-invalid @enderror" id="BiayaBooking"
                                        placeholder="Total Harga" value="{{ old('BiayaBooking', $booking->Total ?? '') }}"
                                        autocomplete="off">
                                </div>
                                @error('BiayaBooking')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- <div class="col-md-4">
                                <label for="UangMuka" class="form-label"><strong>Uang Muka</strong></label>
                                <input type="number" step="1" name="UangMuka"
                                    class="form-control @error('UangMuka') is-invalid @enderror" id="UangMuka"
                                    placeholder="Uang Muka" value="{{ old('UangMuka') }}">
                                @error('UangMuka')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div> --}}
                            {{-- <div class="col-md-4">
                                <label for="SisaBayar" class="form-label"><strong>Sisa Bayar</strong></label>
                                <input type="number" step="1" name="SisaBayar"
                                    class="form-control @error('SisaBayar') is-invalid @enderror" id="SisaBayar"
                                    placeholder="Sisa Bayar" value="{{ old('SisaBayar') }}">
                                @error('SisaBayar')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div> --}}

                            <div class="col-md-12">
                                <label for="Keterangan" class="form-label"><strong>Keterangan</strong></label>
                                <textarea name="Keterangan" class="form-control @error('Keterangan') is-invalid @enderror" id="Keterangan"
                                    placeholder="Keterangan" rows="3">{{ old('Keterangan') }}</textarea>
                                @error('Keterangan')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 text-end mt-3">
                                <a href="{{ route('transaksi.index') }}" class="btn btn-secondary me-2">
                                    <i class="fa fa-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Simpan
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hargaInput = document.getElementById('TotalHarga');
            hargaInput.addEventListener('input', function(e) {
                let value = this.value.replace(/\D/g, "");
                if (value) {
                    this.value = parseInt(value).toLocaleString('id-ID');
                } else {
                    this.value = "";
                }
            });

            // Optionally, on form submit remove formatting if needed
            hargaInput.form && hargaInput.form.addEventListener('submit', function() {
                hargaInput.value = hargaInput.value.replace(/\./g, "");
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bookingInput = document.getElementById('BiayaBooking');

            function formatRupiah(angka, prefix) {
                let number_string = angka.replace(/[^,\d]/g, '').toString(),
                    split = number_string.split(','),
                    sisa = split[0].length % 3,
                    rupiah = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                    let separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
                return prefix === undefined ? rupiah : (rupiah ? prefix + rupiah : '');
            }

            // Set initial value if any
            if (bookingInput.value) {
                bookingInput.value = formatRupiah(bookingInput.value, '');
            }

            bookingInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\./g, '').replace(/[^0-9]/g, '');
                e.target.value = formatRupiah(value, '');
            });

            // Optionally: on form submit, remove thousand separator before send
            bookingInput.form && bookingInput.form.addEventListener('submit', function() {
                bookingInput.value = bookingInput.value.replace(/\./g, '').replace(/[^0-9]/g, '');
            });
        });
    </script>
@endpush
