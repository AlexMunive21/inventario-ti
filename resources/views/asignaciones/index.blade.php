@extends('adminlte::page')

@section('title', 'Asignaciones')

@section('content_header')
    <h1>Asignaciones</h1>
@stop

@section('content')

<a href="{{ route('asignaciones.create') }}" class="btn btn-primary mb-3">
    Nueva Asignación
</a>
<a href="{{ route('asignaciones.historial') }}" class="btn btn-secondary mb-3">
    Historial de Asignaciones
</a>

<div class="card">
<div class="card-body table-responsive">

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Equipo</th>
            <th>Colaborador</th>
            <th>Fecha</th>
            <th>Estatus</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($asignaciones as $asig)
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
                <form action="{{ route('asignaciones.destroy',$asig) }}"
                      method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm">
                        Liberar
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

</div>
</div>

@stop