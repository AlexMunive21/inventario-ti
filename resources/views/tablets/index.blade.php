@extends('adminlte::page')

@section('title', 'Tablets')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Tablets</h1>
        <a href="{{ route('tablets.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Tablet
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
                @forelse($tablets as $tablet)
                <tr>
                    <td>{{ $tablet->marca }}</td>
                    <td>{{ $tablet->modelo }}</td>
                    <td>{{ $tablet->numero_serie }}</td>
                    <td>{{ $tablet->area->nombre ?? '—' }}</td>
                    <td>{{ $tablet->ciudad->nombre ?? '—' }}</td>
                    <td>
                        @php
                            $badge = [
                                'disponible'   => 'success',
                                'asignado'     => 'primary',
                                'mantenimiento'=> 'warning',
                                'baja'         => 'danger',
                            ][$tablet->estatus] ?? 'secondary';
                        @endphp
                        <span class="badge badge-{{ $badge }}">{{ ucfirst($tablet->estatus) }}</span>
                    </td>
                    <td>
                        <a href="{{ route('tablets.show', $tablet) }}"
                           class="btn btn-sm btn-info" title="Ver">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('tablets.edit', $tablet) }}"
                           class="btn btn-sm btn-warning" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                        @role('GerenteTIDS')
                        <form action="{{ route('tablets.destroy', $tablet) }}"
                              method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('¿Dar de baja esta tablet?')"
                                    title="Dar de baja">
                                <i class="fas fa-ban"></i>
                            </button>
                        </form>
                        @endrole
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">No hay tablets registradas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@stop
