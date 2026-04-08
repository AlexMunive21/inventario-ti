@extends('adminlte::page')

@section('title', 'Ficha RRHH')

@section('content_header')
<h1>Ficha de Colaborador</h1>
@stop

@section('content')

<div class="card">
<div class="card-header bg-primary text-white">
Información del Colaborador
</div>

<div class="card-body">

<p><strong>Nombre:</strong>
{{ $colaborador->nombre }}
{{ $colaborador->apellido_paterno }}
{{ $colaborador->apellido_materno }}
</p>

<p><strong>Puesto:</strong> {{ $colaborador->puesto ?? 'N/A' }}</p>

<p><strong>Área:</strong> {{ $colaborador->area->nombre ?? 'N/A' }}</p>

</div>
</div>

<br>

<div class="card">
<div class="card-header bg-success text-white">
Equipo Asignado
</div>

<div class="card-body">

@if($equipo)

<p>
<strong>Equipo:</strong>
{{ $equipo->marca }} - {{ $equipo->modelo }}
</p>

<p>
<strong>Serie:</strong>
{{ $equipo->numero_serie }}
</p>

<a href="{{ asset('storage/responsivas/'.$equipo->responsiva) }}"
class="btn btn-danger">

Descargar Responsiva PDF

</a>

@else

<span class="badge bg-secondary">
No tiene equipo asignado
</span>

@endif

</div>
</div>

<br>

<div class="card">
<div class="card-header bg-info text-white">
Celular Asignado
</div>

<div class="card-body">

@if($celular)

<p>
<strong>Celular:</strong>
{{ $celular->marca }} - {{ $celular->modelo }}
</p>

<p>
<strong>IMEI:</strong>
{{ $celular->imei }}
</p>

<a href="{{ asset('storage/responsivas/'.$celular->responsiva) }}"
class="btn btn-danger">

Descargar Responsiva PDF

</a>

@else

<span class="badge bg-secondary">
No tiene celular asignado
</span>

@endif

</div>
</div>

@stop