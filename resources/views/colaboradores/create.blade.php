@extends('adminlte::page')

@section('title', 'Nuevo Colaborador')

@section('content_header')
    <h1>Registrar Colaborador</h1>
@stop

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-body">

        <form action="{{ route('colaboradores.store') }}" method="POST">
            @csrf

            <div class="row">

                <div class="col-md-4">
                    <label>Nombre</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label>Apellido Paterno</label>
                    <input type="text" name="apellido_paterno" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label>Apellido Materno</label>
                    <input type="text" name="apellido_materno" class="form-control">
                </div>

            </div>

            <br>

            <div class="row">

                <div class="col-md-4">
                    <label>Género</label>
                    <select name="genero" class="form-control" required>
                        <option value="">Seleccione</option>
                        <option value="Hombre">Hombre</option>
                        <option value="Mujer">Mujer</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label>Correo</label>
                    <input type="email" name="correo" class="form-control">
                </div>

                <div class="col-md-4">
                    <label>Teléfono</label>
                    <input type="text" name="telefono" class="form-control">
                </div>

            </div>

            <br>

            <div class="row">

                <div class="col-md-6">
                    <label>Área</label>
                    <select name="area_id" class="form-control" required>
                        <option value="">Seleccione</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}">
                                {{ $area->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label>Puesto</label>
                    <input type="text" name="puesto" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label>Ciudad</label>
                    <select name="ciudad_id" class="form-control" required>
                        <option value="">Seleccione</option>
                        @foreach($ciudades as $ciudad)
                            <option value="{{ $ciudad->id }}">
                                {{ $ciudad->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <br>

            <div class="form-group">
                <label>Activo</label>
                <select name="activo" class="form-control">
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>

            <br>

            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="{{ route('colaboradores.index') }}" class="btn btn-secondary">Cancelar</a>

        </form>

    </div>
</div>

@stop