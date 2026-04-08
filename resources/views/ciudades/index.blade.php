@extends('adminlte::page')

@section('title', 'Ciudades')

@section('content_header')
    <h1>Ciudades</h1>
@stop

@section('content')

<a href="{{ route('ciudades.create') }}" class="btn btn-primary mb-3">
    Nueva Ciudad
</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Estado</th>
            <th>Activo</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($ciudades as $ciudad)
        <tr>
            <td>{{ $ciudad->id }}</td>
            <td>{{ $ciudad->nombre }}</td>
            <td>{{ $ciudad->estado }}</td>
            <td>{{ $ciudad->activo ? 'Sí' : 'No' }}</td>
            <td>
                <a href="{{ route('ciudades.edit', $ciudad) }}" class="btn btn-sm btn-warning">Editar</a>

                <form action="{{ route('ciudades.destroy', $ciudad) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn
                    -sm btn-danger">Eliminar</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@stop