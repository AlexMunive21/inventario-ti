@extends('adminlte::page')

@section('title', 'Editar Ciudad')

@section('content_header')
    <h1>Editar Ciudad</h1>
@stop

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Ups!</strong> Hay algunos errores:<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-body">

        <form action="{{ route('ciudades.update', $ciudad) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control"
                       value="{{ $ciudad->nombre }}" required>
            </div>

            <div class="form-group">
                <label>Estado</label>
                <input type="text" name="estado" class="form-control"
                       value="{{ $ciudad->estado }}">
            </div>

            <div class="form-group">
                <label>Activo</label>
                <select name="activo" class="form-control">
                    <option value="1" {{ $ciudad->activo ? 'selected' : '' }}>Sí</option>
                    <option value="0" {{ !$ciudad->activo ? 'selected' : '' }}>No</option>
                </select>
            </div>

            <br>

            <button type="submit" class="btn btn-success">
                Actualizar
            </button>

            <a href="{{ route('ciudades.index') }}" class="btn btn-secondary">
                Cancelar
            </a>

        </form>

    </div>
</div>

@stop