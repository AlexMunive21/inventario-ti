@extends('adminlte::page')

@section('title', 'Editar Equipo')

@section('content_header')
    <h1>Editar Equipo</h1>
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

        <form action="{{ route('equipos.update', $equipo) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">

                <div class="col-md-6">
                    <label>Área</label>
                    <select name="area_id" class="form-control" required>
                        <option value="">Seleccione</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}"
                                {{ $equipo->area_id == $area->id ? 'selected' : '' }}>
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
                            <option value="{{ $ciudad->id }}"
                                {{ $equipo->ciudad_id == $ciudad->id ? 'selected' : '' }}>
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
                    <input type="text" 
                           name="tipo_equipo" 
                           class="form-control"
                           value="{{ $equipo->tipo_equipo }}" 
                           required>
                </div>

                <div class="col-md-4">
                    <label>Marca</label>
                    <input type="text" 
                           name="marca" 
                           class="form-control"
                           value="{{ $equipo->marca }}" 
                           required>
                </div>

                <div class="col-md-4">
                    <label>Modelo</label>
                    <input type="text" 
                           name="modelo" 
                           class="form-control"
                           value="{{ $equipo->modelo }}" 
                           required>
                </div>

            </div>

            <br>

            <div class="row">

                <div class="col-md-6">
                    <label>Número de Serie</label>
                    <input type="text" 
                           name="numero_serie" 
                           class="form-control"
                           value="{{ $equipo->numero_serie }}" 
                           required>
                </div>

                <div class="col-md-6">
                    <label>Estatus</label>
                    <select name="estatus" class="form-control" required>
                        
                        <option value="disponible"
                            {{ $equipo->estatus == 'disponible' ? 'selected' : '' }}>
                            Disponible
                        </option>

                        <option value="asignado"
                            {{ $equipo->estatus == 'asignado' ? 'selected' : '' }}>
                            Asignado
                        </option>

                        <option value="mantenimiento"
                            {{ $equipo->estatus == 'mantenimiento' ? 'selected' : '' }}>
                            Mantenimiento
                        </option>
                        
                        <option value="baja"
                            {{ $equipo->estatus == 'baja' ? 'selected' : '' }}>
                            Baja
                        </option>
                    </select>
                </div>

            </div>

            <br>

            <div>
                <label>Observaciones</label>
                <textarea name="observaciones" 
                          class="form-control">{{ $equipo->observaciones }}</textarea>
            </div>

            <br>

            <button type="submit" class="btn btn-success">
                Actualizar
            </button>

            <a href="{{ route('equipos.index') }}" class="btn btn-secondary">
                Cancelar
            </a>

        </form>

    </div>
</div>

@stop