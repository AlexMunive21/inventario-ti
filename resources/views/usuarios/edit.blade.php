@extends('adminlte::page')

@section('title', 'Editar Usuario')

@section('content_header')
<h1>Editar Usuario</h1>
@stop

@section('content')

<div class="card">
<div class="card-body">

<form action="{{ route('usuarios.update',$usuario->id) }}" method="POST">
@csrf
@method('PUT')

<div class="row">

<div class="col-md-6 mb-3">
<label>Nombre</label>
<input type="text" name="name"
value="{{ $usuario->name }}"
class="form-control" required>
</div>

<div class="col-md-6 mb-3">
<label>Email</label>
<input type="email" name="email"
value="{{ $usuario->email }}"
class="form-control" required>
</div>

<div class="col-md-6 mb-3">
<label>Contraseña (opcional)</label>
<input type="password" name="password" class="form-control">
</div>

<div class="col-md-6 mb-3">
<label>Rol</label>
<select name="role" class="form-control">

@foreach($roles as $role)
<option value="{{ $role->name }}"
    {{ $usuario->roles->first()?->name == $role->name ? 'selected' : '' }}>
    {{ $role->name }}
</option>
@endforeach

</select>
</div>

</div>

<button class="btn btn-primary">Actualizar</button>

</form>

</div>
</div>

@stop