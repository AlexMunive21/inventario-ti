@extends('adminlte::page')

@section('title', 'Bajas de Colaboradores')

@section('content_header')
    <h1>Bajas de Colaboradores</h1>
@stop

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Colaboradores dados de baja</h3>
    </div>
    <div class="card-body table-responsive">

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-striped">
            <thead>
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
                    <td>{{ $col->nombre }} {{ $col->apellido_paterno }} {{ $col->apellido_materno }}</td>
                    <td>{{ $col->genero }}</td>
                    <td>{{ $col->area->nombre ?? '—' }}</td>
                    <td>{{ $col->puesto ?? '—' }}</td>
                    <td>{{ $col->ciudad->nombre ?? '—' }}</td>
                    <td>
                        {{ $col->fecha_baja ? \Carbon\Carbon::parse($col->fecha_baja)->format('d/m/Y') : '—' }}
                    </td>
                    <td>
                        @if($col->ultimo_equipo && $col->ultimo_equipo->equipo)
                            {{ $col->ultimo_equipo->equipo->marca }}
                            {{ $col->ultimo_equipo->equipo->modelo }}
                            <br>
                            <small class="text-muted">Serie: {{ $col->ultimo_equipo->equipo->numero_serie }}</small>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        @if($col->ultimo_celular && $col->ultimo_celular->celular)
                            {{ $col->ultimo_celular->celular->marca }}
                            {{ $col->ultimo_celular->celular->modelo }}
                            <br>
                            <small class="text-muted">Serie: {{ $col->ultimo_celular->celular->numero_serie }}</small>
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
                            <button class="btn btn-success btn-sm"
                                    onclick="return confirm('¿Reactivar a este colaborador?')"
                                    title="Reactivar">
                                <i class="fas fa-user-check"></i> Reactivar
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted">No hay colaboradores dados de baja.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</div>

@stop