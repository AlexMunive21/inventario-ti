@extends('adminlte::page')

@section('title', 'Equipos de Escritorio')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Equipos de Escritorio</h1>
        <a href="{{ route('equipos-escritorio.create') }}" class="btn btn-primary">
            <i class="fas fa-tools"></i> Armar Equipo
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

<div class="card">
    <div class="card-body table-responsive p-0">
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Nombre</th>
                    <th>CPU</th>
                    <th>Monitores</th>
                    <th>Área</th>
                    <th>Ciudad</th>
                    <th>Estatus</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($equipos as $equipo)
                <tr>
                    <td><strong>{{ $equipo->nombre }}</strong></td>
                    <td>
                        @if($equipo->cpu)
                            {{ $equipo->cpu->marca }} {{ $equipo->cpu->modelo }}
                            <small class="text-muted d-block">{{ $equipo->cpu->numero_serie }}</small>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        @foreach($equipo->monitores as $monitor)
                            <span class="badge badge-secondary">
                                {{ $monitor->marca }} {{ $monitor->modelo }}
                            </span>
                        @endforeach
                    </td>
                    <td>{{ $equipo->area->nombre ?? '—' }}</td>
                    <td>{{ $equipo->ciudad->nombre ?? '—' }}</td>
                    <td>
                        @php
                            $badge = ['disponible'=>'success','asignado'=>'primary','mantenimiento'=>'warning','baja'=>'danger'][$equipo->estatus] ?? 'secondary';
                        @endphp
                        <span class="badge badge-{{ $badge }}">{{ ucfirst($equipo->estatus) }}</span>
                    </td>
                    <td>
                        <a href="{{ route('equipos-escritorio.show', $equipo) }}"
                           class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">No hay equipos de escritorio registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@stop