@extends('adminlte::page')

@section('title', 'Equipos de Baja')

@section('content_header')
    <h1>Equipos de Baja</h1>
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
        <ul class="nav nav-tabs" id="bajasTab">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#equipos">
                    <i class="fas fa-laptop"></i> Equipos
                    <span class="badge badge-danger ml-1">{{ $equipos->count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#celulares">
                    <i class="fas fa-mobile-alt"></i> Celulares
                    <span class="badge badge-danger ml-1">{{ $celulares->count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tablets">
                    <i class="fas fa-tablet-alt"></i> Tablets
                    <span class="badge badge-danger ml-1">{{ $tablets->count() }}</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="card-body">
        <div class="tab-content">

            {{-- TAB EQUIPOS --}}
            <div class="tab-pane fade show active" id="equipos">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>No. Serie</th>
                            <th>Área</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($equipos as $equipo)
                        <tr>
                            <td>{{ $equipo->marca }}</td>
                            <td>{{ $equipo->modelo }}</td>
                            <td>{{ $equipo->numero_serie }}</td>
                            <td>{{ $equipo->area->nombre ?? '—' }}</td>
                            <td>
                                <button type="button"
                                        class="btn btn-sm btn-success"
                                        data-toggle="modal"
                                        data-target="#modalEquipo{{ $equipo->id }}">
                                    <i class="fas fa-redo"></i> Reactivar
                                </button>
                            </td>
                        </tr>

                        {{-- Modal reactivar equipo --}}
                        <div class="modal fade" id="modalEquipo{{ $equipo->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('bajas.equipos.reactivar', $equipo->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Reactivar equipo — {{ $equipo->marca }} {{ $equipo->modelo }}</h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Tipo de reparación</label>
                                                <select name="tipo_reparacion" class="form-control" required>
                                                    <option value="">-- Selecciona --</option>
                                                    <option value="reemplazo_pieza">Reemplazo de pieza</option>
                                                    <option value="mantenimiento">Mantenimiento</option>
                                                    <option value="limpieza">Limpieza</option>
                                                    <option value="otro">Otro</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Fecha de reactivación</label>
                                                <input type="date" name="fecha_reactivacion"
                                                       class="form-control"
                                                       value="{{ date('Y-m-d') }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Autorizó</label>
                                                <input type="text" name="autorizo"
                                                       class="form-control"
                                                       placeholder="Nombre de quien autorizó" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-success">Confirmar reactivación</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="fas fa-check-circle text-success"></i> No hay equipos de baja.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- TAB CELULARES --}}
            <div class="tab-pane fade" id="celulares">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>IMEI</th>
                            <th>Área</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($celulares as $celular)
                        <tr>
                            <td>{{ $celular->marca }}</td>
                            <td>{{ $celular->modelo }}</td>
                            <td>{{ $celular->imei }}</td>
                            <td>{{ $celular->area->nombre ?? '—' }}</td>
                            <td>
                                <button type="button"
                                        class="btn btn-sm btn-success"
                                        data-toggle="modal"
                                        data-target="#modalCelular{{ $celular->id }}">
                                    <i class="fas fa-redo"></i> Reactivar
                                </button>
                            </td>
                        </tr>

                        {{-- Modal reactivar celular --}}
                        <div class="modal fade" id="modalCelular{{ $celular->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('bajas.celulares.reactivar', $celular->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Reactivar celular — {{ $celular->marca }} {{ $celular->modelo }}</h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Tipo de reparación</label>
                                                <select name="tipo_reparacion" class="form-control" required>
                                                    <option value="">-- Selecciona --</option>
                                                    <option value="reemplazo_pieza">Reemplazo de pieza</option>
                                                    <option value="mantenimiento">Mantenimiento</option>
                                                    <option value="limpieza">Limpieza</option>
                                                    <option value="otro">Otro</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Fecha de reactivación</label>
                                                <input type="date" name="fecha_reactivacion"
                                                       class="form-control"
                                                       value="{{ date('Y-m-d') }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Autorizó</label>
                                                <input type="text" name="autorizo"
                                                       class="form-control"
                                                       placeholder="Nombre de quien autorizó" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-success">Confirmar reactivación</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="fas fa-check-circle text-success"></i> No hay celulares de baja.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- TAB TABLETS --}}
            <div class="tab-pane fade" id="tablets">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>No. Serie</th>
                            <th>Área</th>
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
                            <td>
                                <button type="button"
                                        class="btn btn-sm btn-success"
                                        data-toggle="modal"
                                        data-target="#modalTablet{{ $tablet->id }}">
                                    <i class="fas fa-redo"></i> Reactivar
                                </button>
                            </td>
                        </tr>

                        {{-- Modal reactivar tablet --}}
                        <div class="modal fade" id="modalTablet{{ $tablet->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('bajas.tablets.reactivar', $tablet->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Reactivar tablet — {{ $tablet->marca }} {{ $tablet->modelo }}</h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Tipo de reparación</label>
                                                <select name="tipo_reparacion" class="form-control" required>
                                                    <option value="">-- Selecciona --</option>
                                                    <option value="reemplazo_pieza">Reemplazo de pieza</option>
                                                    <option value="mantenimiento">Mantenimiento</option>
                                                    <option value="limpieza">Limpieza</option>
                                                    <option value="otro">Otro</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Fecha de reactivación</label>
                                                <input type="date" name="fecha_reactivacion"
                                                       class="form-control"
                                                       value="{{ date('Y-m-d') }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Autorizó</label>
                                                <input type="text" name="autorizo"
                                                       class="form-control"
                                                       placeholder="Nombre de quien autorizó" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-success">Confirmar reactivación</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="fas fa-check-circle text-success"></i> No hay tablets de baja.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

@stop