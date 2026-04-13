@extends('adminlte::page')

@section('title', 'Bajas de Colaboradores')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Bajas de Colaboradores</h1>
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
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-user-slash mr-2"></i>
            Colaboradores dados de baja
        </h3>
        <div class="card-tools">
            <span class="badge badge-danger">{{ $colaboradores->count() }} bajas</span>
        </div>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Género</th>
                    <th>Área</th>
                    <th>Puesto</th>
                    <th>Ciudad</th>
                    <th>Fecha de baja</th>
                    <th>Último equipo</th>
                    <th>Último celular</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($colaboradores as $col)
                <tr>
                    <td>
                        <strong>{{ $col->nombre }} {{ $col->apellido_paterno }}</strong>
                        @if($col->apellido_materno)
                            {{ $col->apellido_materno }}
                        @endif
                    </td>
                    <td>{{ $col->genero }}</td>
                    <td>{{ $col->area->nombre ?? '—' }}</td>
                    <td>{{ $col->puesto ?? '—' }}</td>
                    <td>{{ $col->ciudad->nombre ?? '—' }}</td>
                    <td>
                        @if($col->fecha_baja)
                            <span class="badge badge-danger">
                                {{ \Carbon\Carbon::parse($col->fecha_baja)->format('d/m/Y') }}
                            </span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        @if($col->ultimo_equipo && $col->ultimo_equipo->equipo)
                            <small>
                                <strong>{{ $col->ultimo_equipo->equipo->marca }}</strong>
                                {{ $col->ultimo_equipo->equipo->modelo }}<br>
                                <span class="text-muted">Serie: {{ $col->ultimo_equipo->equipo->numero_serie }}</span>
                            </small>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        @if($col->ultimo_celular && $col->ultimo_celular->celular)
                            <small>
                                <strong>{{ $col->ultimo_celular->celular->marca }}</strong>
                                {{ $col->ultimo_celular->celular->modelo }}<br>
                                <span class="text-muted">IMEI: {{ $col->ultimo_celular->celular->imei }}</span>
                            </small>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('colaboradores.reactivar', $col) }}"
                              method="POST"
                              style="display:inline;">
                            @csrf
                            @method('PUT')
                            <button type="submit"
                                    class="btn btn-success btn-sm"
                                    onclick="return confirm('¿Reactivar a {{ $col->nombre }} {{ $col->apellido_paterno }}?')"
                                    title="Reactivar">
                                <i class="fas fa-user-check"></i> Reactivar
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">
                        <i class="fas fa-check-circle fa-2x mb-2 d-block text-success"></i>
                        No hay colaboradores dados de baja.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@stop