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
                                    @if(isset($booking) && $booking->getCustomer)
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
                                    <option value="Tunai" {{ old('JenisTransaksi') == 'Tunai' ? 'selected' : '' }}>Tunai
                                    </option>
                                    <option value="Kredit" {{ old('JenisTransaksi') == 'Kredit' ? 'selected' : '' }}>Kredit
                                    </option>
                                </select>
                                @error('JenisTransaksi')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="TotalHarga" class="form-label"><strong>Total Harga</strong></label>
                                <input type="number" step="1" name="TotalHarga"
                                    class="form-control @error('TotalHarga') is-invalid @enderror" id="TotalHarga"
                                    placeholder="Total Harga" value="{{ old('TotalHarga', $booking->Total ?? '') }}">
                                @error('TotalHarga')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="UangMuka" class="form-label"><strong>Uang Muka</strong></label>
                                <input type="number" step="1" name="UangMuka"
                                    class="form-control @error('UangMuka') is-invalid @enderror" id="UangMuka"
                                    placeholder="Uang Muka" value="{{ old('UangMuka') }}">
                                @error('UangMuka')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="SisaBayar" class="form-label"><strong>Sisa Bayar</strong></label>
                                <input type="number" step="1" name="SisaBayar"
                                    class="form-control @error('SisaBayar') is-invalid @enderror" id="SisaBayar"
                                    placeholder="Sisa Bayar" value="{{ old('SisaBayar') }}">
                                @error('SisaBayar')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="Keterangan" class="form-label"><strong>Keterangan</strong></label>
                                <textarea name="Keterangan" class="form-control @error('Keterangan') is-invalid @enderror"
                                    id="Keterangan" placeholder="Keterangan" rows="3">{{ old('Keterangan') }}</textarea>
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