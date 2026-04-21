@extends('adminlte::page')

@section('title', 'Cuentas')

@section('content_header')
    <h1>Cuentas</h1>
@stop

@section('content')

<div class="card">

    <div class="card-header">
        <button class="btn btn-primary" data-toggle="modal" data-target="#modalCuenta">
            Nueva Cuenta
        </button>
    </div>

    <div class="card-body table-responsive">

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Usuario</th>
                    <th>Contraseña</th>
                    <th>Tipo</th>
                    <th>Colaborador</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cuentas as $cuenta)
                <tr>
                    <td>{{ $cuenta->name }}</td>
                    <td>{{ $cuenta->username }}</td>
                    <td>{{ $cuenta->password }}</td>
                    <td>{{ $cuenta->type }}</td>
                    <td>{{ $cuenta->colaborador->nombre ?? 'TI' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>


{{-- MODAL --}}
<div class="modal fade" id="modalCuenta" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('cuentas.store') }}" method="POST">
            @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Nueva Cuenta</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" name="name" class="form-control" placeholder="Correo, Servidor, etc" required>
                    </div>

                    <div class="form-group">
                        <label>Usuario</label>
                        <input type="text" name="username" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Contraseña</label>
                        <input type="text" name="confidencial" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Tipo</label>
                        <select name="type" class="form-control" required>
                            <option value="">Selecciona</option>
                            <option value="email">Correo</option>
                            <option value="servidor">Servidor</option>
                            <option value="red_social">Red Social</option>
                            <option value="modem">Modem</option>
                            <option value="camara">Cámara IP</option>
                            <option value="sistema">Sistema</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>

                    <div>
                        <label>Observaciones</label>                            
                        <textarea name="observaciones" class="form-control" rows="3"></textarea>                        
                    </div>

                    <div class="form-group">
                        <label>Colaborador</label>
                        <select name="colaborador_id" class="form-control">
                            <option value="">Cuenta de TI</option>
                            @foreach($colaboradores as $col)
                                <option value="{{ $col->id }}">
                                    {{ $col->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cancelar
                    </button>
                    <button class="btn btn-success">
                        Guardar
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>

@stop