    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">
                <i class="fas fa-cog"></i> Administración INDEL
            </span>
            <div>
                <span class="text-light me-3">
                    <i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['admin_nombre'] ?? $_SESSION['admin_username'] ?? 'Usuario') ?>
                </span>
                <a href="promociones.php" class="btn btn-outline-light btn-sm me-2" >
                    <i class="fas fa-eye"></i> Promociones
                </a>
                <a href="noticias.php" class="btn btn-outline-light btn-sm me-2" >
                    <i class="fas fa-newspaper"></i> Noticias
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
    </nav>