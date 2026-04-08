@extends('adminlte::page')

@section('title', 'Editar Colaborador')

@section('content_header')
    <h1>Editar Colaborador</h1>
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

        <form action="{{ route('colaboradores.update', $colaborador) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">

                <div class="col-md-4">
                    <label>Nombre</label>
                    <input type="text" name="nombre"
                           value="{{ $colaborador->nombre }}"
                           class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label>Apellido Paterno</label>
                    <input type="text" name="apellido_paterno"
                           value="{{ $colaborador->apellido_paterno }}"
                           class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label>Apellido Materno</label>
                    <input type="text" name="apellido_materno"
                           value="{{ $colaborador->apellido_materno }}"
                           class="form-control">
                </div>

            </div>

            <br>

            <div class="row">

                <div class="col-md-4">
                    <label>Género</label>
                    <select name="genero" class="form-control" required>
                        <option value="Hombre"
                            {{ $colaborador->genero == 'Hombre' ? 'selected' : '' }}>
                            Hombre
                        </option>
                        <option value="Mujer"
                            {{ $colaborador->genero == 'Mujer' ? 'selected' : '' }}>
                            Mujer
                        </option>
                        <option value="Otro"
                            {{ $colaborador->genero == 'Otro' ? 'selected' : '' }}>
                            Otro
                        </option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label>Correo</label>
                    <input type="email" name="correo"
                           value="{{ $colaborador->correo }}"
                           class="form-control">
                </div>

                <div class="col-md-4">
                    <label>Teléfono</label>
                    <input type="text" name="telefono"
                           value="{{ $colaborador->telefono }}"
                           class="form-control">
                </div>

            </div>

            <br>

            <div class="row">

                <div class="col-md-6">
                    <label>Área</label>
                    <select name="area_id" class="form-control" required>
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}"
                                {{ $colaborador->area_id == $area->id ? 'selected' : '' }}>
                                {{ $area->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                 <div class="col-md-6">
                    <label>Puesto</label>
                    <input type="text" name ="puesto" class="form-control"
                    value="{{ $colaborador->puesto }}" required>
                </div>

                <div class="col-md-6">
                    <label>Ciudad</label>
                    <select name="ciudad_id" class="form-control" required>
                        @foreach($ciudades as $ciudad)
                            <option value="{{ $ciudad->id }}"
                                {{ $colaborador->ciudad_id == $ciudad->id ? 'selected' : '' }}>
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
                    <option value="1"
                        {{ $colaborador->activo ? 'selected' : '' }}>
                        Sí
                    </option>
                    <option value="0"
                        {{ !$colaborador->activo ? 'selected' : '' }}>
                        No
                    </option>
                </select>
            </div>

            <br>

            <button type="submit" class="btn btn-success">Actualizar</button>
            <a href="{{ route('colaboradores.index') }}" class="btn btn-secondary">Cancelar</a>

        </form>

    </div>
</div>

@stop
