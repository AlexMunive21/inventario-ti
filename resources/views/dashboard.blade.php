@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard Inventario TI</h1>
@stop

@section('content')

{{-- Tarjetas de Equipos — solo TI y Gerente --}}
@role('AnalistaTI|GerenteTIDS')
<div class="row">

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $disponibles }}</h3>
                <p>Equipos Disponibles</p>
            </div>
            <div class="icon">
                <i class="fas fa-laptop"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $asignados }}</h3>
                <p>Equipos Asignados</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-check"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $mantenimiento }}</h3>
                <p>En Mantenimiento</p>
            </div>
            <div class="icon">
                <i class="fas fa-tools"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $baja }}</h3>
                <p>Equipos de Baja</p>
            </div>
            <div class="icon">
                <i class="fas fa-times-circle"></i>
            </div>
        </div>
    </div>

</div>
@endrole

{{-- Colaboradores — todos los roles --}}
<div class="row">

    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $colaboradores }}</h3>
                <p>Colaboradores Activos</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>

    {{-- Bajas — solo RH y Gerente --}}
    @role('rh|GerenteTIDS')
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $bajas }}</h3>
                <p>Bajas de Colaboradores</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-slash"></i>
            </div>
        </div>
    </div>
    @endrole

</div>

@stop