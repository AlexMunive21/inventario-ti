@extends('adminlte::page')

@section('title', 'Editar Celular')

@section('content_header')
    <h1>Editar Celular</h1>
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

        <form action="{{ route('celulares.update', $celular) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Área</label>
                <select name="area_id" class="form-control" required>
                    @foreach($areas as $area)
                        <option value="{{ $area->id }}"
                            {{ $celular->area_id == $area->id ? 'selected' : '' }}>
                            {{ $area->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Ciudad</label>
                <select name="ciudad_id" class="form-control" required>
                    @foreach($ciudades as $ciudad)
                        <option value="{{ $ciudad->id }}"
                            {{ $celular->ciudad_id == $ciudad->id ? 'selected' : '' }}>
                            {{ $ciudad->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Marca</label>
                <input type="text" name="marca" class="form-control"
                       value="{{ $celular->marca }}" required>
            </div>

            <div class="form-group">
                <label>Modelo</label>
                <input type="text" name="modelo" class="form-control"
                       value="{{ $celular->modelo }}" required>
            </div>

            <div class="form-group">
                <label>IMEI</label>
                <input type="text" name="imei" class="form-control"
                       value="{{ $celular->imei }}" required>
            </div>

            <div class="form-group">
                <label>Número de Teléfono</label>
                <input type="text" name="numero_telefono" class="form-control"
                       value="{{ $celular->numero_telefono }}">
            </div>

            <div class="form-group">
                <label>Estatus</label>
                <select name="estatus" class="form-control">
                    <option value="disponible" {{ $celular->estatus == 'disponible' ? 'selected' : '' }}>
                        Disponible
                    </option>
                    <option value="asignado" {{ $celular->estatus == 'asignado' ? 'selected' : '' }}>
                        Asignado
                    </option>
                    <option value="mantenimiento" {{ $celular->estatus == 'mantenimiento' ? 'selected' : '' }}>
                        Mantenimiento
                    </option>
                    <option value="baja" {{ $celular->estatus == 'baja' ? 'selected' : '' }}>
                        Baja
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label>Observaciones</label>
                <textarea name="observaciones" class="form-control">{{ $celular->observaciones }}</textarea>
            </div>

            <button type="submit" class="btn btn-success">
                Actualizar
            </button>

            <a href="{{ route('celulares.index') }}" class="btn btn-secondary">
                Cancelar
            </a>

        </form>

    </div>
</div>

@stop