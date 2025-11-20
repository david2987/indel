<div class="container-fluid" style="margin-top: 46px;">
    <!-- Logo OVNISA en la parte superior central -->
    <div class="d-flex justify-content-center mb-4">
        <a href="https://www.ovnisa.com/" target="_blank">
            <img src="<?= URL_HOST ?>Recursos/imagenes/ovnisa.png" class="img-fluid logo-ovnisa">
        </a>
    </div>
    
    <!-- Logos MIMA y JIALIFT debajo de OVNISA -->
    <div class="container">
        <div class="row justify-content-center">
            <!-- MIMA AUTOELEVADORES - izquierda -->
            <div class="mt-4 col-md-4 col-sm-6 text-center mb-3">
                <a href="https://www.mimaargentina.com.ar/" target="_blank">
                    <img src="<?= URL_HOST ?>Recursos/imagenes/mima.png" class="img-fluid logo-mima">
                </a>
            </div>
            
            <!-- JIALIFT MATERIALS HANDLING - derecha -->
            <div class="col-md-4 col-sm-6 text-center mb-3">
                <a href="https://spanish.jialiftforklift.com/" target="_blank">
                    <img src="<?= URL_HOST ?>Recursos/imagenes/jialift.jpg" class="img-fluid logo-jialift">
                </a>
            </div>
        </div>
    </div>
    
    <!-- Imagen principal de montacargas -->
    <div class="container mt-4">
        <div class="d-flex justify-content-center">
            <img src="<?= URL_HOST ?>Recursos/imagenes/mima-jialift.png" class="img-fluid imagen-principal">
        </div>
    </div>
    <!-- Slogan centrado debajo de la imagen -->
    <div class="container text-center">
        <div style=" margin-top: 5rem; ;font-size: 2.5rem; font-weight: bold; color: #333; margin-bottom: 2rem;">
            Tu tranquilidad es nuestro objetivo
        </div>
    </div>
    
    <!-- Texto descriptivo -->
    <!-- <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <div style="font-size: 1.2rem; font-weight: 500; color: #666; line-height: 1.6;">
                    Como distribuidores oficiales nuestros productos están respaldados e impulsados por Mima. Cada equipo se comercia con una sólida garantía de un año y nuestras baterías se respaldan con garantía de 3 a 5 años.
                    Tu satisfacción y tranquilidad son nuestras prioridades
                </div>
            </div>
        </div>
    </div> -->
    <br>
    <div class="mt-5">
        <div class="tituloAireComprimido">
            SOLUCIONES LOGÍSTICAS
        </div>
        <div class="text-center mt-2">
            <img src="<?= URL_HOST ?>Recursos/imagenes/Barra-Inferior.png">
        </div>
    </div>
    <div class="mt-5 textoAutoelevadores" style="text-align: center;margin-bottom: 100px; ">
        <!-- Equipos para movimientos / Almacenaje de materiales y Equipos para operaciones especiales -->
        <div style="font-family: 'Montserrat';font-style: normal;font-weight: 700;font-size: 35px;line-height: 45px;text-align: center;color: #6C6A6A;">
            Autoelevadores – Apiladores – Reachs – Transpaletas - Equipos con Bateria de Litio
        </div>
        <div class="d-grid" style="justify-content: center;gap: 20px;margin-top:5%">
            <div><img src="<?= URL_HOST ?>Recursos/imagenes/jialift-productos-1.png" class="w-100"> </div>            
            <div><img src="<?= URL_HOST ?>Recursos/imagenes/jialift-productos-2.png" class="w-100"> </div>
        </div>
    </div>
</div>
<div class="fondoCuadradoAutoelevadores w-100 text-center">
    <div class="grid justify-content-center" style="align-content: center;justify-items: center;">
        <div class=" col-md-12 rectanguloAireComprimido mt-4 contactoAutolevadores">
            CONTACTOS
        </div>
        <div class="infoLineaDirectaAireComprimido mt-4">
            Haga su consulta: (341) 6 913541 
        </div>
        <div class="lineaDivisionAireComprimido mt-4"></div>
        <div class="infoLineaDirectaAireComprimido mt-4">
            <a href="https://www.mimaargentina.com.ar/" target="_blank">
                https://www.mimaargentina.com.ar
            </a>
            <br>
            <a href="mailto:comercial@indelsrl.com.ar">
                comercial@indelsrl.com.ar
            </a>
        </div>
    </div>

</div>

<div class="container" style="justify-items: center; margin-top: -30px; ">
    <div class="rectanguloAutoelevadoresRojo" style="align-content: center;">
        <div class="textoRectanguloRojoAutoelevadores">
            HAGA CLICK Y CONOZCA NUESTRA REPRESENTADA<BR>
            PARA AMPLIAR INFORMACIÓN  
        </div>
    </div>
</div>
<div style="margin-top: 50px;">

</div>

<style>
/* Estilos para hacer las imágenes más grandes en desktop */
.logo-ovnisa {
    max-height: 80px;
}

.logo-mima, .logo-jialift {
    max-height: 60px;
}

.imagen-principal {
    max-width: 100%;
}

/* Media query para desktop - hacer las imágenes 3 veces más grandes */
@media (min-width: 768px) {
    .logo-ovnisa {
        max-height: 240px; /* 80px * 3 */
    }
    
    .logo-mima, .logo-jialift {
        max-height: 180px; /* 60px * 3 */
    }
    
    .imagen-principal {
        max-width: 100%;
        transform: scale(1.5); /* Escalar la imagen principal 1.5x */
    }
}

/* Para pantallas muy grandes (desktop) */
@media (min-width: 1200px) {
    .imagen-principal {
        transform: scale(1.5); /* Escalar aún más en pantallas grandes */
    }
}
</style>