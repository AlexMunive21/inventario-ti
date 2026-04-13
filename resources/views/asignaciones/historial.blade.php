@extends('adminlte::page')

@section('title','Historial Asignaciones')

@section('content_header')
<h1>Historial de Asignaciones</h1>
@stop

@section('content')

<div class="card">
<div class="card-body table-responsive">

<table class="table table-bordered">
<thead>
<tr>
<th>Equipo</th>
<th>Colaborador</th>
<th>Fecha Asignación</th>
<th>Fecha Devolución</th>
<th>Estatus</th>
<th>Observaciones Devolucion</th>
</tr>
</thead>

<tbody>

@foreach($asignaciones as $asig)

<tr>

<td>
{{ $asig->equipo->marca }}
{{ $asig->equipo->modelo }}
</td>

<td>
{{ $asig->colaborador->nombre }}
{{ $asig->colaborador->apellido_paterno }}
</td>

<td>{{ $asig->fecha_asignacion }}</td>

<td>{{ $asig->fecha_devolucion }}</td>

<td>
@if($asig->activa)
<span class="badge bg-success">Activa</span>
@else
<span class="badge bg-secondary">Liberada</span>
@endif
</td>

<td>{{ $asig->observaciones_devolucion }}</td>

</tr>

@endforeach

</tbody>

</table>

</div>
</div>

@stop
