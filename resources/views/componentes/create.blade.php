@extends('adminlte::page')

@section('title', 'Nuevo Componente')

@section('content_header')
    <h1>Nuevo Componente</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('componentes.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Tipo</label>
                        <select name="tipo" class="form-control @error('tipo') is-invalid @enderror" required>
                            <option value="">-- Selecciona --</option>
                            <option value="cpu" {{ old('tipo') == 'cpu' ? 'selected' : '' }}>CPU</option>
                            <option value="monitor" {{ old('tipo') == 'monitor' ? 'selected' : '' }}>Monitor</option>
                        </select>
                        @error('tipo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Marca</label>
                        <input type="text" name="marca" class="form-control @error('marca') is-invalid @enderror"
                               value="{{ old('marca') }}" required>
                        @error('marca')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Modelo</label>
                        <input type="text" name="modelo" class="form-control @error('modelo') is-invalid @enderror"
                               value="{{ old('modelo') }}" required>
                        @error('modelo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Número de Serie</label>
                        <input type="text" name="numero_serie" class="form-control @error('numero_serie') is-invalid @enderror"
                               value="{{ old('numero_serie') }}" required>
                        @error('numero_serie')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Área</label>
                        <select name="area_id" class="form-control @error('area_id') is-invalid @enderror" required>
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
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Ciudad</label>
                        <select name="ciudad_id" class="form-control @error('ciudad_id') is-invalid @enderror" required>
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
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Estatus</label>
                        <select name="estatus" class="form-control" required>
                            <option value="disponible">Disponible</option>
                            <option value="mantenimiento">Mantenimiento</option>
                            <option value="baja">Baja</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Observaciones</label>
                        <input type="text" name="observaciones" class="form-control"
                               value="{{ old('observaciones') }}">
                    </div>
                </div>
            </div>
            <a href="{{ route('componentes.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
    </div>
</div>
@stop