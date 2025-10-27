@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Master Angsuran</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('master-angsuran.index') }}">Master Angsuran</a></li>
                    <li class="breadcrumb-item active">Tambah Angsuran</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <h4 class="card-title mb-0">Form Tambah Angsuran</h4>

                </div>
                <div class="card-body">
                    <form action="{{ route('master-angsuran.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="JumlahPembayaran" class="form-label"><strong>Jumlah Pembayaran
                                        (Bulan)</strong></label>
                                <input type="number" name="JumlahPembayaran"
                                    class="form-control @error('JumlahPembayaran') is-invalid @enderror"
                                    id="JumlahPembayaran" placeholder="Contoh: 12" min="1"
                                    value="{{ old('JumlahPembayaran') }}">
                                @error('JumlahPembayaran')
                                    <div class="text-danger mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="KonversiTahun" class="form-label"><strong>Konversi Tahun</strong></label>
                                <input type="text" name="KonversiTahun"
                                    class="form-control @error('KonversiTahun') is-invalid @enderror" id="KonversiTahun"
                                    placeholder="Contoh: 1 Tahun" value="{{ old('KonversiTahun') }}" readonly>
                                @error('KonversiTahun')
                                    <div class="text-danger mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="Bunga" class="form-label"><strong>Bunga (%)</strong></label>
                                <input type="number" name="Bunga"
                                    class="form-control @error('Bunga') is-invalid @enderror" id="Bunga" step="0.01"
                                    placeholder="Contoh: 10.5" value="{{ old('Bunga', 0) }}">
                                @error('Bunga')
                                    <div class="text-danger mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12 text-end mt-3">
                                <a href="{{ route('master-angsuran.index') }}" class="btn btn-secondary me-2">
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
        function bulanKeTahun(bulan) {
            bulan = parseInt(bulan);
            if (isNaN(bulan) || bulan < 1) return '';
            let tahun = Math.floor(bulan / 12);
            let sisabulan = bulan % 12;
            let str = '';
            if (tahun > 0) {
                str += tahun + ' Tahun';
            }
            if (sisabulan > 0) {
                if (str != '') str += ' ';
                str += sisabulan + ' Bulan';
            }
            return str;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const inputBulan = document.getElementById('JumlahPembayaran');
            const inputKonversi = document.getElementById('KonversiTahun');

            function updateKonversi() {
                inputKonversi.value = bulanKeTahun(inputBulan.value);
            }
            inputBulan.addEventListener('input', updateKonversi);
            updateKonversi();
        });
    </script>
@endpush
