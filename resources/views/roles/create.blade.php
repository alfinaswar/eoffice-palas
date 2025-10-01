@extends('layouts.app')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col">
                <h3 class="page-title">Manajemen Role</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Role</a></li>
                    <li class="breadcrumb-item active">Buat Role Baru</li>
                </ul>
            </div>

        </div>
    </div>

    @if (count($errors) > 0)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Terjadi kesalahan!</strong> Silakan periksa input Anda.<br><br>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <h4 class="card-title mb-0">Formulir Role Baru</h4>
                    <p class="card-text mb-0">
                        Silakan isi data role dan pilih permission yang diinginkan.
                    </p>
                </div>
                <div class="card-body">
                    {!! Form::open(['route' => 'roles.store', 'method' => 'POST']) !!}
                    <div class="mb-3">
                        <label for="name" class="form-label"><strong>Nama Role</strong></label>
                        {!! Form::text('name', null, ['placeholder' => 'Nama Role', 'class' => 'form-control', 'id' => 'name']) !!}
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><strong>Permission</strong></label>
                        <div class="row">
                            @foreach($permission as $value)
                                <div class="col-md-6 mb-2">
                                    <div class="form-check">
                                        {{ Form::checkbox('permission[]', $value->id, false, ['class' => 'form-check-input', 'id' => 'perm_' . $value->id]) }}
                                        <label class="form-check-label" for="perm_{{ $value->id }}">{{ $value->name }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
