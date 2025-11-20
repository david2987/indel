<?php
/**
 * Funciones de autenticación y autorización
 */

require_once 'database.php';

/**
 * Verificar credenciales de usuario
 */
function verificarCredenciales($username, $password) {
    $conn = getDBConnection();
    
    $query = "SELECT id, username, email, password, nombre, apellido, activo 
              FROM usuarios 
              WHERE username = ? AND activo = 1";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $usuario = $result->fetch_assoc();
        
        // Verificar contraseña
        if (password_verify($password, $usuario['password'])) {
            // Actualizar último acceso
            $updateQuery = "UPDATE usuarios SET ultimo_acceso = NOW() WHERE id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("i", $usuario['id']);
            $updateStmt->execute();
            $updateStmt->close();
            
            $stmt->close();
            closeDBConnection($conn);
            
            return $usuario;
        }
    }
    
    $stmt->close();
    closeDBConnection($conn);
    
    return false;
}

/**
 * Obtener grupos de un usuario
 */
function obtenerGruposUsuario($usuario_id) {
    $conn = getDBConnection();
    
    $query = "SELECT g.id, g.nombre, g.descripcion 
              FROM grupos g
              INNER JOIN usuarios_grupos ug ON g.id = ug.grupo_id
              WHERE ug.usuario_id = ? AND g.activo = 1";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $grupos = [];
    while ($row = $result->fetch_assoc()) {
        $grupos[] = $row;
    }
    
    $stmt->close();
    closeDBConnection($conn);
    
    return $grupos;
}

/**
 * Verificar si el usuario tiene un grupo específico
 */
function tieneGrupo($usuario_id, $nombre_grupo) {
    $grupos = obtenerGruposUsuario($usuario_id);
    
    foreach ($grupos as $grupo) {
        if ($grupo['nombre'] === $nombre_grupo) {
            return true;
        }
    }
    
    return false;
}

/**
 * Verificar si el usuario es administrador
 */
function esAdministrador($usuario_id) {
    return tieneGrupo($usuario_id, 'Administradores');
}

/**
 * Requerir autenticación (redirige al login si no está autenticado)
 */
function requerirAutenticacion() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header('Location: login.php');
        exit;
    }
}

/**
 * Requerir grupo específico
 */
function requerirGrupo($nombre_grupo) {
    requerirAutenticacion();
    
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: login.php');
        exit;
    }
    
    if (!tieneGrupo($_SESSION['usuario_id'], $nombre_grupo)) {
        header('Location: index.php');
        exit;
    }
}

/**
 * Requerir ser administrador
 */
function requerirAdministrador() {
    requerirGrupo('Administradores');
}

/**
 * Crear hash de contraseña
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

