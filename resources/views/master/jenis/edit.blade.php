@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Master Jenis Produk</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('master-jenis-produk.index') }}">Master Jenis Produk</a>
                    </li>
                    <li class="breadcrumb-item active">Edit Jenis Produk</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <h4 class="card-title mb-0">Formulir Edit Jenis Produk</h4>
                    <p class="card-text mb-0">
                        Silakan ubah data jenis produk di bawah ini.
                    </p>
                </div>
                <div class="card-body">
                    <form action="{{ route('master-jenis-produk.update', encrypt($jenis->id)) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label for="Nama" class="form-label"><strong>Nama</strong></label>
                                <input type="text" name="Nama" class="form-control @error('Nama') is-invalid @enderror"
                                    id="Nama" placeholder="Nama Jenis Produk" value="{{ old('Nama', $jenis->Nama) }}">
                                @error('Nama')
                                    <div class="text-danger mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="Keterangan" class="form-label"><strong>Keterangan</strong></label>
                                <input type="text" name="Keterangan"
                                    class="form-control @error('Keterangan') is-invalid @enderror" id="Keterangan"
                                    placeholder="Keterangan" value="{{ old('Keterangan', $jenis->Keterangan) }}">
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
                                    <i class="fa fa-save"></i> Update
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection