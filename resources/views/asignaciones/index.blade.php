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

<a href="{{ route('asignaciones.create') }}" class="btn btn-primary mb-3">
    Nueva Asignación
</a>
<a href="{{ route('asignaciones.historial') }}" class="btn btn-secondary mb-3">
    Historial de Asignaciones
</a>

<div class="card">
<div class="card-body table-responsive">

<table class="table table-bordered table-striped">
    <thead class="thead-dark">
        <tr>
            <th>Equipo</th>
            <th>Colaborador</th>
            <th>Fecha</th>
            <th>Estatus</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($asignaciones as $asig)
        <tr>
            <td>{{ $asig->equipo->marca }} {{ $asig->equipo->modelo }}</td>
            <td>
                {{ $asig->colaborador->nombre }}
                {{ $asig->colaborador->apellido_paterno }}
                {{ $asig->colaborador->apellido_materno }}
            </td>
            <td>{{ $asig->fecha_asignacion }}</td>
            <td>{{ $asig->activa ? 'Activa' : 'Inactiva' }}</td>
            <td>
                {{-- Botón que abre el modal --}}
                <button type="button"
                        class="btn btn-sm btn-warning"
                        data-toggle="modal"
                        data-target="#modalDevolver{{ $asig->id }}"
                        title="Devolver">
                    <i class="fas fa-undo"></i> Liberar
                </button>
            </td>
        </tr>

        {{-- Modal de devolución — dentro del foreach --}}
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
                                          class="form-control"
                                          rows="3"
                                          placeholder="Ej: Equipo en buenas condiciones, ligeros rayones en la tapa..."></textarea>
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

        @empty
        <tr>
            <td colspan="5" class="text-center text-muted py-4">No hay asignaciones activas.</td>
        </tr>
        @endforelse
    </tbody>
</table>

</div>
</div>

@stop