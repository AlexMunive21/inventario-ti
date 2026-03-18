@extends('adminlte::page')

@section('title', 'Nueva Asignación')

@section('content_header')
    <h1>Nueva Asignación</h1>
@stop

@section('content')

<form action="{{ route('asignaciones-celulares.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label>Celular</label>
        <select name="celular_id" class="form-control" required>
            <option value="">Seleccione</option>
            @foreach($celulares as $celular)
                <option value="{{ $celular->id }}">
                    {{ $celular->marca }} - {{ $celular->modelo }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Colaborador</label>
        <select name="colaborador_id" class="form-control" required>
            <option value="">Seleccione</option>
            @foreach($colaboradores as $colaborador)
                <option value="{{ $colaborador->id }}">
                    {{ $colaborador->nombre }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Fecha Asignación</label>
        <input type="date" name="fecha_asignacion" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Observaciones</label>
        <textarea name="observaciones" class="form-control"></textarea>
    </div>

    <button class="btn btn-primary">Guardar</button>
    <a href="{{ route('asignaciones-celulares.index') }}" class="btn btn-secondary">Cancelar</a>

</form>

@stop