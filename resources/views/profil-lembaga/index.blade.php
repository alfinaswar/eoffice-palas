@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Profil Lembaga</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Profil Lembaga</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <h4 class="card-title mb-0">Form Profil Lembaga</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('profil-lembaga.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nama_perusahaan" class="form-label"><strong>Nama Perusahaan</strong></label>
                                <input type="text" name="nama_perusahaan"
                                    class="form-control @error('nama_perusahaan') is-invalid @enderror" id="nama_perusahaan"
                                    placeholder="Nama Perusahaan"
                                    value="{{ old('nama_perusahaan', $data?->nama_perusahaan) }}">
                                @error('nama_perusahaan')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="telepon" class="form-label"><strong>Telepon</strong></label>
                                <input type="text" name="telepon"
                                    class="form-control @error('telepon') is-invalid @enderror" id="telepon"
                                    placeholder="Telepon" value="{{ old('telepon', $data?->telepon) }}">
                                @error('telepon')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="alamat" class="form-label"><strong>Alamat</strong></label>
                                <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" id="alamat" placeholder="Alamat">{{ old('alamat', $data?->alamat) }}</textarea>
                                @error('alamat')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="kota" class="form-label"><strong>Kota</strong></label>
                                <input type="text" name="kota"
                                    class="form-control @error('kota') is-invalid @enderror" id="kota"
                                    placeholder="Kota" value="{{ old('kota', $data?->kota) }}">
                                @error('kota')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="provinsi" class="form-label"><strong>Provinsi</strong></label>
                                <input type="text" name="provinsi"
                                    class="form-control @error('provinsi') is-invalid @enderror" id="provinsi"
                                    placeholder="Provinsi" value="{{ old('provinsi', $data?->provinsi) }}">
                                @error('provinsi')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="email" class="form-label"><strong>Email</strong></label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror" id="email"
                                    placeholder="Email" value="{{ old('email', $data?->email) }}">
                                @error('email')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="website" class="form-label"><strong>Website</strong></label>
                                <input type="text" name="website"
                                    class="form-control @error('website') is-invalid @enderror" id="website"
                                    placeholder="Website" value="{{ old('website', $data?->website) }}">
                                @error('website')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="logo" class="form-label"><strong>Logo</strong></label>
                                <input type="file" name="logo"
                                    class="form-control @error('logo') is-invalid @enderror" id="logo"
                                    accept="image/*">
                                @error('logo')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                                @if ($data && $data->logo)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $data->logo) }}" alt="Logo" height="70">
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-12">
                                <label for="deskripsi" class="form-label"><strong>Deskripsi</strong></label>
                                <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi"
                                    placeholder="Deskripsi">{{ old('deskripsi', $data?->deskripsi) }}</textarea>
                                @error('deskripsi')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 text-end mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Simpan
                                </button>
                            </div>
                        </div>
                    </form>
                    {{-- Tidak perlu lagi tabel data saat ini, data sudah muncul di form langsung --}}
                </div>
            </div>
        </div>
    </div>
@endsection
