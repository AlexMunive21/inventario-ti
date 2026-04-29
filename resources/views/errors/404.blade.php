@extends('adminlte::page')

@section('title', 'Página no encontrada')

@section('content_header')
    <h1>Página no encontrada</h1>
@stop

@section('content')

<div class="text-center py-5">
    <i class="fas fa-search fa-5x text-warning mb-4"></i>
    <h2 class="mb-2">404 — No encontrado</h2>
    <p class="text-muted mb-4">La página que buscas no existe o fue movida.</p>
    <a href="{{ route('dashboard') }}" class="btn btn-primary">
        <i class="fas fa-home"></i> Regresar al Dashboard
    </a>
</div>

@stop