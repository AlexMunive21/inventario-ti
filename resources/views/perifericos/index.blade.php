@extends('adminlte::page')

@section('title', 'Periféricos')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Periféricos</h1>
        <a href="{{ route('perifericos.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Periférico
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
                    <th>Tipo</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Área</th>
                    <th>Total</th>
                    <th>Disponibles</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($perifericos as $periferico)
                <tr>
                    <td><span class="badge badge-info">{{ ucfirst($periferico->tipo) }}</span></td>
                    <td>{{ $periferico->marca }}</td>
                    <td>{{ $periferico->modelo }}</td>
                    <td>{{ $periferico->area->nombre ?? '—' }}</td>
                    <td>{{ $periferico->cantidad_total }}</td>
                    <td>
                        <span class="badge badge-{{ $periferico->cantidad_disponible > 0 ? 'success' : 'danger' }}">
                            {{ $periferico->cantidad_disponible }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('perifericos.edit', $periferico) }}"
                           class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">No hay periféricos registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@stop