<?php
require_once 'config/database.php';

$conn = getDBConnection();

$query = "SELECT * FROM noticias
          WHERE activo = 1
          ORDER BY orden ASC, COALESCE(fecha_publicacion, fecha_creacion) DESC";

$result = $conn->query($query);
$noticias = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $noticias[] = $row;
    }
}

closeDBConnection($conn);

function resumirContenido(?string $resumen, ?string $contenido): string
{
    if (!empty($resumen)) {
        return $resumen;
    }

    if (!empty($contenido)) {
        $textoPlano = strip_tags($contenido);
        if (mb_strlen($textoPlano, 'UTF-8') > 280) {
            return mb_substr($textoPlano, 0, 277, 'UTF-8') . '...';
        }
        return $textoPlano;
    }

    return '';
}
?>

<div class="container" style="margin-top: 46px; margin-bottom: 126px;">
    <div class="row">
        <div class="col-12">
            <h2 class="tituloPromociones mb-4 text-center">Noticias</h2>
        </div>
    </div>

    <?php if (empty($noticias)): ?>
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <p class="textoPromociones">No hay noticias disponibles en este momento.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($noticias as $noticia): ?>
                <div class="col-md-6 col-lg-4">
                    <article class="card h-100 shadow-sm border-0">
                        <?php if (!empty($noticia['imagen'])): ?>
                            <div class="ratio ratio-4x3">
                                <img
                                    src="<?= URL_HOST ?>Recursos/imagenes/noticias/<?= htmlspecialchars($noticia['imagen']) ?>"
                                    alt="<?= htmlspecialchars($noticia['titulo']) ?>"
                                    class="img-fluid rounded-top"
                                    style="object-fit: cover;"
                                >
                            </div>
                        <?php endif; ?>
                        <div class="card-body d-flex flex-column">
                            <header>
                                <h3 class="h5"><?= htmlspecialchars($noticia['titulo']) ?></h3>
                                <?php if (!empty($noticia['fecha_publicacion'])): ?>
                                    <p class="text-muted mb-2">
                                        <small><i class="fas fa-calendar-alt me-1"></i><?= date('d/m/Y', strtotime($noticia['fecha_publicacion'])) ?></small>
                                    </p>
                                <?php endif; ?>
                            </header>
                            <?php
                                $resumen = resumirContenido($noticia['resumen'], $noticia['contenido']);
                                if (!empty($resumen)):
                            ?>
                                <p class="mb-0 flex-grow-1"><?= nl2br(htmlspecialchars($resumen)) ?></p>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($noticia['contenido'])): ?>
                            <div class="card-footer bg-white border-0 text-end">
                                <button class="btn btn-link p-0 text-decoration-none" type="button" data-bs-toggle="modal" data-bs-target="#modalNoticiaDetalle<?= $noticia['id'] ?>">
                                    Leer más
                                </button>
                            </div>
                        <?php endif; ?>
                    </article>
                </div>

                <?php if (!empty($noticia['contenido'])): ?>
                    <div class="modal fade" id="modalNoticiaDetalle<?= $noticia['id'] ?>" tabindex="-1" aria-labelledby="modalNoticiaDetalleLabel<?= $noticia['id'] ?>" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalNoticiaDetalleLabel<?= $noticia['id'] ?>"><?= htmlspecialchars($noticia['titulo']) ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <?php if (!empty($noticia['fecha_publicacion'])): ?>
                                        <p class="text-muted">
                                            <small><i class="fas fa-calendar-alt me-1"></i><?= date('d/m/Y', strtotime($noticia['fecha_publicacion'])) ?></small>
                                        </p>
                                    <?php endif; ?>
                                    <?php if (!empty($noticia['imagen'])): ?>
                                        <img
                                            src="<?= URL_HOST ?>Recursos/imagenes/noticias/<?= htmlspecialchars($noticia['imagen']) ?>"
                                            alt="<?= htmlspecialchars($noticia['titulo']) ?>"
                                            class="img-fluid rounded mb-3"
                                        >
                                    <?php endif; ?>
                                    <div class="contenido-noticia">
                                        <?= nl2br(htmlspecialchars($noticia['contenido'])) ?>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

