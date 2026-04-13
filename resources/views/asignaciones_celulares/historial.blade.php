@extends('adminlte::page')

@section('title', 'Historial Celulares')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Historial de Asignaciones de Celulares</h1>
        <a href="{{ route('asignaciones-celulares.index') }}" class="btn btn-secondary">
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
                    <th>Celular</th>
                    <th>IMEI</th>
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
                    <td>{{ $asig->celular->marca ?? '—' }} {{ $asig->celular->modelo ?? '' }}</td>
                    <td>{{ $asig->celular->imei ?? '—' }}</td>
                    <td>{{ $asig->colaborador->nombre ?? '—' }} {{ $asig->colaborador->apellido_paterno ?? '' }}</td>
                    <td>{{ \Carbon\Carbon::parse($asig->fecha_asignacion)->format('d/m/Y') }}</td>
                    <td>
                        @if($asig->fecha_devolucion)
                            {{ \Carbon\Carbon::parse($asig->fecha_devolucion)->format('d/m/Y') }}
                        @else
                            <span class="badge badge-primary">Activa</span>
                        @endif
                    </td>
                    <td>
                        @if(is_null($asig->fecha_devolucion))
                            <span class="badge badge-primary">Activa</span>
                        @else
                            <span class="badge badge-success">Devuelta</span>
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