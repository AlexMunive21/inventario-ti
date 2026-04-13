@extends('adminlte::page')

@section('title', 'Asignaciones de Tablets')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Asignaciones de Tablets</h1>
        <a href="{{ route('asignaciones-tablets.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Asignación
        </a>
        <a href="{{ route('asignaciones-tablets.historial') }}" class="btn btn-secondary">
            <i class="fas fa-history"></i> Historial
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

<div class="card">
    <div class="card-body table-responsive p-0">
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Colaborador</th>
                    <th>Tablet</th>
                    <th>No. Serie</th>
                    <th>Fecha asignación</th>
                    <th>Fecha devolución</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($asignaciones as $asignacion)
                <tr>
                    <td>{{ $asignacion->colaborador->nombre ?? '—' }} {{ $asignacion->colaborador->apellido_paterno ?? '' }}</td>
                    <td>{{ $asignacion->tablet->marca ?? '—' }} {{ $asignacion->tablet->modelo ?? '' }}</td>
                    <td>{{ $asignacion->tablet->numero_serie ?? '—' }}</td>
                    <td>{{ \Carbon\Carbon::parse($asignacion->fecha_asignacion)->format('d/m/Y') }}</td>
                    <td>
                        @if($asignacion->fecha_devolucion)
                            {{ \Carbon\Carbon::parse($asignacion->fecha_devolucion)->format('d/m/Y') }}
                        @else
                            <span class="badge badge-success">Activa</span>
                        @endif
                    </td>
                    <td>
                        @if(!$asignacion->fecha_devolucion)
                            <button type="button"
                                    class="btn btn-sm btn-warning"
                                    data-toggle="modal"
                                    data-target="#modalTablet{{ $asignacion->id }}"
                                    title="Devolver">
                                <i class="fas fa-undo"></i> Devolver
                            </button>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                </tr>

                {{-- Modal devolución tablet --}}
                @if(!$asignacion->fecha_devolucion)
                <div class="modal fade" id="modalTablet{{ $asignacion->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('asignaciones-tablets.devolver', $asignacion->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title">Devolver tablet</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <p>¿Confirmas la devolución de
                                        <strong>{{ $asignacion->tablet->marca }} {{ $asignacion->tablet->modelo }}</strong>
                                        de <strong>{{ $asignacion->colaborador->nombre }} {{ $asignacion->colaborador->apellido_paterno }}</strong>?
                                    </p>
                                    <div class="form-group">
                                        <label>Condición de la tablet al momento de la devolución</label>
                                        <textarea name="observaciones_devolucion"
                                                  class="form-control"
                                                  rows="3"
                                                  placeholder="Ej: Tablet en buenas condiciones, ligeros rayones en la pantalla..."></textarea>
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
                @endif

                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">No hay asignaciones registradas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@stop