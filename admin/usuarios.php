<?php
session_start();
require_once '../config/auth.php';
include('../constant.php');

// Solo administradores pueden acceder
requerirAdministrador();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestión de Usuarios - Administración INDEL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="<?= URL_HOST ?>css/style.css">
    <link rel="stylesheet" href="<?= URL_HOST ?>css/admin.css">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">
                <i class="fas fa-users"></i> Gestión de Usuarios y Grupos
            </span>
            <div>
                <a href="index.php" class="btn btn-outline-light btn-sm me-2">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
                <a href="logout.php" class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-sign-out-alt"></i> Salir
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <ul class="nav nav-tabs" id="adminTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="usuarios-tab" data-bs-toggle="tab" data-bs-target="#usuarios" type="button" role="tab">
                    <i class="fas fa-user"></i> Usuarios
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="grupos-tab" data-bs-toggle="tab" data-bs-target="#grupos" type="button" role="tab">
                    <i class="fas fa-users-cog"></i> Grupos
                </button>
            </li>
        </ul>

        <div class="tab-content mt-4" id="adminTabsContent">
            <!-- Tab Usuarios -->
            <div class="tab-pane fade show active" id="usuarios" role="tabpanel">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-user"></i> Usuarios</h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalUsuario" onclick="abrirModalNuevoUsuario()">
                            <i class="fas fa-plus"></i> Nuevo Usuario
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaUsuarios">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Usuario</th>
                                        <th>Email</th>
                                        <th>Nombre</th>
                                        <th>Grupos</th>
                                        <th>Estado</th>
                                        <th>Último Acceso</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyUsuarios">
                                    <!-- Se llena dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Grupos -->
            <div class="tab-pane fade" id="grupos" role="tabpanel">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-users-cog"></i> Grupos</h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalGrupo" onclick="abrirModalNuevoGrupo()">
                            <i class="fas fa-plus"></i> Nuevo Grupo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaGrupos">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Usuarios</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyGrupos">
                                    <!-- Se llena dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Usuario -->
    <div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="modalUsuarioLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalUsuarioLabel">Nuevo Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formUsuario">
                        <input type="hidden" id="usuario_id" name="id">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Usuario <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="apellido" class="form-label">Apellido</label>
                                <input type="text" class="form-control" id="apellido" name="apellido">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña <span id="passwordRequired" class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password" name="password">
                            <small class="form-text text-muted">Dejar en blanco para mantener la contraseña actual (solo al editar)</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Grupos</label>
                            <div id="gruposCheckboxes">
                                <!-- Se llena dinámicamente -->
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="usuario_activo" name="activo" checked>
                                <label class="form-check-label" for="usuario_activo">
                                    Usuario Activo
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarUsuario()">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Grupo -->
    <div class="modal fade" id="modalGrupo" tabindex="-1" aria-labelledby="modalGrupoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalGrupoLabel">Nuevo Grupo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formGrupo">
                        <input type="hidden" id="grupo_id" name="id">
                        
                        <div class="mb-3">
                            <label for="grupo_nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="grupo_nombre" name="nombre" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="grupo_descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="grupo_descripcion" name="descripcion" rows="3"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="grupo_activo" name="activo" checked>
                                <label class="form-check-label" for="grupo_activo">
                                    Grupo Activo
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarGrupo()">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= URL_HOST ?>js/admin-usuarios.js"></script>
</body>
</html>

