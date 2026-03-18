@extends('adminlte::page')

@section('title', 'Nuevo Equipo')

@section('content_header')
    <h1>Registrar Equipo</h1>
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

<div class="card">
    <div class="card-body">

        <form action="{{ route('equipos.store') }}" method="POST">
            @csrf

            <div class="row">

                <div class="col-md-6">
                    <label>Área</label>
                    <select name="area_id" class="form-control" required>
                        <option value="">Seleccione</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}">
                                {{ $area->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label>Ciudad</label>
                    <select name="ciudad_id" class="form-control" required>
                        <option value="">Seleccione</option>
                        @foreach($ciudades as $ciudad)
                            <option value="{{ $ciudad->id }}">
                                {{ $ciudad->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <br>

            <div class="row">

                <div class="col-md-4">
                    <label>Tipo Equipo</label>
                    <input type="text" name="tipo_equipo" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label>Marca</label>
                    <input type="text" name="marca" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label>Modelo</label>
                    <input type="text" name="modelo" class="form-control" required>
                </div>

            </div>

            <br>

            <div class="row">

                <div class="col-md-6">
                    <label>Número de Serie</label>
                    <input type="text" name="numero_serie" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label>Estatus</label>
                    <select name="estatus" class="form-control" required>
                        <option value="disponible">Disponible</option>
                        <option value="mantenimiento">Mantenimiento</option>
                        <option value="asignado">Asignado</option>
                        <option value="baja">Baja</option>
                    </select>
                </div>

            </div>

            <br>

            <div>
                <label>Observaciones</label>
                <textarea name="observaciones" class="form-control"></textarea>
            </div>

            <br>

            <button class="btn btn-primary">Guardar</button>
            <a href="{{ route('equipos.index') }}" class="btn btn-secondary">Cancelar</a>

        </form>

    </div>
</div>

@stop