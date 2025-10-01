@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Master Proyek</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('master-proyek.index') }}">Proyek</a></li>
                    <li class="breadcrumb-item active">Tambah Proyek</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <h4 class="card-title mb-0">Formulir Tambah Proyek</h4>
                    <p class="card-text mb-0">
                        Silakan isi data proyek baru di bawah ini.
                    </p>
                </div>
                <div class="card-body">
                    <form action="{{ route('master-proyek.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label for="NamaProyek" class="form-label"><strong>Nama Proyek</strong></label>
                                <input type="text" name="NamaProyek"
                                    class="form-control @error('NamaProyek') is-invalid @enderror" id="NamaProyek"
                                    placeholder="Nama Proyek" value="{{ old('NamaProyek') }}">
                                @error('NamaProyek')
                                    <div class="text-danger mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="AlamatProyek" class="form-label"><strong>Alamat Proyek</strong></label>
                                <input type="text" name="AlamatProyek"
                                    class="form-control @error('AlamatProyek') is-invalid @enderror" id="AlamatProyek"
                                    placeholder="Alamat Proyek" value="{{ old('AlamatProyek') }}">
                                @error('AlamatProyek')
                                    <div class="text-danger mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>


                            <div class="col-12 text-end mt-3">
                                <a href="{{ route('master-proyek.index') }}" class="btn btn-secondary me-2">
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