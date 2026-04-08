@extends('adminlte::page')

@section('title', 'Nueva Asignación')

@section('content_header')
    <h1>Nueva Asignación</h1>
@stop

@section('content')

<div class="card">
<div class="card-body">

<form action="{{ route('asignaciones.store') }}" method="POST">
    @csrf

    <div class="row">

        <div class="col-md-6">
            <label>Equipo</label>
            <select name="equipo_id" class="form-control" required>
                <option value="">Seleccione</option>
                @foreach($equipos as $equipo)
                    <option value="{{ $equipo->id }}">
                        {{ $equipo->marca }} {{ $equipo->modelo }}
                        ({{ $equipo->numero_serie }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label>Colaborador</label>
            <select name="colaborador_id" class="form-control" required>
                <option value="">Seleccione</option>
                @foreach($colaboradores as $col)
                    <option value="{{ $col->id }}">
                        {{ $col->nombre }} {{ $col->apellido_paterno }}
                    </option>
                @endforeach
            </select>
        </div>

    </div>

    <br>

    <div class="form-group">
        <label>Fecha de Asignación</label>
        <input type="date" name="fecha_asignacion"
               class="form-control" required>
    </div>

    <div class="form-group">
        <label>Observaciones</label>
        <textarea name="observaciones"
                  class="form-control"></textarea>
    </div>

    <br>

    <button class="btn btn-success">Asignar</button>

</form>

</div>
</div>

@stop