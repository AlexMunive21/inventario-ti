@extends('adminlte::page')

@section('title', 'Asignaciones')

@section('content_header')
    <h1>Asignaciones</h1>
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

<a href="{{ route('asignaciones.create') }}" class="btn btn-primary mb-3">
    <i class="fas fa-plus"></i> Nueva Asignación
</a>
<a href="{{ route('asignaciones.historial') }}" class="btn btn-secondary mb-3">
    <i class="fas fa-history"></i> Historial
</a>

<div class="card">
    <div class="card-body table-responsive p-0">
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Equipo</th>
                    <th>Colaborador</th>
                    <th>Fecha</th>
                    <th>Estatus</th>
                    <th>PDF Firmado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($asignaciones as $asig)
                <tr>
                    <td>
                        <strong>{{ $asig->equipo->marca }} {{ $asig->equipo->modelo }}</strong>
                        <small class="text-muted d-block">{{ $asig->equipo->tipo_equipo }} — {{ $asig->equipo->numero_serie }}</small>
                    </td>
                    <td>
                        {{ $asig->colaborador->nombre }}
                        {{ $asig->colaborador->apellido_paterno }}
                        {{ $asig->colaborador->apellido_materno }}
                    </td>
                    <td>{{ \Carbon\Carbon::parse($asig->fecha_asignacion)->format('d/m/Y') }}</td>
                    <td>
                        <span class="badge badge-{{ $asig->activa ? 'success' : 'secondary' }}">
                            {{ $asig->activa ? 'Activa' : 'Inactiva' }}
                        </span>
                    </td>
                    <td>
                        @if($asig->pdf_firmado)
                            <a href="{{ route('asignaciones.descargarPdf', $asig->id) }}"
                               class="btn btn-xs btn-danger" title="Descargar PDF firmado">
                                <i class="fas fa-file-pdf"></i> Ver responsiva PDF
                            </a>
                        @else
                            <span class="badge badge-warning">Pendiente</span>
                        @endif
                    </td>
                    <td>
                        <!-- {{-- Dropdown de documentos --}}
                        @role('AnalistaTI|AnalistaDS|GerenteTIDS')
                        <div class="btn-group mr-1">
                            <button type="button" class="btn btn-sm btn-info dropdown-toggle"
                                    data-toggle="dropdown">
                                <i class="fas fa-file-alt"></i>
                            </button>
                            <div class="dropdown-menu">
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#"
                                   data-toggle="modal"
                                   data-target="#modalPdf{{ $asig->id }}">
                                    <i class="fas fa-upload text-secondary mr-1"></i>
                                    {{ $asig->pdf_firmado ? 'Reemplazar PDF' : 'Subir PDF firmado' }}
                                </a>
                            </div>
                        </div>
                        @endrole -->
                        {{-- Ficha técnica — solo laptops y equipos de escritorio --}}
                        @if(in_array(strtolower($asig->equipo->tipo_equipo ?? ''), ['laptop', 'desktop', 'workstation']))
                        <a class="dropdown-item"
                        href="{{ route('asignaciones.fichaTecnica', $asig->id) }}">
                            <i class="fas fa-file-alt text-success mr-1"></i> Ficha Técnica
                        </a>
                        @endif

                        {{-- Liberar --}}
                        <button type="button"
                                class="btn btn-sm btn-warning"
                                data-toggle="modal"
                                data-target="#modalDevolver{{ $asig->id }}"
                                title="Liberar">
                            <i class="fas fa-undo"></i> Liberar
                        </button>
                    </td>
                </tr>

                {{-- Modal devolver --}}
                <div class="modal fade" id="modalDevolver{{ $asig->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('asignaciones.destroy', $asig->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <div class="modal-header">
                                    <h5 class="modal-title">Devolver equipo</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <p>¿Confirmas la devolución de
                                        <strong>{{ $asig->equipo->marca }} {{ $asig->equipo->modelo }}</strong>
                                        de <strong>{{ $asig->colaborador->nombre }} {{ $asig->colaborador->apellido_paterno }}</strong>?
                                    </p>
                                    <div class="form-group">
                                        <label>Condición del equipo al momento de la devolución</label>
                                        <textarea name="observaciones_devolucion"
                                                  class="form-control" rows="3"
                                                  placeholder="Ej: Equipo en buenas condiciones, ligeros rayones..."></textarea>
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

                {{-- Modal subir PDF --}}
                <div class="modal fade" id="modalPdf{{ $asig->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('asignaciones.subirPdf', $asig->id) }}"
                                  method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        <i class="fas fa-upload mr-2"></i>
                                        {{ $asig->pdf_firmado ? 'Reemplazar PDF firmado' : 'Subir PDF firmado' }}
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <p class="text-muted">
                                        Responsiva/Pagaré firmado por
                                        <strong>{{ $asig->colaborador->nombre }} {{ $asig->colaborador->apellido_paterno }}</strong>
                                    </p>
                                    @if($asig->pdf_firmado)
                                        <div class="alert alert-info py-1">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Ya tiene un PDF subido. Al subir uno nuevo lo reemplazará.
                                        </div>
                                    @endif
                                    <div class="form-group">
                                        <label>Archivo PDF <span class="text-danger">*</span></label>
                                        <input type="file" name="pdf_firmado"
                                               class="form-control-file" accept=".pdf" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-upload"></i> Subir
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">No hay asignaciones activas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@stop