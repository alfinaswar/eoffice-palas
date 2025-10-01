@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Master Kantor</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('master-kantor.index') }}">Kantor</a></li>
                    <li class="breadcrumb-item active">Tambah Kantor</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <h4 class="card-title mb-0">Formulir Tambah Kantor</h4>
                    <p class="card-text mb-0">
                        Silakan isi data kantor baru di bawah ini.
                    </p>
                </div>
                <div class="card-body">
                    <form action="{{ route('master-kantor.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label for="Nama" class="form-label"><strong>Nama</strong></label>
                                <input type="text" name="Nama" class="form-control @error('Nama') is-invalid @enderror"
                                    id="Nama" placeholder="Nama" value="{{ old('Nama') }}">
                                @error('Nama')
                                    <div class="text-danger mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="Kode" class="form-label"><strong>Kode</strong></label>
                                <input type="text" name="Kode" class="form-control @error('Kode') is-invalid @enderror"
                                    id="Kode" placeholder="Kode" value="{{ old('Kode') }}">
                                @error('Kode')
                                    <div class="text-danger mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="Alamat" class="form-label"><strong>Alamat</strong></label>
                                <textarea name="Alamat" class="form-control @error('Alamat') is-invalid @enderror"
                                    id="Alamat" placeholder="Alamat">{{ old('Alamat') }}</textarea>
                                @error('Alamat')
                                    <div class="text-danger mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-12 text-end mt-3">
                                <a href="{{ route('master-kantor.index') }}" class="btn btn-secondary me-2">
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
