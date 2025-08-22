<nav class="navbar navbar-expand-lg">
    <div class="container-fluid barraNavegacion">                
        <a class="navbar-brand" href="#"></a>        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse text-center text-lg-start" id="navbarNav">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item textoMenu "><a class="nav-link" <?= $seleccion == 1 ? 'style="color:#FF0000"' : '' ?> href="index.php"> EMPRESA </a></li>
                <li class="nav-item textoMenu "><a class="nav-link" <?= $seleccion == 2 ? 'style="color:#FF0000"' : '' ?> href="productos.php">PRODUCTOS</a></li>
                <li class="nav-item textoMenu "><a class="nav-link" <?= $seleccion == 3 ? 'style="color:#FF0000"' : '' ?> href="clientes.php">CLIENTES</a></li>
                <li class="nav-item textoMenu "><a class="nav-link" <?= $seleccion == 4 ? 'style="color:#FF0000"' : '' ?> href="contacto.php">CONTACTO</a></li>
                <li class="nav-item textoMenu "><a class="nav-link" <?= $seleccion == 5 ? 'style="color:#FF0000"' : '' ?> href="#">NOTICIAS</a></li>
            </ul>
        </div>
    </div>
</nav>

