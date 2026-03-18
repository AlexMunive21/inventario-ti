@extends('adminlte::page')

@section('title', 'Editar Área')

@section('content_header')
    <h1>Editar Área</h1>
@stop

@section('content')

<form action="{{ route('areas.update', $area) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label>Nombre</label>
        <input type="text" name="nombre" class="form-control" value="{{ $area->nombre }}" required>
    </div>

    <div class="form-group">
        <label>Descripción</label>
        <input type="text" name="descripcion" class="form-control" value="{{ $area->descripcion }}">
    </div>

    <button type="submit" class="btn btn-success mt-2">Actualizar</button>
    <a href="{{ route('areas.index') }}" class="btn btn-secondary mt-2">Cancelar</a>
</form>

@stop