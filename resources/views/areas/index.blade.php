@extends('adminlte::page')

@section('title', 'Áreas')

@section('content_header')
    <h1>Áreas</h1>
@stop

@section('content')

@role ('GerenteTIDS|AnalistaTI')
<a href="{{ route('areas.create') }}" class="btn btn-primary mb-3">
    Nueva Área
</a>
@endrole

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Activo</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($areas as $area)
        <tr>
            <td>{{ $area->nombre }}</td>
            <td>{{ $area->descripcion }}</td>
            <td>{{ $area->activo ? 'Sí' : 'No' }}</td>
            <td>
                <a href="{{ route('areas.edit', $area) }}" class="btn btn-sm btn-warning">Editar</a>
                <form action="{{ route('areas.destroy', $area) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">Eliminar</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@stop