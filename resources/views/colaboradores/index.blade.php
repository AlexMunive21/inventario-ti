@extends('adminlte::page')

@section('title', 'Colaboradores')

@section('content_header')
    <h1>Colaboradores</h1>
@stop

@section('content')

@role('GerenteTIDS|AnalistaTI')
<a href="{{ route('colaboradores.create') }}" class="btn btn-primary mb-3">
    Nuevo Colaborador
</a>
@endrole

<div class="card">
<div class="card-body table-responsive">

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Género</th>
            <th>Área</th>
            <th>Puesto</th>
            <th>Ciudad</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($colaboradores as $col)
        <tr>
            <td>
                {{ $col->nombre }}
                {{ $col->apellido_paterno }}
                {{ $col->apellido_materno }}
            </td>
            <td>{{ $col->genero }}</td>
            <td>{{ $col->area->nombre ?? '' }}</td>
            <td>{{ $col->puesto ?? '' }}</td>
            <td>{{ $col->ciudad->nombre ?? '' }}</td>
            <td>
                @role('GerenteTIDS|AnalistaTI|AnalistaDS')

                {{-- Ver Ficha --}}
                <a href="{{ route('colaboradores.show',$col) }}"
                class="btn btn-sm btn-info"
                title="Ver Ficha">
                    <i class="fas fa-id-card"></i>
                </a>
                @endrole

                @role('rh|GerenteTIDS|AnalistaTI|AnalistaDS')
                {{-- Ficha RRHH --}}
                <a href="{{ route('colaboradores.ficha_rrhh',$col) }}" 
                class="btn btn-sm btn-success" 
                title="Ver Ficha RRHH">
                    <i class="fas fa-users-cog"></i>
                </a>
                @endrole


                @role('GerenteTIDS|AnalistaTI')
                {{-- Editar --}}
                <a href="{{ route('colaboradores.edit',$col) }}"
                class="btn btn-sm btn-warning"
                title="Editar">
                    <i class="fas fa-edit"></i>
                </a>
                @endrole

                @role('GerenteTIDS')
                <form action="{{ route('colaboradores.baja',$col) }}"
                    method="POST"
                    style="display:inline;">
                    @csrf
                    @method('PUT')

                    <button class="btn btn-sm btn-danger"
                        onclick="return confirm('¿Dar de baja a este colaborador?')">
                        <i class="fas fa-user-slash"></i>
                    </button>
                </form>
                @endrole

            </td>
        </tr>
        @endforeach
    </tbody>
</table>

</div>
</div>

@stop