@extends('adminlte::page')

@section('title', 'Armar Equipo de Escritorio')

@section('content_header')
    <h1><i class="fas fa-desktop mr-2"></i>Armar Equipo de Escritorio</h1>
@stop

@section('content')

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <form action="{{ route('equipos-escritorio.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nombre del equipo</label>
                        <input type="text" name="nombre" class="form-control"
                               placeholder="Ej: Escritorio Recepción CDMX"
                               value="{{ old('nombre') }}" required>
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
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Ciudad</label>
                        <select name="ciudad_id" class="form-control" required>
                            <option value="">-- Selecciona --</option>
                            @foreach($ciudades as $ciudad)
                                <option value="{{ $ciudad->id }}" {{ old('ciudad_id') == $ciudad->id ? 'selected' : '' }}>
                                    {{ $ciudad->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <hr>
            <h5><i class="fas fa-microchip text-primary mr-1"></i> CPU</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Selecciona el CPU</label>
                        <select name="cpu_id" class="form-control" required>
                            <option value="">-- Selecciona CPU --</option>
                            @foreach($cpus as $cpu)
                                <option value="{{ $cpu->id }}" {{ old('cpu_id') == $cpu->id ? 'selected' : '' }}>
                                    {{ $cpu->marca }} {{ $cpu->modelo }} — {{ $cpu->numero_serie }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <hr>
            <h5><i class="fas fa-tv text-success mr-1"></i> Monitores <small class="text-muted">(puedes seleccionar varios)</small></h5>
            <div class="row">
                @foreach($monitores as $monitor)
                <div class="col-md-4">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox"
                               name="monitores[]" value="{{ $monitor->id }}"
                               id="monitor{{ $monitor->id }}"
                               {{ in_array($monitor->id, old('monitores', [])) ? 'checked' : '' }}>
                        <label class="form-check-label" for="monitor{{ $monitor->id }}">
                            {{ $monitor->marca }} {{ $monitor->modelo }}
                            <small class="text-muted d-block">Serie: {{ $monitor->numero_serie }}</small>
                        </label>
                    </div>
                </div>
                @endforeach
                @if($monitores->isEmpty())
                    <div class="col-12">
                        <p class="text-muted">No hay monitores disponibles.</p>
                    </div>
                @endif
            </div>

            <hr>
            <h5><i class="fas fa-keyboard text-warning mr-1"></i> Periféricos</h5>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Teclado</label>
                        <select name="teclado_id" class="form-control" required>
                            <option value="">-- Selecciona --</option>
                            @foreach($teclados as $teclado)
                                <option value="{{ $teclado->id }}" {{ old('teclado_id') == $teclado->id ? 'selected' : '' }}>
                                    {{ $teclado->marca }} {{ $teclado->modelo }}
                                    ({{ $teclado->cantidad_disponible }} disponibles)
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Mouse</label>
                        <select name="mouse_id" class="form-control" required>
                            <option value="">-- Selecciona --</option>
                            @foreach($mouses as $mouse)
                                <option value="{{ $mouse->id }}" {{ old('mouse_id') == $mouse->id ? 'selected' : '' }}>
                                    {{ $mouse->marca }} {{ $mouse->modelo }}
                                    ({{ $mouse->cantidad_disponible }} disponibles)
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <hr>
            <div class="form-group">
                <label>Observaciones</label>
                <textarea name="observaciones" class="form-control" rows="2">{{ old('observaciones') }}</textarea>
            </div>

            <a href="{{ route('equipos-escritorio.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-tools"></i> Armar Equipo
            </button>
        </form>
    </div>
</div>

@stop