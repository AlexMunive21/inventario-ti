@extends('adminlte::page')

@section('title', 'Nueva Tablet')

@section('content_header')
    <h1>Nueva Tablet</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('tablets.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Marca</label>
                        <input type="text" name="marca" class="form-control @error('marca') is-invalid @enderror"
                               value="{{ old('marca') }}">
                        @error('marca')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Modelo</label>
                        <input type="text" name="modelo" class="form-control @error('modelo') is-invalid @enderror"
                               value="{{ old('modelo') }}">
                        @error('modelo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Número de Serie</label>
                        <input type="text" name="numero_serie" class="form-control @error('numero_serie') is-invalid @enderror"
                               value="{{ old('numero_serie') }}">
                        @error('numero_serie')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Estatus</label>
                        <select name="estatus" class="form-control @error('estatus') is-invalid @enderror">
                            <option value="">-- Selecciona --</option>
                            <option value="disponible" {{ old('estatus') == 'disponible' ? 'selected' : '' }}>Disponible</option>
                            <option value="asignado" {{ old('estatus') == 'asignado' ? 'selected' : '' }}>Asignado</option>
                            <option value="mantenimiento" {{ old('estatus') == 'mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                            <option value="baja" {{ old('estatus') == 'baja' ? 'selected' : '' }}>Baja</option>
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
                                <option value="{{ $area->id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}>
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
                                <option value="{{ $ciudad->id }}" {{ old('ciudad_id') == $ciudad->id ? 'selected' : '' }}>
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
                <textarea name="observaciones" class="form-control" rows="3">{{ old('observaciones') }}</textarea>
            </div>

            <a href="{{ route('tablets.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
    </div>
</div>
@stop
