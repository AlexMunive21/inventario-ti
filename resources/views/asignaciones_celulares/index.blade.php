@extends('adminlte::page')

@section('title', 'Asignaciones de Celulares')

@section('content_header')
    <h1>Asignaciones de Celulares</h1>
@stop

@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="mb-3">
    <a href="{{ route('asignaciones-celulares.create') }}" class="btn btn-primary">
        Nueva Asignación
    </a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Celular</th>
                    <th>IMEI</th>
                    <th>Colaborador</th>
                    <th>Fecha Asignación</th>
                    <th>Fecha Devolución</th>
                    <th>Estatus</th>
                    <th width="150">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($asignaciones as $asignacion)
                    <tr>
                        <td>{{ $asignacion->id }}</td>

                        <td>
                            {{ $asignacion->celular->marca }} 
                            {{ $asignacion->celular->modelo }}
                        </td>

                        <td>{{ $asignacion->celular->imei }}</td>

                        <td>
                            {{ $asignacion->colaborador->nombre }}
                            {{ $asignacion->colaborador->apellido_paterno }}
                        </td>

                        <td>{{ $asignacion->fecha_asignacion }}</td>

                        <td>
                            {{ $asignacion->fecha_devolucion ?? '-' }}
                        </td>

                        <td>
                            @if(is_null($asignacion->fecha_devolucion))
                                <span class="badge bg-primary">Activa</span>
                            @else
                                <span class="badge bg-success">Devuelta</span>
                            @endif
                        </td>

                        <td>
                            @if(is_null($asignacion->fecha_devolucion))
                                <form action="{{ route('asignaciones-celulares.devolver', $asignacion->id) }}" 
                                      method="POST" 
                                      style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <button class="btn btn-sm btn-warning"
                                            onclick="return confirm('¿Confirmar devolución del celular?')">
                                        Devolver
                                    </button>
                                </form>
                            @else
                                <span class="text-muted">Sin acciones</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">
                            No hay asignaciones registradas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@stop