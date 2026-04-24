@extends('adminlte::page')

@section('title', 'Editar Cuenta')

@section('content_header')
    <h1>Editar Cuenta</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('cuentas.update', $cuenta) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control"
                               value="{{ old('name', $cuenta->name) }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Tipo <span class="text-danger">*</span></label>
                        <select name="type" class="form-control" required>
                            <option value="email"      {{ $cuenta->type == 'email'      ? 'selected' : '' }}>Correo</option>
                            <option value="servidor"   {{ $cuenta->type == 'servidor'   ? 'selected' : '' }}>Servidor</option>
                            <option value="red_social" {{ $cuenta->type == 'red_social' ? 'selected' : '' }}>Red Social</option>
                            <option value="modem"      {{ $cuenta->type == 'modem'      ? 'selected' : '' }}>Módem</option>
                            <option value="camara"     {{ $cuenta->type == 'camara'     ? 'selected' : '' }}>Cámara IP</option>
                            <option value="sistema"    {{ $cuenta->type == 'sistema'    ? 'selected' : '' }}>Sistema</option>
                            <option value="otro"       {{ $cuenta->type == 'otro'       ? 'selected' : '' }}>Otro</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Usuario</label>
                        <input type="text" name="username" class="form-control"
                               value="{{ old('username', $cuenta->username) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Contraseña <small class="text-muted">(deja vacío para no cambiarla)</small></label>
                        <input type="text" name="password" class="form-control"
                               placeholder="Nueva contraseña">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Colaborador</label>
                        <select name="colaborador_id" class="form-control">
                            <option value="">— Cuenta de TI —</option>
                            @foreach($colaboradores as $col)
                                <option value="{{ $col->id }}"
                                    {{ $cuenta->colaborador_id == $col->id ? 'selected' : '' }}>
                                    {{ $col->nombre }} {{ $col->apellido_paterno }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Observaciones</label>
                        <textarea name="observaciones" class="form-control" rows="2">{{ old('observaciones', $cuenta->observaciones) }}</textarea>
                    </div>
                </div>
            </div>
            <a href="{{ route('cuentas.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Actualizar
            </button>
        </form>
    </div>
</div>
@stop