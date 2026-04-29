@extends('adminlte::page')

@section('title', 'Detalle Equipo Escritorio')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>{{ $equipoEscritorio->nombre }}</h1>
        <div>
            @role('GerenteTIDS')
            @if($equipoEscritorio->estatus !== 'baja' && !$asignacionActiva)
            <form action="{{ route('equipos-escritorio.destroy', $equipoEscritorio) }}"
                  method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger btn-sm mr-2"
                        onclick="return confirm('¿Dar de baja este equipo? Los componentes quedarán disponibles.')">
                    <i class="fas fa-ban"></i> Dar de baja
                </button>
            </form>
            @endif
            @endrole
            <a href="{{ route('equipos-escritorio.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Regresar
            </a>
        </div>
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
                <div class="card-tools">
                    @php
                        $badgeColor = ['disponible'=>'success','asignado'=>'primary','mantenimiento'=>'warning','baja'=>'danger'][$equipoEscritorio->estatus] ?? 'secondary';
                    @endphp
                    <span class="badge badge-{{ $badgeColor }}">{{ ucfirst($equipoEscritorio->estatus) }}</span>
                </div>
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
                        <td>
                            @role('AnalistaTI|GerenteTIDS')
                            <button class="btn btn-xs btn-warning" data-toggle="modal"
                                    data-target="#modalCambiarPeriferico{{ $periferico->id }}">
                                <i class="fas fa-exchange-alt"></i>
                            </button>
                            @endrole
                        </td>
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
                    <table class="table table-sm">
                        <tr>
                            <th>Colaborador</th>
                            <td>{{ $asignacionActiva->colaborador->nombre }} {{ $asignacionActiva->colaborador->apellido_paterno }}</td>
                        </tr>
                        <tr>
                            <th>Puesto</th>
                            <td>{{ $asignacionActiva->colaborador->puesto ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Área</th>
                            <td>{{ $asignacionActiva->colaborador->area->nombre ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Desde</th>
                            <td>{{ \Carbon\Carbon::parse($asignacionActiva->fecha_asignacion)->format('d/m/Y') }}</td>
                        </tr>
                        @if($asignacionActiva->pdf_firmado)
                        <tr>
                            <th>PDF firmado</th>
                            <td>
                                <a href="{{ route('asignaciones-escritorio.descargarPdf', $asignacionActiva->id) }}"
                                   class="btn btn-xs btn-danger">
                                    <i class="fas fa-file-pdf"></i> Descargar
                                </a>
                            </td>
                        </tr>
                        @endif
                    </table>

                    <div class="mt-2">
                        {{-- Documentos --}}
                        @role('AnalistaTI|AnalistaDS|GerenteTIDS')
                        <div class="btn-group mr-2">
                            <button type="button" class="btn btn-sm btn-info dropdown-toggle"
                                    data-toggle="dropdown">
                                <i class="fas fa-file-alt"></i> Documentos
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item"
                                   href="{{ route('asignaciones-escritorio.generar', [$asignacionActiva->id, 'ficha_tecnica']) }}">
                                    <i class="fas fa-file-word text-success mr-1"></i> Ficha Técnica
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#"
                                   data-toggle="modal" data-target="#modalPdfEscritorio">
                                    <i class="fas fa-upload text-secondary mr-1"></i>
                                    {{ $asignacionActiva->pdf_firmado ? 'Reemplazar PDF firmado' : 'Subir PDF firmado' }}
                                </a>
                            </div>
                        </div>
                        @endrole

                        {{-- Liberar --}}
                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalLiberar">
                            <i class="fas fa-undo"></i> Liberar equipo
                        </button>
                    </div>
                @else
                    <p class="text-muted">
                        <i class="fas fa-info-circle mr-1"></i>
                        Este equipo no tiene asignación activa.
                    </p>
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

{{-- ✅ Fix 5 — Historial mejorado --}}
<div class="card mt-3">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-history mr-2"></i>Historial de asignaciones</h3>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-bordered table-sm mb-0">
            <thead class="thead-dark">
                <tr>
                    <th>Colaborador</th>
                    <th>Puesto</th>
                    <th>Fecha asignación</th>
                    <th>Fecha devolución</th>
                    <th>Condición devolución</th>
                    <th>PDF</th>
                </tr>
            </thead>
            <tbody>
                @forelse($equipoEscritorio->asignaciones()->with('colaborador')->orderBy('created_at','desc')->get() as $asig)
                <tr>
                    <td>
                        {{ $asig->colaborador->nombre ?? '—' }}
                        {{ $asig->colaborador->apellido_paterno ?? '' }}
                    </td>
                    <td>{{ $asig->colaborador->puesto ?? '—' }}</td>
                    <td>{{ \Carbon\Carbon::parse($asig->fecha_asignacion)->format('d/m/Y') }}</td>
                    <td>
                        @if($asig->fecha_devolucion)
                            {{ \Carbon\Carbon::parse($asig->fecha_devolucion)->format('d/m/Y') }}
                        @else
                            <span class="badge badge-success">Activa</span>
                        @endif
                    </td>
                    <td>{{ $asig->observaciones_devolucion ?? '—' }}</td>
                    <td>
                        @if($asig->pdf_firmado)
                            <a href="{{ route('asignaciones-escritorio.descargarPdf', $asig->id) }}"
                               class="btn btn-xs btn-danger" title="Descargar PDF firmado">
                                <i class="fas fa-file-pdf"></i>
                            </a>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-3">Sin historial de asignaciones.</td>
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

{{-- Modal subir PDF firmado --}}
<div class="modal fade" id="modalPdfEscritorio" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            @if($asignacionActiva)
            <form action="{{ route('asignaciones-escritorio.subirPdf', $asignacionActiva->id) }}"
                  method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Subir PDF firmado</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Archivo PDF</label>
                        <input type="file" name="pdf_firmado" class="form-control-file" accept=".pdf" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload"></i> Subir
                    </button>
                </div>
            </form>
            @endif
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

{{-- Modales cambiar periférico --}}
@foreach($equipoEscritorio->perifericos as $periferico)
<div class="modal fade" id="modalCambiarPeriferico{{ $periferico->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('equipos-escritorio.cambiarPeriferico', $equipoEscritorio) }}" method="POST">
                @csrf
                <input type="hidden" name="periferico_viejo_id" value="{{ $periferico->id }}">
                <div class="modal-header">
                    <h5 class="modal-title">Cambiar {{ ucfirst($periferico->tipo) }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">
                        {{ ucfirst($periferico->tipo) }} actual:
                        <strong>{{ $periferico->marca }} {{ $periferico->modelo }}</strong>
                    </p>
                    <div class="form-group">
                        <label>Nuevo {{ ucfirst($periferico->tipo) }}</label>
                        <select name="periferico_nuevo_id" class="form-control" required>
                            <option value="">-- Selecciona --</option>
                            @foreach(\App\Models\Periferico::where('tipo', $periferico->tipo)->where('cantidad_disponible', '>', 0)->get() as $p)
                                <option value="{{ $p->id }}">
                                    {{ $p->marca }} {{ $p->modelo }}
                                    ({{ $p->cantidad_disponible }} disponibles)
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-exchange-alt"></i> Cambiar {{ ucfirst($periferico->tipo) }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@stop