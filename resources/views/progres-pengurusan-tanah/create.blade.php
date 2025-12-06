@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Tambah Progres Pengurusan Tanah</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('pengurusan-tanah.index') }}">Progres Pengurusan
                            Tanah</a></li>
                    <li class="breadcrumb-item active">Tambah Progres</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-12">
            {{-- FORM START --}}
            <form action="{{ route('pengurusan-tanah.store') }}" method="POST">
                @csrf

                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h4 class="card-title mb-0">Formulir Tambah Progres Pengurusan Tanah</h4>
                        <small>Silakan isi data progres pengurusan tanah di bawah ini.</small>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="Deskripsi" class="form-label">Deskripsi</label>
                                <input type="text" name="Deskripsi" id="Deskripsi"
                                    class="form-control @error('Deskripsi') is-invalid @enderror" placeholder="Deskripsi"
                                    value="{{ old('Deskripsi') }}">
                                @error('Deskripsi')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Kode Proyek --}}
                            <div class="col-md-6">
                                <label for="KodeProyek" class="form-label"><strong>Kode Proyek</strong></label>
                                <select name="KodeProyek" id="KodeProyek"
                                    class="form-select select2 @error('KodeProyek') is-invalid @enderror">
                                    <option value="">-- Pilih Kode Proyek --</option>
                                    @foreach ($proyeks as $proyek)
                                        <option value="{{ $proyek->id }}"
                                            {{ old('KodeProyek') == $proyek->KodeProyek ? 'selected' : '' }}>
                                            {{ $proyek->NamaProyek ?? '' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('KodeProyek')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>



                            {{-- Tanggal --}}
                            <div class="col-md-6">
                                <label for="Tanggal" class="form-label"><strong>Tanggal</strong></label>
                                <input type="date" name="Tanggal" id="Tanggal"
                                    class="form-control @error('Tanggal') is-invalid @enderror"
                                    value="{{ old('Tanggal', \Carbon\Carbon::now()->toDateString()) }}">
                                @error('Tanggal')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Legal --}}
                            <div class="col-md-6">
                                <label for="Legal" class="form-label"><strong>Legal</strong></label>
                                <input type="text" name="Legal" id="Legal"
                                    class="form-control @error('Legal') is-invalid @enderror rupiah-input"
                                    value="{{ old('Legal') }}" placeholder="Legal">
                                @error('Legal')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="col-md-6">
                                <label for="NamaBank" class="form-label"><strong>Bank</strong></label>
                                <select name="NamaBank" id="NamaBank"
                                    class="form-select select2 @error('NamaBank') is-invalid @enderror">
                                    <option value="">-- Pilih Bank --</option>
                                    @foreach ($bank as $b)
                                        <option value="{{ $b->id }}"
                                            {{ old('NamaBank') == $b->NamaBank ? 'selected' : '' }}>
                                            {{ $b->Nama ?? '' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('NamaBank')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Keterangan --}}
                            <div class="col-md-12">
                                <label for="Keterangan" class="form-label"><strong>Keterangan</strong></label>
                                <textarea name="Keterangan" id="Keterangan" class="form-control @error('Keterangan') is-invalid @enderror"
                                    rows="3" placeholder="Keterangan">{{ old('Keterangan') }}</textarea>
                                @error('Keterangan')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>



                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <a href="{{ route('pengurusan-tanah.index') }}" class="btn btn-secondary me-2">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                    </div>
                </div>
            </form>
            {{-- FORM END --}}
        </div>
    </div>
@endsection
@push('js')
    <script>
        // Rupiah formatting for Legal input
        document.addEventListener('DOMContentLoaded', function() {
            const legalInput = document.getElementById('Legal');
            if (legalInput) {
                legalInput.addEventListener('input', function(e) {
                    let value = this.value.replace(/[^,\d]/g, '').toString();
                    let split = value.split(',');
                    let sisa = split[0].length % 3;
                    let rupiah = split[0].substr(0, sisa);
                    let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                    if (ribuan) {
                        let separator = sisa ? '.' : '';
                        rupiah += separator + ribuan.join('.');
                    }

                    rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
                    this.value = rupiah ? 'Rp ' + rupiah : '';
                });

                // If there is already a value, format it on load
                if (legalInput.value) {
                    let e = new Event('input', {
                        bubbles: true
                    });
                    legalInput.dispatchEvent(e);
                }
            }
        });
    </script>
@endpush
