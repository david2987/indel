<?php
/**
 * Configuración de base de datos
 * Ajustar según tu configuración de MySQL/MariaDB
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'indel');

/**
 * Función para obtener conexión a la base de datos
 */
function getDBConnection() {
    // Verificar si la extensión mysqli está disponible
    if (!extension_loaded('mysqli')) {
        die("Error: La extensión mysqli de PHP no está habilitada. Por favor, habilítela en el archivo php.ini de XAMPP.");
    }
    
    try {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if ($conn->connect_error) {
            throw new Exception("Error de conexión: " . $conn->connect_error);
        }
        
        $conn->set_charset("utf8mb4");
        return $conn;
        
    } catch (Exception $e) {
        error_log("Error de base de datos: " . $e->getMessage());
        die("Error de conexión a la base de datos. Por favor, contacte al administrador.".$e->getMessage());
    }
}

/**
 * Función para cerrar conexión
 */
function closeDBConnection($conn) {
    if ($conn) {
        $conn->close();
    }
}

