@extends('adminlte::page')

@section('title', 'Nueva Asignación de Tablet')

@section('content_header')
    <h1>Nueva Asignación de Tablet</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('asignaciones-tablets.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Tablet</label>
                        <select name="tablet_id" class="form-control @error('tablet_id') is-invalid @enderror">
                            <option value="">-- Selecciona --</option>
                            @foreach($tablets as $tablet)
                                <option value="{{ $tablet->id }}">
                                    {{ $tablet->marca }} {{ $tablet->modelo }} — {{ $tablet->numero_serie }}
                                </option>
                            @endforeach
                        </select>
                        @error('tablet_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Colaborador</label>
                        <select name="colaborador_id" class="form-control @error('colaborador_id') is-invalid @enderror">
                            <option value="">-- Selecciona --</option>
                            @foreach($colaboradores as $col)
                                <option value="{{ $col->id }}">
                                    {{ $col->nombre }} {{ $col->apellido_paterno }}
                                </option>
                            @endforeach
                        </select>
                        @error('colaborador_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Fecha de Asignación</label>
                        <input type="date" name="fecha_asignacion"
                               class="form-control @error('fecha_asignacion') is-invalid @enderror"
                               value="{{ old('fecha_asignacion', date('Y-m-d')) }}">
                        @error('fecha_asignacion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Observaciones</label>
                        <textarea name="observaciones" class="form-control" rows="2">{{ old('observaciones') }}</textarea>
                    </div>
                </div>
            </div>

            <a href="{{ route('asignaciones-tablets.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Asignar</button>
        </form>
    </div>
</div>
@stop