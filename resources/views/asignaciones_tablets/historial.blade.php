@extends('adminlte::page')

@section('title', 'Historial Tablets')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Historial de Asignaciones de Tablets</h1>
        <a href="{{ route('asignaciones-tablets.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Regresar
        </a>
    </div>
@stop

@section('content')

<div class="card">
    <div class="card-body table-responsive p-0">
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Tablet</th>
                    <th>No. Serie</th>
                    <th>Colaborador</th>
                    <th>Fecha asignación</th>
                    <th>Fecha devolución</th>
                    <th>Estatus</th>
                    <th>Condición en devolución</th>
                </tr>
            </thead>
            <tbody>
                @forelse($asignaciones as $asig)
                <tr>
                    <td>{{ $asig->tablet->marca ?? '—' }} {{ $asig->tablet->modelo ?? '' }}</td>
                    <td>{{ $asig->tablet->numero_serie ?? '—' }}</td>
                    <td>{{ $asig->colaborador->nombre ?? '—' }} {{ $asig->colaborador->apellido_paterno ?? '' }}</td>
                    <td>{{ \Carbon\Carbon::parse($asig->fecha_asignacion)->format('d/m/Y') }}</td>
                    <td>
                        @if($asig->fecha_devolucion)
                            {{ \Carbon\Carbon::parse($asig->fecha_devolucion)->format('d/m/Y') }}
                        @else
                            <span class="badge badge-success">Activa</span>
                        @endif
                    </td>
                    <td>
                        @if(is_null($asig->fecha_devolucion))
                            <span class="badge badge-success">Activa</span>
                        @else
                            <span class="badge badge-secondary">Devuelta</span>
                        @endif
                    </td>
                    <td>{{ $asig->observaciones_devolucion ?? '—' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">No hay registros.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@stop