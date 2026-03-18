@extends('adminlte::page')

@section('title', 'Bajas de Colaboradores')

@section('content_header')
<h1>Bajas de Colaboradores</h1>
@stop

@section('content')

<div class="card">
<div class="card-body">

<table class="table table-bordered">
<thead>
<tr>
<th>ID</th>
<th>Nombre</th>
<th>Acciones</th>
</tr>
</thead>

<tbody>
@foreach($colaboradores as $col)
<tr>
<td>{{ $col->id }}</td>
<td>{{ $col->nombre }} {{ $col->apellido_paterno }}</td>

<td>

<form action="{{ route('colaboradores.reactivar',$col) }}"
      method="POST">

@csrf
@method('PUT')

<button class="btn btn-success btn-sm">
Reactivar
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