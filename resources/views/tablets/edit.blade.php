@extends('adminlte::page')

@section('title', 'Editar Tablet')

@section('content_header')
    <h1>Editar Tablet</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('tablets.update', $tablet) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Marca</label>
                        <input type="text" name="marca" class="form-control @error('marca') is-invalid @enderror"
                               value="{{ old('marca', $tablet->marca) }}">
                        @error('marca')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Modelo</label>
                        <input type="text" name="modelo" class="form-control @error('modelo') is-invalid @enderror"
                               value="{{ old('modelo', $tablet->modelo) }}">
                        @error('modelo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Número de Serie</label>
                        <input type="text" name="numero_serie" class="form-control @error('numero_serie') is-invalid @enderror"
                               value="{{ old('numero_serie', $tablet->numero_serie) }}">
                        @error('numero_serie')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Estatus</label>
                        <select name="estatus" class="form-control @error('estatus') is-invalid @enderror">
                            <option value="disponible" {{ $tablet->estatus == 'disponible' ? 'selected' : '' }}>Disponible</option>
                            <option value="asignado" {{ $tablet->estatus == 'asignado' ? 'selected' : '' }}>Asignado</option>
                            <option value="mantenimiento" {{ $tablet->estatus == 'mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                            <option value="baja" {{ $tablet->estatus == 'baja' ? 'selected' : '' }}>Baja</option>
                        </select>
                        @error('estatus')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Área</label>
                        <select name="area_id" class="form-control @error('area_id') is-invalid @enderror">
                            <option value="">-- Selecciona --</option>
                            @foreach($areas as $area)
                                <option value="{{ $area->id }}" {{ $tablet->area_id == $area->id ? 'selected' : '' }}>
                                    {{ $area->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('area_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Ciudad</label>
                        <select name="ciudad_id" class="form-control @error('ciudad_id') is-invalid @enderror">
                            <option value="">-- Selecciona --</option>
                            @foreach($ciudades as $ciudad)
                                <option value="{{ $ciudad->id }}" {{ $tablet->ciudad_id == $ciudad->id ? 'selected' : '' }}>
                                    {{ $ciudad->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('ciudad_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Observaciones</label>
                <textarea name="observaciones" class="form-control" rows="3">{{ old('observaciones', $tablet->observaciones) }}</textarea>
            </div>

            <a href="{{ route('tablets.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Actualizar</button>
        </form>
    </div>
</div>
@stop
