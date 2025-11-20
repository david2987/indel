<?php
require_once 'config/database.php';

$conn = getDBConnection();

// Obtener promociones activas y vigentes
$fecha_actual = date('Y-m-d');
$query = "SELECT * FROM promociones 
          WHERE activo = 1 
          AND (fecha_fin IS NULL OR fecha_fin >= ?)
          ORDER BY orden ASC, fecha_creacion DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $fecha_actual);
$stmt->execute();
$result = $stmt->get_result();
$promociones = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

closeDBConnection($conn);
?>

<div class="container" style="margin-top: 46px; margin-bottom: 126px;">
    <?php if (empty($promociones)): ?>
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h2 class="tituloPromociones mb-4">Promociones</h2>
                <p class="textoPromociones">No hay promociones disponibles en este momento.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-12">
                <h2 class="tituloPromociones mb-4 text-center">Promociones</h2>
            </div>
        </div>
        
        <div class="row">
            <?php foreach ($promociones as $promocion): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card-promocion">
                        <?php if (!empty($promocion['imagen'])): ?>
                            <div class="imagen-promocion">
                                <img src="<?= URL_HOST ?>Recursos/imagenes/promociones/<?= htmlspecialchars($promocion['imagen']) ?>" 
                                     alt="<?= htmlspecialchars($promocion['titulo']) ?>"
                                      style="width:100%;height:100%;object-fit:contain;transition:transform 0.3s ease">
                            </div>
                        <?php endif; ?>
                        <div class="contenido-promocion">
                            <h3 class="titulo-promocion"><?= htmlspecialchars($promocion['titulo']) ?></h3>
                            <?php if (!empty($promocion['descripcion'])): ?>
                                <p class="descripcion-promocion"><?= nl2br(htmlspecialchars($promocion['descripcion'])) ?></p>
                            <?php endif; ?>
                            
                            <?php if (!empty($promocion['fecha_fin'])): ?>
                                <div class="fecha-promocion">
                                    <small>Válido hasta: <?= date('d/m/Y', strtotime($promocion['fecha_fin'])) ?></small>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div style="width: 90%;" >
                            <a href="contacto.php?mensaje=<?= !empty($promocion['descripcion'])?htmlspecialchars($promocion['descripcion']):''  ?>" class="btn btn-danger w-100 m-3">Consultar</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

