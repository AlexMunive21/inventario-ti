@extends('adminlte::page')

@section('title', 'Celulares')

@section('content_header')
    <h1>Celulares</h1>
@stop

@section('content')

    <a href="{{ route('celulares.create') }}" class="btn btn-primary mb-3">
        Nuevo Celular
    </a>

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

    <div class="card">
        <div class="card-body">

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Área</th>
                        <th>Ciudad</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>IMEI</th>
                        <th>Teléfono</th>
                        <th>Estatus</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($celulares as $celular)
                        <tr>
                            <td>{{ $celular->area->nombre ?? '-' }}</td>
                            <td>{{ $celular->ciudad->nombre ?? '-' }}</td>
                            <td>{{ $celular->marca }}</td>
                            <td>{{ $celular->modelo }}</td>
                            <td>{{ $celular->imei }}</td>
                            <td>{{ $celular->numero_telefono ?? '-' }}</td>
                            <td>
                                @if($celular->estatus == 'disponible')
                                    <span class="badge badge-success">Disponible</span>
                                @elseif($celular->estatus == 'asignado')
                                    <span class="badge badge-primary">Asignado</span>
                                @elseif($celular->estatus == 'mantenimiento')
                                    <span class="badge badge-warning">Mantenimiento</span>
                                @else
                                    <span class="badge badge-danger">Baja</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('celulares.edit', $celular) }}"
                                   class="btn btn-sm btn-warning">
                                    Editar
                                </a>

                                @role ('GerenteTIDS')

                                @if($celular->estatus !== 'baja')
                                    <form action="{{ route('celulares.destroy', $celular) }}"
                                          method="POST"
                                          style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger"
                                                onclick="return confirm('¿Seguro que deseas dar de baja este celular?')">
                                            Baja
                                        </button>
                                    </form>
                                @endif
                                @endrole
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">
                                No hay celulares registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

@stop