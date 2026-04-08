@extends('adminlte::page')

@section('title', 'Crear Usuario')

@section('content_header')
<h1>Nuevo Usuario</h1>
@stop

@section('content')

<div class="card">
<div class="card-body">

<form action="{{ route('usuarios.store') }}" method="POST">
@csrf

<div class="row">

<div class="col-md-6 mb-3">
<label>Nombre</label>
<input type="text" name="name" class="form-control" required>
</div>

<div class="col-md-6 mb-3">
<label>Email</label>
<input type="email" name="email" class="form-control" required>
</div>

<div class="col-md-6 mb-3">
<label>Contraseña</label>
<input type="password" name="password" class="form-control" required>
</div>

<div class="col-md-6 mb-3">
<label>Rol</label>
<select name="role" class="form-control" required>
    <option value="">Selecciona</option>
    @foreach($roles as $role)
        <option value="{{ $role->name }}">
            {{ $role->name }}
        </option>
    @endforeach
</select>
</div>

</div>

<button class="btn btn-success">Guardar</button>

</form>

</div>
</div>

@stop