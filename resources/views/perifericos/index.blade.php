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

                    @if($periferico->cantidad_disponible > 0)
                    <button type="button" class="btn btn-sm btn-danger"
                            data-toggle="modal"
                            data-target="#modalBaja{{ $periferico->id }}"
                            title="Dar de baja unidades">
                        <i class="fas fa-ban"></i>
                    </button>
                    @endif

                    @role('GerenteTIDS')
                    @if($periferico->cantidad_disponible == $periferico->cantidad_total)
                    <form action="{{ route('perifericos.destroy', $periferico) }}"
                        method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger"
                                onclick="return confirm('¿Eliminar este periférico completamente?')"
                                title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                    @endif
                    @endrole
                </td>
            </tr>

            {{-- Modal --}}
            <div class="modal fade" id="modalBaja{{ $periferico->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('perifericos.darDeBaja', $periferico) }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    Dar de baja — {{ $periferico->marca }} {{ $periferico->modelo }}
                                </h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <p class="text-muted">
                                    Disponibles: <strong>{{ $periferico->cantidad_disponible }}</strong> de
                                    <strong>{{ $periferico->cantidad_total }}</strong> en total.
                                </p>
                                <div class="form-group">
                                    <label>¿Cuántas unidades dar de baja?</label>
                                    <input type="number" name="cantidad_baja" class="form-control"
                                        min="1" max="{{ $periferico->cantidad_disponible }}"
                                        value="1" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-danger">Confirmar baja</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

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