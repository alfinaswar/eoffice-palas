@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>Edit Role</h2>
                </div>
                <div class="card-body">
                    <a class="btn btn-secondary mb-3" href="{{ route('roles.index') }}">Kembali</a>

                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> Ada beberapa masalah dengan input Anda.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {!! Form::model($role, ['method' => 'PATCH', 'route' => ['roles.update', $role->id]]) !!}
                    <div class="form-group mb-3">
                        <label for="name"><strong>Nama Role:</strong></label>
                        {!! Form::text('name', null, array('placeholder' => 'Nama Role', 'class' => 'form-control', 'id' => 'name')) !!}
                    </div>
                    <div class="form-group mb-3">
                        <label><strong>Permission:</strong></label>
                        <div class="row">
                            @foreach($permission as $value)
                                <div class="col-md-4">
                                    <div class="form-check">
                                        {{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, ['class' => 'form-check-input', 'id' => 'perm_' . $value->id]) }}
                                        <label class="form-check-label" for="perm_{{ $value->id }}">{{ $value->name }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>

        </div>
    </div>

@endsection
