@extends('adminlte::page')

@section('title', 'Equipos')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

@section('content_header')
    <h1>Equipos</h1>
@stop

@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<a href="{{ route('equipos.create') }}" class="btn btn-primary mb-3">
    Nuevo Equipo
</a>

<div class="card">
    <div class="card-body table-responsive">

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>No. Serie</th>
                    <th>Área</th>
                    <th>Ciudad</th>
                    <th>Estatus</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($equipos as $equipo)
                <tr>
                    <td>{{ $equipo->tipo_equipo }}</td>
                    <td>{{ $equipo->marca }}</td>
                    <td>{{ $equipo->modelo }}</td>
                    <td>{{ $equipo->numero_serie }}</td>
                    <td>{{ $equipo->area->nombre ?? '' }}</td>
                    <td>{{ $equipo->ciudad->nombre ?? '' }}</td>
                    <td>
                        <span class="badge 
                            @if($equipo->estatus == 'Activo') bg-success
                            @elseif($equipo->estatus == 'Disponible') bg-secondary
                            @elseif($equipo->estatus == 'Reparacion') bg-warning 
                            @else bg-danger 
                            @endif">
                            {{ $equipo->estatus }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('equipos.show', $equipo) }}" 
                        class="btn btn-sm btn-info"
                        title="Ver">
                            <i class="bi bi-eye"></i>
                        </a>

                        <a href="{{ route('equipos.edit', $equipo) }}" 
                        class="btn btn-sm btn-warning"
                        title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                        
                        <a href="{{ route('equipos.responsiva', $equipo->id) }}"
                        class="btn btn-sm btn-secondary"
                        title="Responsiva">
                            <i class="bi bi-file-earmark-text"></i>
                        </a>

                        <form action="{{ route('equipos.destroy', $equipo) }}" 
                            method="POST" 
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" title="Eliminar">
                                <i class="bi bi-trash"></i>
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