<div class="container-fluid " style="margin-top: 100px;">
    <div class="row">
        <?php
        $titulos = array(" AIRE COMPRIMIDO", "AUTOELEVADORES", "MECANIZADO DE CHAPA", "MAQUINAS HERRAMIENTAS", "METROLOGÍA", "MESAS SOLDADURA", "PUENTES GRÚA", "PINTURA - ASPIRACIÓN");
        $imagenes = array("Aire Comprimido Banner.png", "Autolevadores Banner .png", "MECANIZADO DE CHAPA Banner.png", "MAQUINAS HERRAMIENTAS Banner.png", "METROLOGÍA Banner.png", "MESAS SOLDADURA Banner.png", "PUENTES GRÚA Banner.png", "TRATAM DE SUPERFICIES Banner.png");
        $enlaces = array("aireComprimido.php", "autoelevadores.php", "mecanizadosChapa.php", "maquinasHerramientas.php", "metrologia.php", "mesasSoldadura.php", "puntesGruas.php", "tratamientoSuperficies.php");
        $i = 0;

        foreach ($titulos as  $titulo) { ?>
            <div class="col-md-3 text-center mt-5" style="justify-items: center;">
                <a href="<?= URL_HOST . $enlaces[$i] ?>" style="text-decoration: none;" class="seleccionarProducto" >
                    <div class="bannerProductosNoSeleccionado">
                        <span class="tituloBannerProductos"> <?= $titulo ?></span>
                    </div>
                    <img src="<?= URL_HOST ?>Recursos/imagenes/productos/<?= $imagenes[$i] ?>" class="d-block imagenProductos ">                   
                </a>
            </div>
        <?php
            $i += 1;
        } ?>
    </div>
    <br><br><br><br>
</div>