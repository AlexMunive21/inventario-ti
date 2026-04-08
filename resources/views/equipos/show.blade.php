@extends('adminlte::page')

@section('title', 'Detalle del Equipo')

@section('content_header')
    <h1>Detalle del Equipo</h1>
@stop

@section('content')

<div class="card">
    <div class="card-body">

        <h4><strong>Equipo:</strong> {{ $equipo->nombre }}</h4>
        <p><strong>Serie:</strong> {{ $equipo->numero_serie }}</p>
        <p><strong>Estatus actual:</strong> {{ $equipo->estatus }}</p>

        <hr>

        <h5>Historial de Asignaciones</h5>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Colaborador</th>
                    <th>Fecha Asignación</th>
                    <th>Estatus</th>
                    <th>Fecha Liberación</th>
                </tr>
            </thead>
            <tbody>
                @forelse($equipo->asignaciones as $asignacion)
                    <tr>
                        <td>{{ $asignacion->colaborador->nombre }}</td>
                        <td>{{ $asignacion->fecha_asignacion }}</td>
                        <td>
                            {{ $asignacion->activa ? 'Activo' : 'Liberado' }}
                        </td>

                        <td>{{ $asignacion->fecha_devolucion ? $asignacion->fecha_devolucion : 'No devuelto' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">Sin historial</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</div>

@stop