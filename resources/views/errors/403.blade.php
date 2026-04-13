@extends('adminlte::page')

@section('title', 'Acceso denegado')

@section('content_header')
    <h1>Acceso denegado</h1>
@stop

@section('content')

<div class="text-center py-5">
    <i class="fas fa-lock fa-5x text-danger mb-4"></i>
    <h2 class="mb-2">403 — Sin permisos</h2>
    <p class="text-muted mb-4">No tienes permisos para acceder a esta sección.</p>
    <a href="{{ route('dashboard') }}" class="btn btn-primary">
        <i class="fas fa-home"></i> Regresar al Dashboard
    </a>
</div>

@stop