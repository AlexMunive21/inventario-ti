@extends('adminlte::page')

@section('title', 'Detalle Equipo Escritorio')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>{{ $equipoEscritorio->nombre }}</h1>
        <a href="{{ route('equipos-escritorio.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Regresar
        </a>
    </div>
@stop

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
@endif

<div class="row">

    {{-- Info del equipo --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-desktop mr-2"></i>Componentes del equipo</h3>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th width="120">CPU</th>
                        <td>
                            {{ $equipoEscritorio->cpu->marca }} {{ $equipoEscritorio->cpu->modelo }}
                            <small class="text-muted d-block">Serie: {{ $equipoEscritorio->cpu->numero_serie }}</small>
                        </td>
                        <td>
                            @role('AnalistaTI|GerenteTIDS')
                            <button class="btn btn-xs btn-warning" data-toggle="modal"
                                    data-target="#modalCambiarCpu">
                                <i class="fas fa-exchange-alt"></i>
                            </button>
                            @endrole
                        </td>
                    </tr>
                    @foreach($equipoEscritorio->monitores as $monitor)
                    <tr>
                        <th>Monitor {{ $loop->iteration }}</th>
                        <td>
                            {{ $monitor->marca }} {{ $monitor->modelo }}
                            <small class="text-muted d-block">Serie: {{ $monitor->numero_serie }}</small>
                        </td>
                        <td>
                            @role('AnalistaTI|GerenteTIDS')
                            <button class="btn btn-xs btn-warning" data-toggle="modal"
                                    data-target="#modalCambiarMonitor{{ $monitor->id }}">
                                <i class="fas fa-exchange-alt"></i>
                            </button>
                            @endrole
                        </td>
                    </tr>
                    @endforeach
                    @foreach($equipoEscritorio->perifericos as $periferico)
                    <tr>
                        <th>{{ ucfirst($periferico->tipo) }}</th>
                        <td>{{ $periferico->marca }} {{ $periferico->modelo }}</td>
                        <td></td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>

    {{-- Asignación actual --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user-check mr-2"></i>Asignación actual</h3>
            </div>
            <div class="card-body">
                @if($asignacionActiva)
                    <p><strong>Colaborador:</strong>
                        {{ $asignacionActiva->colaborador->nombre }}
                        {{ $asignacionActiva->colaborador->apellido_paterno }}
                    </p>
                    <p><strong>Desde:</strong>
                        {{ \Carbon\Carbon::parse($asignacionActiva->fecha_asignacion)->format('d/m/Y') }}
                    </p>
                    <p><strong>Puesto:</strong> {{ $asignacionActiva->colaborador->puesto ?? '—' }}</p>

                    {{-- Liberar --}}
                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalLiberar">
                        <i class="fas fa-undo"></i> Liberar equipo
                    </button>
                @else
                    <p class="text-muted">Este equipo no tiene asignación activa.</p>
                    @if($equipoEscritorio->estatus === 'disponible')
                    <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalAsignar">
                        <i class="fas fa-user-plus"></i> Asignar colaborador
                    </button>
                    @endif
                @endif
            </div>
        </div>
    </div>

</div>

{{-- Historial de asignaciones --}}
<div class="card mt-2">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-history mr-2"></i>Historial de asignaciones</h3>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-bordered table-sm">
            <thead class="thead-dark">
                <tr>
                    <th>Colaborador</th>
                    <th>Fecha asignación</th>
                    <th>Fecha devolución</th>
                    <th>Condición devolución</th>
                </tr>
            </thead>
            <tbody>
                @forelse($equipoEscritorio->asignaciones as $asig)
                <tr>
                    <td>{{ $asig->colaborador->nombre }} {{ $asig->colaborador->apellido_paterno }}</td>
                    <td>{{ \Carbon\Carbon::parse($asig->fecha_asignacion)->format('d/m/Y') }}</td>
                    <td>
                        @if($asig->fecha_devolucion)
                            {{ \Carbon\Carbon::parse($asig->fecha_devolucion)->format('d/m/Y') }}
                        @else
                            <span class="badge badge-success">Activa</span>
                        @endif
                    </td>
                    <td>{{ $asig->observaciones_devolucion ?? '—' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">Sin historial.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal asignar --}}
<div class="modal fade" id="modalAsignar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('equipos-escritorio.asignar', $equipoEscritorio) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Asignar equipo</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Colaborador</label>
                        <select name="colaborador_id" class="form-control" required>
                            <option value="">-- Selecciona --</option>
                            @foreach(\App\Models\Colaborador::where('activo', 1)->get() as $col)
                                <option value="{{ $col->id }}">
                                    {{ $col->nombre }} {{ $col->apellido_paterno }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Fecha de asignación</label>
                        <input type="date" name="fecha_asignacion" class="form-control"
                               value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Observaciones</label>
                        <textarea name="observaciones" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Asignar</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal liberar --}}
<div class="modal fade" id="modalLiberar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('equipos-escritorio.liberar', $equipoEscritorio) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Liberar equipo</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Condición del equipo al momento de la devolución</label>
                        <textarea name="observaciones_devolucion" class="form-control" rows="3"
                                  placeholder="Ej: Todo en buenas condiciones, rayón en monitor..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">Confirmar devolución</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal cambiar CPU --}}
<div class="modal fade" id="modalCambiarCpu" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('equipos-escritorio.cambiarComponente', $equipoEscritorio) }}" method="POST">
                @csrf
                <input type="hidden" name="tipo" value="cpu">
                <input type="hidden" name="componente_viejo_id" value="{{ $equipoEscritorio->cpu_id }}">
                <div class="modal-header">
                    <h5 class="modal-title">Cambiar CPU</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">CPU actual:
                        <strong>{{ $equipoEscritorio->cpu->marca }} {{ $equipoEscritorio->cpu->modelo }}</strong>
                    </p>
                    <div class="form-group">
                        <label>Nuevo CPU</label>
                        <select name="componente_nuevo_id" class="form-control" required>
                            <option value="">-- Selecciona --</option>
                            @foreach(\App\Models\Componente::where('tipo','cpu')->where('estatus','disponible')->get() as $cpu)
                                <option value="{{ $cpu->id }}">
                                    {{ $cpu->marca }} {{ $cpu->modelo }} — {{ $cpu->numero_serie }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">Cambiar CPU</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modales cambiar monitor --}}
@foreach($equipoEscritorio->monitores as $monitor)
<div class="modal fade" id="modalCambiarMonitor{{ $monitor->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('equipos-escritorio.cambiarComponente', $equipoEscritorio) }}" method="POST">
                @csrf
                <input type="hidden" name="tipo" value="monitor">
                <input type="hidden" name="componente_viejo_id" value="{{ $monitor->id }}">
                <div class="modal-header">
                    <h5 class="modal-title">Cambiar Monitor</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">Monitor actual:
                        <strong>{{ $monitor->marca }} {{ $monitor->modelo }}</strong>
                    </p>
                    <div class="form-group">
                        <label>Nuevo Monitor</label>
                        <select name="componente_nuevo_id" class="form-control" required>
                            <option value="">-- Selecciona --</option>
                            @foreach(\App\Models\Componente::where('tipo','monitor')->where('estatus','disponible')->get() as $m)
                                <option value="{{ $m->id }}">
                                    {{ $m->marca }} {{ $m->modelo }} — {{ $m->numero_serie }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">Cambiar Monitor</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@stop