@extends('adminlte::page')

@section('title', 'Nuevo Celular')

@section('content_header')
    <h1>Registrar Nuevo Celular</h1>
@stop

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-body">

        <form action="{{ route('celulares.store') }}" method="POST">
            @csrf

            <div class="form-group">
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

            <div class="form-group">
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

            <div class="form-group">
                <label>Marca</label>
                <input type="text" name="marca" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Modelo</label>
                <input type="text" name="modelo" class="form-control" required>
            </div>

            <div class="form-group">
                <label>IMEI</label>
                <input type="text" name="imei" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Número de Teléfono</label>
                <input type="text" name="numero_telefono" class="form-control">
            </div>

            <div class="form-group">
                <label>Estatus</label>
                <select name="estatus" class="form-control">
                    <option value="disponible">Disponible</option>
                    <option value="mantenimiento">Mantenimiento</option>
                    <option value="baja">Baja</option>
                </select>
            </div>

            <div class="form-group">
                <label>Observaciones</label>
                <textarea name="observaciones" class="form-control"></textarea>
            </div>

            <button type="submit" class="btn btn-success">
                Guardar
            </button>

            <a href="{{ route('celulares.index') }}" class="btn btn-secondary">
                Cancelar
            </a>

        </form>

    </div>
</div>

@stop