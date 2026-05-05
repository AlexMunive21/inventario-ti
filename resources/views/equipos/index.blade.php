@extends('adminlte::page')

@section('title', 'Equipos')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Equipos</h1>
        <a href="{{ route('equipos.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Nuevo Equipo
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

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
@endif

<div class="card">
    <div class="card-body table-responsive p-0">
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-dark">
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
                @forelse($equipos as $equipo)
                <tr>
                    <td>{{ $equipo->tipo_equipo }}</td>
                    <td>{{ $equipo->marca }}</td>
                    <td>{{ $equipo->modelo }}</td>
                    <td>{{ $equipo->numero_serie }}</td>
                    <td>{{ $equipo->area->nombre ?? '—' }}</td>
                    <td>{{ $equipo->ciudad->nombre ?? '—' }}</td>
                    <td>
                        @php
                            $badge = [
                                'disponible'    => 'success',
                                'asignado'      => 'primary',
                                'mantenimiento' => 'warning',
                                'baja'          => 'danger',
                            ][strtolower($equipo->estatus)] ?? 'secondary';
                        @endphp
                        <span class="badge badge-{{ $badge }}">{{ ucfirst($equipo->estatus) }}</span>
                    </td>
                    <td>
                        {{-- Ver --}}
                        <a href="{{ route('equipos.show', $equipo) }}"
                           class="btn btn-sm btn-info" title="Ver">
                            <i class="bi bi-eye"></i>
                        </a>

                        {{-- Editar --}}
                        <a href="{{ route('equipos.edit', $equipo) }}"
                           class="btn btn-sm btn-warning" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>

                        {{-- Responsiva — solo si está asignado --}}
                        @if($equipo->estatus === 'asignado')
                        <a href="{{ route('equipos.responsiva', $equipo->id) }}"
                           class="btn btn-sm btn-secondary" title="Responsiva">
                            <i class="bi bi-file-earmark-text"></i>
                        </a>
                        @endif

                        {{-- Pagaré — solo laptops asignadas --}}
                        @if(strtolower($equipo->tipo_equipo) === 'laptop' && $equipo->estatus === 'asignado')
                        <a href="{{ route('equipos.pagare', $equipo->id) }}"
                           class="btn btn-sm btn-dark" title="Pagaré">
                            <i class="bi bi-receipt"></i>
                        </a>
                        @endif

                        {{-- Dar de baja --}}
                        @role('GerenteTIDS')
                        @if($equipo->estatus !== 'baja')
                        <form action="{{ route('equipos.destroy', $equipo) }}"
                              method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('¿Dar de baja este equipo?')"
                                    title="Dar de baja">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                        @endif
                        @endrole
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">No hay equipos registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@stop