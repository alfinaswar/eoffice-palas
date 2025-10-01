@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Master Bank</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('master-bank.index') }}">Bank</a></li>
                    <li class="breadcrumb-item active">Edit Bank</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <h4 class="card-title mb-0">Formulir Edit Bank</h4>
                    <p class="card-text mb-0">
                        Silakan ubah data bank di bawah ini.
                    </p>
                </div>
                <div class="card-body">
                    <form action="{{ route('master-bank.update', $bank->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label for="Nama" class="form-label"><strong>Nama</strong></label>
                                <input type="text" name="Nama" class="form-control @error('Nama') is-invalid @enderror"
                                    id="Nama" placeholder="Nama" value="{{ old('Nama', $bank->Nama) }}">
                                @error('Nama')
                                    <div class="text-danger mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="Kode" class="form-label"><strong>Kode</strong></label>
                                <input type="text" name="Kode" class="form-control @error('Kode') is-invalid @enderror"
                                    id="Kode" placeholder="Kode" value="{{ old('Kode', $bank->Kode) }}">
                                @error('Kode')
                                    <div class="text-danger mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="Status" class="form-label"><strong>Status</strong></label>
                                <select name="Status" id="Status"
                                    class="form-control @error('Status') is-invalid @enderror">
                                    <option value="">Pilih Status</option>
                                    <option value="Aktif" {{ old('Status', $bank->Status) == 'Aktif' ? 'selected' : '' }}>
                                        Aktif</option>
                                    <option value="Nonaktif" {{ old('Status', $bank->Status) == 'Nonaktif' ? 'selected' : '' }}>Nonaktif
                                    </option>
                                </select>
                                @error('Status')
                                    <div class="text-danger mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-12 text-end mt-3">
                                <a href="{{ route('master-bank.index') }}" class="btn btn-secondary me-2">
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
