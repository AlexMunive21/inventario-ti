@extends('adminlte::page')

@section('title', 'Nueva Área')

@section('content_header')
    <h1>Nueva Área</h1>
@stop

@section('content')

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('areas.store') }}" method="POST">
    @csrf

    <div class="form-group">
        <label>Nombre</label>
        <input type="text" name="nombre" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Descripción</label>
        <input type="text" name="descripcion" class="form-control">
    </div>

    <button type="submit" class="btn btn-success mt-2">Guardar</button>
    <a href="{{ route('areas.index') }}" class="btn btn-secondary mt-2">Cancelar</a>
</form>

@stop