<?php
session_start();
require_once '../config/database.php';
require_once '../config/auth.php';
include('../constant.php');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Por favor, complete todos los campos';
    } else {
        // Verificar credenciales en la base de datos
        $usuario = verificarCredenciales($username, $password);
        
        if ($usuario) {
            // Obtener grupos del usuario
            $grupos = obtenerGruposUsuario($usuario['id']);
            
            // Guardar información en sesión
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['admin_username'] = $usuario['username'];
            $_SESSION['admin_email'] = $usuario['email'];
            $_SESSION['admin_nombre'] = $usuario['nombre'] . ' ' . $usuario['apellido'];
            $_SESSION['grupos'] = array_column($grupos, 'nombre');
            $_SESSION['es_administrador'] = esAdministrador($usuario['id']);
            
            header('Location: index.php');
            exit;
        } else {
            $error = 'Usuario o contraseña incorrectos';
        }
    }
}

// Si ya está logueado, redirigir
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Administración INDEL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="<?= URL_HOST ?>css/style.css">
    <style>
        body {
           
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            max-width: 400px;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="card shadow-lg">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <i class="fas fa-lock fa-3x text-primary mb-3"></i>
                    <h3>Administración</h3>
                    <p class="text-muted">Ingrese sus credenciales</p>
                </div>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="username" class="form-label">Usuario</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control" id="username" name="username" required autofocus>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-sign-in-alt"></i> Ingresar
                    </button>
                </form>
                
                <div class="text-center mt-3">
                    <a href="<?= URL_HOST ?>" class="text-muted text-decoration-none">
                        <i class="fas fa-arrow-left"></i> Volver al sitio
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

