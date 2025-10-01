@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Master Grade</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('master-grade.index') }}">Master Grade</a></li>
                    <li class="breadcrumb-item active">Tambah Grade</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <h4 class="card-title mb-0">Formulir Tambah Grade</h4>
                    <p class="card-text mb-0">
                        Silakan isi data grade baru di bawah ini.
                    </p>
                </div>
                <div class="card-body">
                    <form action="{{ route('master-grade.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label for="Nama" class="form-label"><strong>Nama</strong></label>
                                <input type="text" name="Nama" class="form-control @error('Nama') is-invalid @enderror"
                                    id="Nama" placeholder="Nama Jenis Produk" value="{{ old('Nama') }}">
                                @error('Nama')
                                    <div class="text-danger mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="Keterangan" class="form-label"><strong>Keterangan</strong></label>
                                <input type="text" name="Keterangan" class="form-control @error('Keterangan') is-invalid @enderror"
                                    id="Keterangan" placeholder="Keterangan" value="{{ old('Keterangan') }}">
                                @error('Keterangan')
                                    <div class="text-danger mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-12 text-end mt-3">
                                <a href="{{ route('master-jenis-produk.index') }}" class="btn btn-secondary me-2">
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
