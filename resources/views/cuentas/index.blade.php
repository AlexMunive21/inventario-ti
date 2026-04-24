@extends('adminlte::page')

@section('title', 'Gestor de Cuentas')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Gestor de Cuentas</h1>
        <button class="btn btn-primary" data-toggle="modal" data-target="#modalNuevaCuenta">
            <i class="fas fa-plus"></i> Nueva Cuenta
        </button>
    </div>
@stop

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
@endif

@php
$etiquetas = [
    'email'      => ['label' => 'Correo',     'icon' => 'fas fa-envelope',  'color' => 'primary'],
    'servidor'   => ['label' => 'Servidor',   'icon' => 'fas fa-server',    'color' => 'dark'],
    'red_social' => ['label' => 'Red Social', 'icon' => 'fas fa-hashtag',   'color' => 'info'],
    'modem'      => ['label' => 'Módem',      'icon' => 'fas fa-wifi',      'color' => 'warning'],
    'camara'     => ['label' => 'Cámara IP',  'icon' => 'fas fa-camera',    'color' => 'secondary'],
    'sistema'    => ['label' => 'Sistema',    'icon' => 'fas fa-desktop',   'color' => 'success'],
    'otro'       => ['label' => 'Otro',       'icon' => 'fas fa-ellipsis-h','color' => 'danger'],
];
@endphp

@foreach($etiquetas as $tipo => $meta)
    @if(isset($cuentas[$tipo]) && $cuentas[$tipo]->count())
    <div class="card mb-3">
        <div class="card-header bg-{{ $meta['color'] }} {{ in_array($meta['color'], ['dark','primary','info','success','danger','secondary']) ? 'text-white' : '' }}">
            <h3 class="card-title">
                <i class="{{ $meta['icon'] }} mr-2"></i>{{ $meta['label'] }}
                <span class="badge badge-light ml-2">{{ $cuentas[$tipo]->count() }}</span>
            </h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-bordered table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Usuario</th>
                        <th>Contraseña</th>
                        <th>Colaborador / TI</th>
                        <th>Observaciones</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cuentas[$tipo] as $cuenta)
                    <tr>
                        <td><strong>{{ $cuenta->name }}</strong></td>
                        <td>
                            @if($cuenta->username)
                                <code>{{ $cuenta->username }}</code>
                                <button class="btn btn-xs btn-outline-secondary ml-1"
                                        onclick="copiar('{{ $cuenta->username }}')"
                                        title="Copiar usuario">
                                    <i class="fas fa-copy"></i>
                                </button>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if($cuenta->password)
                                <span class="password-text" id="pwd-{{ $cuenta->id }}"
                                      style="filter: blur(4px); cursor:pointer;"
                                      onclick="togglePassword('{{ $cuenta->id }}')">
                                    {{ $cuenta->password }}
                                </span>
                                <button class="btn btn-xs btn-outline-secondary ml-1"
                                        onclick="copiar('{{ $cuenta->password }}')"
                                        title="Copiar contraseña">
                                    <i class="fas fa-copy"></i>
                                </button>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if($cuenta->colaborador)
                                <i class="fas fa-user text-primary mr-1"></i>
                                {{ $cuenta->colaborador->nombre }} {{ $cuenta->colaborador->apellido_paterno }}
                            @else
                                <span class="badge badge-dark"><i class="fas fa-shield-alt mr-1"></i>TI</span>
                            @endif
                        </td>
                        <td>{{ $cuenta->observaciones ?? '—' }}</td>
                        <td>
                            <a href="{{ route('cuentas.edit', $cuenta) }}"
                               class="btn btn-sm btn-warning" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('cuentas.destroy', $cuenta) }}"
                                  method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"
                                        onclick="return confirm('¿Eliminar esta cuenta?')"
                                        title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
@endforeach

{{-- Modal nueva cuenta --}}
<div class="modal fade" id="modalNuevaCuenta" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('cuentas.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-key mr-2"></i>Nueva Cuenta</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nombre <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control"
                                       placeholder="Ej: Correo corporativo, Servidor Producción..." required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tipo <span class="text-danger">*</span></label>
                                <select name="type" class="form-control" required>
                                    <option value="">-- Selecciona --</option>
                                    <option value="email">Correo</option>
                                    <option value="servidor">Servidor</option>
                                    <option value="red_social">Red Social</option>
                                    <option value="modem">Módem</option>
                                    <option value="camara">Cámara IP</option>
                                    <option value="sistema">Sistema</option>
                                    <option value="otro">Otro</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Usuario</label>
                                <input type="text" name="username" class="form-control"
                                       placeholder="Usuario o email de acceso">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Contraseña</label>
                                <input type="text" name="password" class="form-control"
                                       placeholder="Contraseña">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Colaborador</label>
                        <select name="colaborador_id" class="form-control">
                            <option value="">Cuenta de TI</option>
                            @foreach($colaboradores as $col)
                                <option value="{{ $col->id }}">
                                    {{ $col->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@stop

@section('js')
<script>
function togglePassword(id) {
    const el = document.getElementById('pwd-' + id);
    if (el.style.filter === 'blur(4px)') {
        el.style.filter = 'none';
    } else {
        el.style.filter = 'blur(4px)';
    }
}

function copiar(texto) {
    navigator.clipboard.writeText(texto).then(() => {
        toastr.success('Copiado al portapapeles');
    });
}
</script>
@stop