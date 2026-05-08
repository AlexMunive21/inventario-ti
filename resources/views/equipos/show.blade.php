@extends('adminlte::page')

@section('title', 'Detalle del Equipo')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Detalle del Equipo</h1>
        <a href="{{ route('equipos.index') }}" class="btn btn-secondary btn-sm">
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
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-laptop mr-2"></i>Información del equipo</h3>
            </div>
            <div class="card-body">
                <table class="table table-sm table-bordered">
                    <tr><th>Tipo</th><td>{{ $equipo->tipo_equipo }}</td></tr>
                    <tr><th>Marca</th><td>{{ $equipo->marca }}</td></tr>
                    <tr><th>Modelo</th><td>{{ $equipo->modelo }}</td></tr>
                    <tr><th>No. Serie</th><td>{{ $equipo->numero_serie }}</td></tr>
                    <tr><th>Área</th><td>{{ $equipo->area->nombre ?? '—' }}</td></tr>
                    <tr><th>Ciudad</th><td>{{ $equipo->ciudad->nombre ?? '—' }}</td></tr>
                    <tr>
                        <th>Estatus</th>
                        <td>
                            @php $badge = ['disponible'=>'success','asignado'=>'primary','mantenimiento'=>'warning','baja'=>'danger'][$equipo->estatus] ?? 'secondary'; @endphp
                            <span class="badge badge-{{ $badge }}">{{ ucfirst($equipo->estatus) }}</span>
                        </td>
                    </tr>
                    @if($equipo->observaciones)
                    <tr><th>Observaciones</th><td>{{ $equipo->observaciones }}</td></tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    {{-- Asignación activa y PDFs --}}
    <div class="col-md-7">
        @php $asignacionActiva = $equipo->asignaciones->where('activa', 1)->first(); @endphp
        @if($asignacionActiva)
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title"><i class="fas fa-user-check mr-2"></i>Asignación activa</h3>
            </div>
            <div class="card-body">
                <p><strong>Colaborador:</strong> {{ $asignacionActiva->colaborador->nombre }} {{ $asignacionActiva->colaborador->apellido_paterno }}</p>
                <p><strong>Puesto:</strong> {{ $asignacionActiva->colaborador->puesto ?? '—' }}</p>
                <p><strong>Desde:</strong> {{ \Carbon\Carbon::parse($asignacionActiva->fecha_asignacion)->format('d/m/Y') }}</p>

                <hr>
                <h6><i class="fas fa-file-pdf mr-1 text-danger"></i> Documentos firmados</h6>

                <div class="row">
                    {{-- PDF Responsiva --}}
                    <div class="col-md-6">
                        <div class="card card-outline card-secondary">
                            <div class="card-header py-2">
                                <h6 class="card-title mb-0"><i class="fas fa-file-alt mr-1"></i> Responsiva</h6>
                            </div>
                            <div class="card-body py-2">
                                @if($asignacionActiva->pdf_firmado)
                                    <a href="{{ route('asignaciones.descargarPdf', $asignacionActiva->id) }}"
                                       class="btn btn-sm btn-danger btn-block mb-1">
                                        <i class="fas fa-download"></i> Descargar PDF
                                    </a>
                                    <button class="btn btn-sm btn-outline-secondary btn-block"
                                            data-toggle="modal" data-target="#modalResponsiva">
                                        <i class="fas fa-sync"></i> Reemplazar
                                    </button>
                                @else
                                    <p class="text-muted small mb-1">Sin PDF subido</p>
                                    <button class="btn btn-sm btn-primary btn-block"
                                            data-toggle="modal" data-target="#modalResponsiva">
                                        <i class="fas fa-upload"></i> Subir PDF
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- PDF Pagaré — solo laptops --}}
                    @if(strtolower($equipo->tipo_equipo) === 'laptop')
                    <div class="col-md-6">
                        <div class="card card-outline card-secondary">
                            <div class="card-header py-2">
                                <h6 class="card-title mb-0"><i class="fas fa-receipt mr-1"></i> Pagaré</h6>
                            </div>
                            <div class="card-body py-2">
                                @if($asignacionActiva->pdf_pagare)
                                    <a href="{{ route('asignaciones.descargarPdfPagare', $asignacionActiva->id) }}"
                                       class="btn btn-sm btn-danger btn-block mb-1">
                                        <i class="fas fa-download"></i> Descargar PDF
                                    </a>
                                    <button class="btn btn-sm btn-outline-secondary btn-block"
                                            data-toggle="modal" data-target="#modalPagare">
                                        <i class="fas fa-sync"></i> Reemplazar
                                    </button>
                                @else
                                    <p class="text-muted small mb-1">Sin PDF subido</p>
                                    <button class="btn btn-sm btn-primary btn-block"
                                            data-toggle="modal" data-target="#modalPagare">
                                        <i class="fas fa-upload"></i> Subir PDF
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Historial --}}
<div class="card mt-2">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-history mr-2"></i>Historial de asignaciones</h3>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-bordered table-sm mb-0">
            <thead class="thead-dark">
                <tr>
                    <th>Colaborador</th>
                    <th>Fecha asignación</th>
                    <th>Fecha devolución</th>
                    <th>Condición</th>
                    <th>Responsiva</th>
                    @if(strtolower($equipo->tipo_equipo) === 'laptop')
                    <th>Pagaré</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($equipo->asignaciones->sortByDesc('created_at') as $asig)
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
                    <td>
                        @if($asig->pdf_firmado)
                            <a href="{{ route('asignaciones.descargarPdf', $asig->id) }}"
                               class="btn btn-xs btn-danger">
                                <i class="fas fa-file-pdf"></i>
                            </a>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    @if(strtolower($equipo->tipo_equipo) === 'laptop')
                    <td>
                        @if($asig->pdf_pagare)
                            <a href="{{ route('asignaciones.descargarPdfPagare', $asig->id) }}"
                               class="btn btn-xs btn-danger">
                                <i class="fas fa-file-pdf"></i>
                            </a>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-3">Sin historial.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal subir responsiva --}}
@if(isset($asignacionActiva))
<div class="modal fade" id="modalResponsiva" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('asignaciones.subirPdf', $asignacionActiva->id) }}"
                  method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-upload mr-2"></i>Subir PDF de Responsiva</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    @if($asignacionActiva->pdf_firmado)
                        <div class="alert alert-warning py-1">Ya tiene un PDF. Al subir uno nuevo lo reemplazará.</div>
                    @endif
                    <div class="form-group">
                        <label>Archivo PDF firmado</label>
                        <input type="file" name="pdf_firmado" class="form-control-file" accept=".pdf" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Subir</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal subir pagaré --}}
@if(strtolower($equipo->tipo_equipo) === 'laptop')
<div class="modal fade" id="modalPagare" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('asignaciones.subirPdfPagare', $asignacionActiva->id) }}"
                  method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-upload mr-2"></i>Subir PDF de Pagaré</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    @if($asignacionActiva->pdf_pagare)
                        <div class="alert alert-warning py-1">Ya tiene un PDF. Al subir uno nuevo lo reemplazará.</div>
                    @endif
                    <div class="form-group">
                        <label>Archivo PDF firmado</label>
                        <input type="file" name="pdf_pagare" class="form-control-file" accept=".pdf" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Subir</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endif

@stop