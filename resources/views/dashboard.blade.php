@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard Inventario TI</h1>
@stop

@section('content')

{{-- ── EQUIPOS DE CÓMPUTO — AnalistaTI y Gerente ── --}}
@role('AnalistaTI|GerenteTIDS')
<h5 class="text-muted mb-2"><i class="fas fa-laptop mr-1"></i> Equipos de Cómputo</h5>
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner"><h3>{{ $disponibles }}</h3><p>Disponibles</p></div>
            <div class="icon"><i class="fas fa-check-circle"></i></div>
            <a href="{{ route('equipos.index') }}" class="small-box-footer">Ver equipos <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner"><h3>{{ $asignados }}</h3><p>Asignados</p></div>
            <div class="icon"><i class="fas fa-user-check"></i></div>
            <a href="{{ route('asignaciones.index') }}" class="small-box-footer">Ver asignaciones <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner"><h3>{{ $mantenimiento }}</h3><p>En Mantenimiento</p></div>
            <div class="icon"><i class="fas fa-tools"></i></div>
            <a href="{{ route('equipos.index') }}" class="small-box-footer">Ver equipos <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner"><h3>{{ $baja }}</h3><p>De Baja</p></div>
            <div class="icon"><i class="fas fa-times-circle"></i></div>
            <a href="{{ route('bajas.index') }}" class="small-box-footer">Ver bajas <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

{{-- ── CELULARES ── --}}
<h5 class="text-muted mb-2"><i class="fas fa-mobile-alt mr-1"></i> Celulares</h5>
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner"><h3>{{ $celularesDisponibles }}</h3><p>Disponibles</p></div>
            <div class="icon"><i class="fas fa-mobile-alt"></i></div>
            <a href="{{ route('celulares.index') }}" class="small-box-footer">Ver celulares <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner"><h3>{{ $celularesAsignados }}</h3><p>Asignados</p></div>
            <div class="icon"><i class="fas fa-user-check"></i></div>
            <a href="{{ route('asignaciones-celulares.index') }}" class="small-box-footer">Ver asignaciones <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner"><h3>{{ $celularesMantenimiento }}</h3><p>En Mantenimiento</p></div>
            <div class="icon"><i class="fas fa-tools"></i></div>
            <a href="{{ route('celulares.index') }}" class="small-box-footer">Ver celulares <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner"><h3>{{ $celularesBaja }}</h3><p>De Baja</p></div>
            <div class="icon"><i class="fas fa-times-circle"></i></div>
            <a href="{{ route('bajas.index') }}" class="small-box-footer">Ver bajas <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

{{-- ── TABLETS ── --}}
<h5 class="text-muted mb-2"><i class="fas fa-tablet-alt mr-1"></i> Tablets</h5>
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner"><h3>{{ $tabletsDisponibles }}</h3><p>Disponibles</p></div>
            <div class="icon"><i class="fas fa-tablet-alt"></i></div>
            <a href="{{ route('tablets.index') }}" class="small-box-footer">Ver tablets <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner"><h3>{{ $tabletsAsignadas }}</h3><p>Asignadas</p></div>
            <div class="icon"><i class="fas fa-user-check"></i></div>
            <a href="{{ route('asignaciones-tablets.index') }}" class="small-box-footer">Ver asignaciones <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner"><h3>{{ $tabletsMantenimiento }}</h3><p>En Mantenimiento</p></div>
            <div class="icon"><i class="fas fa-tools"></i></div>
            <a href="{{ route('tablets.index') }}" class="small-box-footer">Ver tablets <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner"><h3>{{ $tabletsBaja }}</h3><p>De Baja</p></div>
            <div class="icon"><i class="fas fa-times-circle"></i></div>
            <a href="{{ route('bajas.index') }}" class="small-box-footer">Ver bajas <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

{{-- ── COMPONENTES ── --}}
<h5 class="text-muted mb-2"><i class="fas fa-microchip mr-1"></i> Componentes PC Escritorio</h5>
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-teal" style="background-color:#00897b!important;">
            <div class="inner"><h3>{{ $cpusDisponibles }}</h3><p>CPUs Disponibles</p></div>
            <div class="icon"><i class="fas fa-microchip"></i></div>
            <a href="{{ route('componentes.index') }}" class="small-box-footer">Ver componentes <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box" style="background-color:#5c6bc0!important; color:white;">
            <div class="inner"><h3>{{ $monitoresDisponibles }}</h3><p>Monitores Disponibles</p></div>
            <div class="icon"><i class="fas fa-desktop"></i></div>
            <a href="{{ route('componentes.index') }}" class="small-box-footer">Ver componentes <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner"><h3>{{ $componentesMantenimiento }}</h3><p>En Mantenimiento</p></div>
            <div class="icon"><i class="fas fa-tools"></i></div>
            <a href="{{ route('componentes.index') }}" class="small-box-footer">Ver componentes <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner"><h3>{{ $componentesBaja }}</h3><p>De Baja</p></div>
            <div class="icon"><i class="fas fa-times-circle"></i></div>
            <a href="{{ route('bajas.index') }}" class="small-box-footer">Ver bajas <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>
@endrole

{{-- ── COLABORADORES — todos los roles ── --}}
<h5 class="text-muted mb-2"><i class="fas fa-users mr-1"></i> Colaboradores</h5>
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner"><h3>{{ $colaboradores }}</h3><p>Activos</p></div>
            <div class="icon"><i class="fas fa-users"></i></div>
            <a href="{{ route('colaboradores.index') }}" class="small-box-footer">Ver colaboradores <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    @role('rh|GerenteTIDS')
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner"><h3>{{ $bajas }}</h3><p>Bajas</p></div>
            <div class="icon"><i class="fas fa-user-slash"></i></div>
            <a href="{{ route('colaboradores.bajas') }}" class="small-box-footer">Ver bajas <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    @endrole
</div>

{{-- ── DOCUMENTOS Y CUENTAS — AnalistaTI, AnalistaDS y Gerente ── --}}
@role('AnalistaTI|AnalistaDS|GerenteTIDS')
<h5 class="text-muted mb-2"><i class="fas fa-file-alt mr-1"></i> Documentos y Cuentas</h5>
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box" style="background-color:#f57c00!important; color:white;">
            <div class="inner"><h3>{{ $pdfsPendientes }}</h3><p>PDFs pendientes de firma</p></div>
            <div class="icon"><i class="fas fa-file-signature"></i></div>
            <a href="{{ route('asignaciones.index') }}" class="small-box-footer">Ver asignaciones <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box" style="background-color:#43a047!important; color:white;">
            <div class="inner"><h3>{{ $totalTemplates }}</h3><p>Templates subidos</p></div>
            <div class="icon"><i class="fas fa-file-word"></i></div>
            <a href="{{ route('templates.index') }}" class="small-box-footer">
                @if($tiposSinTemplate > 0)
                    ⚠ {{ $tiposSinTemplate }} tipo(s) sin template
                @else
                    Todos los tipos tienen template ✓
                @endif
                <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box" style="background-color:#546e7a!important; color:white;">
            <div class="inner"><h3>{{ $totalCuentas }}</h3><p>Cuentas registradas</p></div>
            <div class="icon"><i class="fas fa-key"></i></div>
            <a href="{{ route('cuentas.index') }}" class="small-box-footer">Ver cuentas <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>
@endrole

{{-- ── FILA INFERIOR: Gráfica + Asignaciones recientes ── --}}
@role('AnalistaTI|AnalistaDS|GerenteTIDS')
<div class="row mt-2">

    <!-- {{-- Gráfica bajas mensuales --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-bar mr-2"></i>Bajas de Colaboradores — Últimos 6 meses</h3>
            </div>
            <div class="card-body">
                <canvas id="graficaBajas" height="120"></canvas>
            </div>
        </div>
    </div> -->

    {{-- Asignaciones activas recientes --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-clock mr-2"></i>Asignaciones activas recientes</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm table-bordered mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Equipo</th>
                            <th>Colaborador</th>
                            <th>Desde</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($asignacionesRecientes as $asig)
                        <tr>
                            <td>{{ $asig->equipo->marca ?? '—' }} {{ $asig->equipo->modelo ?? '' }}</td>
                            <td>{{ $asig->colaborador->nombre ?? '—' }} {{ $asig->colaborador->apellido_paterno ?? '' }}</td>
                            <td>{{ \Carbon\Carbon::parse($asig->fecha_asignacion)->format('d/m/Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-3">Sin asignaciones activas.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer text-right">
                <a href="{{ route('asignaciones.index') }}" class="btn btn-sm btn-primary">
                    Ver todas <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

</div>
@endrole

@stop

<!-- @section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('graficaBajas');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($meses),
                datasets: [{
                    label: 'Bajas de colaboradores',
                    data: @json($bajasMensuales),
                    backgroundColor: 'rgba(220, 53, 69, 0.7)',
                    borderColor: 'rgba(220, 53, 69, 1)',
                    borderWidth: 1,
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    }
</script>
@stop -->