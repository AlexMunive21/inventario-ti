@extends('adminlte::page')

@section('title', 'Editar Componente')

@section('content_header')
    <h1>Editar {{ ucfirst($componente->tipo) }}</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('componentes.update', $componente) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Tipo</label>
                        <input type="text" class="form-control" value="{{ ucfirst($componente->tipo) }}" disabled>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Marca</label>
                        <input type="text" name="marca" class="form-control @error('marca') is-invalid @enderror"
                               value="{{ old('marca', $componente->marca) }}" required>
                        @error('marca')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Modelo</label>
                        <input type="text" name="modelo" class="form-control @error('modelo') is-invalid @enderror"
                               value="{{ old('modelo', $componente->modelo) }}" required>
                        @error('modelo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Número de Serie</label>
                        <input type="text" name="numero_serie" class="form-control @error('numero_serie') is-invalid @enderror"
                               value="{{ old('numero_serie', $componente->numero_serie) }}" required>
                        @error('numero_serie')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Área</label>
                        <select name="area_id" class="form-control" required>
                            @foreach($areas as $area)
                                <option value="{{ $area->id }}" {{ $componente->area_id == $area->id ? 'selected' : '' }}>
                                    {{ $area->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Ciudad</label>
                        <select name="ciudad_id" class="form-control" required>
                            @foreach($ciudades as $ciudad)
                                <option value="{{ $ciudad->id }}" {{ $componente->ciudad_id == $ciudad->id ? 'selected' : '' }}>
                                    {{ $ciudad->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Estatus</label>
                        <select name="estatus" class="form-control" required>
                            <option value="disponible" {{ $componente->estatus == 'disponible' ? 'selected' : '' }}>Disponible</option>
                            <option value="en_uso" {{ $componente->estatus == 'en_uso' ? 'selected' : '' }}>En uso</option>
                            <option value="mantenimiento" {{ $componente->estatus == 'mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                            <option value="baja" {{ $componente->estatus == 'baja' ? 'selected' : '' }}>Baja</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Observaciones</label>
                        <input type="text" name="observaciones" class="form-control"
                               value="{{ old('observaciones', $componente->observaciones) }}">
                    </div>
                </div>
            </div>
            <a href="{{ route('componentes.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Actualizar</button>
        </form>
    </div>
</div>
@stop