@extends('adminlte::page')

@section('title', 'Templates de Documentos')

@section('content_header')
    <h1><i class="fas fa-file-word mr-2"></i>Templates de Documentos</h1>
@stop

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
@endif

{{-- Subir nuevo template --}}
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h3 class="card-title"><i class="fas fa-upload mr-2"></i>Subir nuevo template</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('templates.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Nombre descriptivo <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                               placeholder="Ej: Responsiva Equipos 2025"
                               value="{{ old('nombre') }}" required>
                        @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Tipo de documento <span class="text-danger">*</span></label>
                        <select name="tipo" class="form-control @error('tipo') is-invalid @enderror" required>
                            <option value="">-- Selecciona --</option>
                            @foreach($etiquetas as $valor => $label)
                                <option value="{{ $valor }}" {{ old('tipo') == $valor ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('tipo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Archivo .docx <span class="text-danger">*</span></label>
                        <input type="file" name="archivo"
                               class="form-control-file @error('archivo') is-invalid @enderror"
                               accept=".docx" required>
                        @error('archivo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="text-muted">
                            Usa los placeholders: <code>{{Nombre}}</code> <code>{{ApellidoPaterno}}</code>
                            <code>{{ApellidoMaterno}}</code> <code>{{Puesto}}</code>
                            <code>{{MARCA}}</code> <code>{{MODELO}}</code> <code>{{SERIE}}</code>
                            <code>{{Dia}}</code> <code>{{Mes}}</code> <code>{{Anio}}</code>
                        </small>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-upload"></i> Subir Template
            </button>
        </form>
    </div>
</div>

{{-- Lista por tipo --}}
@foreach($etiquetas as $tipo => $label)
<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-file-word text-primary mr-2"></i>{{ $label }}
        </h3>
    </div>
    <div class="card-body p-0">
        <table class="table table-bordered mb-0">
            <thead class="thead-light">
                <tr>
                    <th>Nombre</th>
                    <th>Subido por</th>
                    <th>Fecha</th>
                    <th width="80">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($templates->get($tipo, collect()) as $template)
                <tr>
                    <td>{{ $template->nombre }}</td>
                    <td>{{ $template->user->name ?? '—' }}</td>
                    <td>{{ $template->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <form action="{{ route('templates.destroy', $template) }}"
                              method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('¿Eliminar este template?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-2">
                        <i class="fas fa-exclamation-triangle text-warning mr-1"></i>
                        Sin template — los documentos de este tipo no estarán disponibles.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endforeach

@stop