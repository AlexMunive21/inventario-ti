@extends('adminlte::page')

@section('title', 'Componentes')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Componentes</h1>
        <a href="{{ route('componentes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Componente
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
    <div class="card-header p-0">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#cpus">
                    <i class="fas fa-microchip"></i> CPUs
                    <span class="badge badge-info ml-1">{{ $cpus->count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#monitores">
                    <i class="fas fa-desktop"></i> Monitores
                    <span class="badge badge-info ml-1">{{ $monitores->count() }}</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content">

            {{-- TAB CPUs --}}
            <div class="tab-pane fade show active" id="cpus">
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
                        @forelse($cpus as $cpu)
                        <tr>
                            <td>{{ $cpu->marca }}</td>
                            <td>{{ $cpu->modelo }}</td>
                            <td>{{ $cpu->numero_serie }}</td>
                            <td>{{ $cpu->area->nombre ?? '—' }}</td>
                            <td>{{ $cpu->ciudad->nombre ?? '—' }}</td>
                            <td>
                                @php
                                    $badge = ['disponible'=>'success','en_uso'=>'primary','mantenimiento'=>'warning','baja'=>'danger'][$cpu->estatus] ?? 'secondary';
                                @endphp
                                <span class="badge badge-{{ $badge }}">{{ ucfirst($cpu->estatus) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('componentes.edit', $cpu) }}"
                                   class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @role('GerenteTIDS')
                                <form action="{{ route('componentes.destroy', $cpu) }}"
                                      method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger"
                                            onclick="return confirm('¿Dar de baja este CPU?')">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                </form>
                                @endrole
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-3">No hay CPUs registrados.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- TAB Monitores --}}
            <div class="tab-pane fade" id="monitores">
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
                        @forelse($monitores as $monitor)
                        <tr>
                            <td>{{ $monitor->marca }}</td>
                            <td>{{ $monitor->modelo }}</td>
                            <td>{{ $monitor->numero_serie }}</td>
                            <td>{{ $monitor->area->nombre ?? '—' }}</td>
                            <td>{{ $monitor->ciudad->nombre ?? '—' }}</td>
                            <td>
                                @php
                                    $badge = ['disponible'=>'success','en_uso'=>'primary','mantenimiento'=>'warning','baja'=>'danger'][$monitor->estatus] ?? 'secondary';
                                @endphp
                                <span class="badge badge-{{ $badge }}">{{ ucfirst($monitor->estatus) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('componentes.edit', $monitor) }}"
                                   class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @role('GerenteTIDS')
                                <form action="{{ route('componentes.destroy', $monitor) }}"
                                      method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger"
                                            onclick="return confirm('¿Dar de baja este monitor?')">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                </form>
                                @endrole
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-3">No hay monitores registrados.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

@stop