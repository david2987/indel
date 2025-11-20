<?php
session_start();
require_once '../config/auth.php';
include('../constant.php');

// Verificar autenticación
requerirAutenticacion();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administración - Promociones INDEL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="<?= URL_HOST ?>css/style.css">
    <link rel="stylesheet" href="<?= URL_HOST ?>css/admin.css">
</head>
<body>
  
     <?php include('navBar.php'); ?>
     <div class="container-fluid mt-4">
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-newspaper"></i> Gestión de Noticias</h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNoticia" onclick="abrirModalNuevaNoticia()">
                            <i class="fas fa-plus"></i> Nueva Noticia
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaNoticias">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Imagen</th>
                                        <th>Título</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                        <th>Orden</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyNoticias">
                                    <!-- Se llena dinámicamente con JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

     <!-- Modal para crear/editar noticia -->
    <div class="modal fade" id="modalNoticia" tabindex="-1" aria-labelledby="modalNoticiaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalNoticiaLabel">Nueva Noticia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formNoticia" enctype="multipart/form-data">
                        <input type="hidden" id="noticia_id" name="id">

                        <div class="mb-3">
                            <label for="noticia_titulo" class="form-label">Título <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="noticia_titulo" name="titulo" required>
                        </div>

                        <div class="mb-3">
                            <label for="noticia_resumen" class="form-label">Resumen</label>
                            <textarea class="form-control" id="noticia_resumen" name="resumen" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="noticia_contenido" class="form-label">Contenido</label>
                            <textarea class="form-control" id="noticia_contenido" name="contenido" rows="6"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="noticia_fecha_publicacion" class="form-label">Fecha de publicación</label>
                                <input type="date" class="form-control" id="noticia_fecha_publicacion" name="fecha_publicacion">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="noticia_orden" class="form-label">Orden</label>
                                <input type="number" class="form-control" id="noticia_orden" name="orden" value="0" min="0">
                                <small class="form-text text-muted">Número menor = aparece primero</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="noticia_imagen" class="form-label">Imagen</label>
                                <input type="file" class="form-control" id="noticia_imagen" name="imagen" accept="image/*">
                                <small class="form-text text-muted">Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 5MB</small>
                                <div id="previewImagenNoticia" class="mt-2"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" id="noticia_activo" name="activo" checked>
                                    <label class="form-check-label" for="noticia_activo">
                                        Noticia activa
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarNoticia()">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>    
    <script src="<?= URL_HOST ?>js/admin-noticias.js"></script>
</body>
</html>