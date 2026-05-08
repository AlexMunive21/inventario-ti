@extends('adminlte::page')

@section('title', 'Editar Periférico')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Editar Periférico</h1>
        <a href="{{ route('perifericos.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Regresar
        </a>
    </div>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('perifericos.update', $periferico) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tipo</label>
                        <input type="text" class="form-control"
                               value="{{ ucfirst($periferico->tipo) }}" disabled>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Marca</label>
                        <input type="text" name="marca" class="form-control"
                               value="{{ old('marca', $periferico->marca) }}" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Modelo</label>
                        <input type="text" name="modelo" class="form-control"
                               value="{{ old('modelo', $periferico->modelo) }}" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Área</label>
                        <select name="area_id" class="form-control" required>
                            @foreach(\App\Models\Area::where('activo', 1)->get() as $area)
                                <option value="{{ $area->id }}"
                                    {{ $periferico->area_id == $area->id ? 'selected' : '' }}>
                                    {{ $area->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Cantidad total</label>
                        <input type="number" name="cantidad_total" class="form-control"
                               value="{{ old('cantidad_total', $periferico->cantidad_total) }}"
                               min="1" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Cantidad disponible</label>
                        <input type="number" name="cantidad_disponible" class="form-control"
                               value="{{ old('cantidad_disponible', $periferico->cantidad_disponible) }}"
                               min="0" required>
                    </div>
                </div>
            </div>
            <a href="{{ route('perifericos.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Actualizar</button>
        </form>
    </div>
</div>
@stop