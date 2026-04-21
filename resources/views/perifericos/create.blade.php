@extends('adminlte::page')

@section('title', 'Nuevo Periférico')

@section('content_header')
    <h1>Nuevo Periférico</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('perifericos.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tipo</label>
                        <select name="tipo" class="form-control" required>
                            <option value="">-- Selecciona --</option>
                            <option value="teclado" {{ old('tipo') == 'teclado' ? 'selected' : '' }}>Teclado</option>
                            <option value="mouse" {{ old('tipo') == 'mouse' ? 'selected' : '' }}>Mouse</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Marca</label>
                        <input type="text" name="marca" class="form-control" value="{{ old('marca') }}" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Modelo</label>
                        <input type="text" name="modelo" class="form-control" value="{{ old('modelo') }}" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Área</label>
                        <select name="area_id" class="form-control" required>
                            <option value="">-- Selecciona --</option>
                            @foreach($areas as $area)
                                <option value="{{ $area->id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}>
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
                               value="{{ old('cantidad_total', 1) }}" min="1" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Cantidad disponible</label>
                        <input type="number" name="cantidad_disponible" class="form-control"
                               value="{{ old('cantidad_disponible', 1) }}" min="0" required>
                    </div>
                </div>
            </div>
            <a href="{{ route('perifericos.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
    </div>
</div>
@stop