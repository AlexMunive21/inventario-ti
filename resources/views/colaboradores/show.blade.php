@extends('adminlte::page')

@section('title', 'Ficha Técnica')

@section('content_header')
    <h1>Ficha Técnica del Colaborador</h1>
@stop

@section('content')

<div class="card">
    <div class="card-header bg-primary text-white">
        Información General
    </div>
    <div class="card-body">
        <p><strong>Nombre:</strong> 
            {{ $colaborador->nombre }} 
            {{ $colaborador->apellido_paterno }} 
            {{ $colaborador->apellido_materno }}
        </p>

        <p><strong>Correo:</strong> {{ $colaborador->correo }}</p>
        <p><strong>Teléfono:</strong> {{ $colaborador->telefono }}</p>
        <p><strong>Área:</strong> {{ $colaborador->area->nombre ?? 'N/A' }}</p>
        <p><strong>Puesto:</strong> {{ $colaborador->puesto ?? 'N/A' }}</p>
        <p><strong>Ciudad:</strong> {{ $colaborador->ciudad->nombre ?? 'N/A' }}</p>

        <p><strong>Estatus:</strong> 
            @if($colaborador->activo)
                <span class="badge bg-success">Activo</span>
            @else
                <span class="badge bg-danger">Inactivo</span>
            @endif
        </p>
    </div>
</div>

<br>

{{-- ================= EQUIPOS ================= --}}
<div class="card">
    <div class="card-header bg-secondary text-white">
        Historial de Equipos
    </div>
    <div class="card-body">

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Equipo</th>
                    <th>Fecha Asignación</th>
                    <th>Fecha Devolución</th>
                </tr>
            </thead>
            <tbody>
                @forelse($colaborador->asignacionesEquipos as $asignacion)
                <tr>
                    <td>
                        {{ $asignacion->equipo->marca ?? '' }} 
                        - 
                        {{ $asignacion->equipo->modelo ?? '' }}
                    </td>

                    <td>{{ $asignacion->fecha_asignacion }}</td>

                    <td>
                        @if($asignacion->fecha_devolucion)
                            {{ $asignacion->fecha_devolucion }}
                        @else
                            <span class="badge bg-warning">Activo</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center text-muted">
                        No tiene equipos asignados
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</div>

<br>

{{-- ================= CELULARES ================= --}}
<div class="card">
    <div class="card-header bg-info text-white">
        Historial de Celulares
    </div>
    <div class="card-body">

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Celular</th>
                    <th>Fecha Asignación</th>
                    <th>Fecha Devolución</th>
                </tr>
            </thead>
            <tbody>
                @forelse($colaborador->asignacionesCelulares as $asignacion)
                <tr>
                    <td>
                        {{ $asignacion->celular->marca ?? '' }} 
                        - 
                        {{ $asignacion->celular->modelo ?? '' }}
                    </td>

                    <td>{{ $asignacion->fecha_asignacion }}</td>

                    <td>
                        @if($asignacion->fecha_devolucion)
                            {{ $asignacion->fecha_devolucion }}
                        @else
                            <span class="badge bg-warning">Activo</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center text-muted">
                        No tiene celulares asignados
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</div>

<br>



@stop