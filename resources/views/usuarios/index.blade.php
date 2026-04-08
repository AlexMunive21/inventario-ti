@extends('adminlte::page')

@section('title', 'Usuarios')

@section('content_header')
    <h1>Usuarios del Sistema</h1>
@stop

@section('content')

<a href="{{ route('usuarios.create') }}" class="btn btn-primary mb-3">
    Nuevo Usuario
</a>

<div class="card">
<div class="card-header bg-primary text-white">
Lista de Usuarios
</div>

<div class="card-body table-responsive">

<table class="table table-bordered table-striped">
<thead>
<tr>
    <th>ID</th>
    <th>Nombre</th>
    <th>Email</th>
    <th>Rol</th>
    <th>Acciones</th>
</tr>
</thead>

<tbody>
@foreach($usuarios as $usuario)
<tr>
    <td>{{ $usuario->id }}</td>
    <td>{{ $usuario->name }}</td>
    <td>{{ $usuario->email }}</td>

    <td>
        @if($usuario->roles->count())
            <span class="badge bg-info">
                {{ $usuario->roles->pluck('name')->implode(', ') }}
            </span>
        @else
            <span class="badge bg-secondary">Sin rol</span>
        @endif
    </td>

    <td>
        <a href="{{ route('usuarios.edit', $usuario->id) }}"
           class="btn btn-warning btn-sm">
           Editar
        </a>

        <form action="{{ route('usuarios.destroy', $usuario->id) }}"
              method="POST"
              style="display:inline">
            @csrf
            @method('DELETE')

            <button class="btn btn-danger btn-sm"
                onclick="return confirm('¿Eliminar usuario?')">
                Eliminar
            </button>
        </form>
    </td>
</tr>
@endforeach
</tbody>
</table>

</div>
</div>

@stop