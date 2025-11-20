<?php

require_once '../config/auth.php';

// Verificar autenticación
requerirAutenticacion();
?>
<body>
    <!-- <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">
                <i class="fas fa-cog"></i> Administración INDEL
            </span>
            <div>
                <span class="text-light me-3">
                    <i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['admin_nombre'] ?? $_SESSION['admin_username'] ?? 'Usuario') ?>
                </span>
                <a href="../promociones.php" class="btn btn-outline-light btn-sm me-2" target="_blank">
                    <i class="fas fa-eye"></i> Ver Promociones
                </a>
                <a href="../noticias.php" class="btn btn-outline-light btn-sm me-2" target="_blank">
                    <i class="fas fa-newspaper"></i> Ver Noticias
                </a>
                <?php if (isset($_SESSION['es_administrador']) && $_SESSION['es_administrador']): ?>
                <a href="usuarios.php" class="btn btn-outline-info btn-sm me-2">
                    <i class="fas fa-users"></i> Usuarios
                </a>
                <?php endif; ?>
                <a href="logout.php" class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-sign-out-alt"></i> Salir
                </a>
            </div>
        </div>
    </nav> -->

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-tags"></i> Gestión de Promociones</h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPromocion" onclick="abrirModalNuevo()">
                            <i class="fas fa-plus"></i> Nueva Promoción
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaPromociones">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Imagen</th>
                                        <th>Título</th>
                                        <th>Fecha Inicio</th>
                                        <th>Fecha Fin</th>
                                        <th>Estado</th>
                                        <th>Orden</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyPromociones">
                                    <!-- Se llena dinámicamente con JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>       
    </div>

     <!-- Modal para crear/editar promoción -->
    <div class="modal fade" id="modalPromocion" tabindex="-1" aria-labelledby="modalPromocionLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPromocionLabel">Nueva Promoción</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formPromocion" enctype="multipart/form-data">
                        <input type="hidden" id="promocion_id" name="id">
                        
                        <div class="mb-3">
                            <label for="titulo" class="form-label">Título <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="titulo" name="titulo" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="4"></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="fecha_fin" class="form-label">Fecha Fin</label>
                                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="imagen" class="form-label">Imagen</label>
                            <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                            <small class="form-text text-muted">Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 5MB</small>
                            <div id="previewImagen" class="mt-2"></div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="orden" class="form-label">Orden</label>
                                <input type="number" class="form-control" id="orden" name="orden" value="0" min="0">
                                <small class="form-text text-muted">Número menor = aparece primero</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" id="activo" name="activo" checked>
                                    <label class="form-check-label" for="activo">
                                        Promoción Activa
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarPromocion()">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>

    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= URL_HOST ?>js/admin-promociones.js"></script>   
</body>
</html>
