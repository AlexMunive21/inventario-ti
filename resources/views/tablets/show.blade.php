@extends('adminlte::page')

@section('title', 'Detalle Tablet')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Detalle de Tablet</h1>
        <a href="{{ route('tablets.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Regresar
        </a>
    </div>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <table class="table table-bordered">
            <tr><th>Marca</th><td>{{ $tablet->marca }}</td></tr>
            <tr><th>Modelo</th><td>{{ $tablet->modelo }}</td></tr>
            <tr><th>No. Serie</th><td>{{ $tablet->numero_serie }}</td></tr>
            <tr><th>Área</th><td>{{ $tablet->area->nombre ?? '—' }}</td></tr>
            <tr><th>Ciudad</th><td>{{ $tablet->ciudad->nombre ?? '—' }}</td></tr>
            <tr><th>Estatus</th><td>{{ ucfirst($tablet->estatus) }}</td></tr>
            <tr><th>Observaciones</th><td>{{ $tablet->observaciones ?? '—' }}</td></tr>
        </table>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header">
        <h3 class="card-title">Historial de asignaciones</h3>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Colaborador</th>
                    <th>Fecha asignación</th>
                    <th>Fecha devolución</th>
                    <th>Observaciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tablet->asignaciones as $asignacion)
                <tr>
                    <td>{{ $asignacion->colaborador->nombre ?? '—' }} {{ $asignacion->colaborador->apellido_paterno ?? '' }}</td>
                    <td>{{ \Carbon\Carbon::parse($asignacion->fecha_asignacion)->format('d/m/Y') }}</td>
                    <td>{{ $asignacion->fecha_devolucion ? \Carbon\Carbon::parse($asignacion->fecha_devolucion)->format('d/m/Y') : 'Activa' }}</td>
                    <td>{{ $asignacion->observaciones ?? '—' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">Sin historial de asignaciones.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@stop
