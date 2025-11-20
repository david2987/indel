<?php
session_start();
header('Content-Type: application/json');

require_once '../../config/auth.php';
require_once '../../config/database.php';

// Solo administradores
requerirAdministrador();

$method = $_SERVER['REQUEST_METHOD'];
$conn = getDBConnection();

switch ($method) {
    case 'GET':
        // Obtener todos los usuarios con sus grupos
        $query = "SELECT u.*, 
                  GROUP_CONCAT(g.nombre SEPARATOR ', ') as grupos_nombres
                  FROM usuarios u
                  LEFT JOIN usuarios_grupos ug ON u.id = ug.usuario_id
                  LEFT JOIN grupos g ON ug.grupo_id = g.id
                  GROUP BY u.id
                  ORDER BY u.fecha_creacion DESC";
        
        $result = $conn->query($query);
        $usuarios = [];
        
        while ($row = $result->fetch_assoc()) {
            // Obtener grupos completos del usuario
            $gruposQuery = "SELECT g.id, g.nombre 
                           FROM grupos g
                           INNER JOIN usuarios_grupos ug ON g.id = ug.grupo_id
                           WHERE ug.usuario_id = ?";
            $stmt = $conn->prepare($gruposQuery);
            $stmt->bind_param("i", $row['id']);
            $stmt->execute();
            $gruposResult = $stmt->get_result();
            $grupos = [];
            while ($g = $gruposResult->fetch_assoc()) {
                $grupos[] = $g;
            }
            $stmt->close();
            
            $row['grupos'] = $grupos;
            unset($row['password']); // No enviar contraseña
            $usuarios[] = $row;
        }
        
        echo json_encode(['success' => true, 'data' => $usuarios]);
        break;
        
    case 'POST':
        // Crear o actualizar usuario
        $id = isset($_POST['id']) && !empty($_POST['id']) ? intval($_POST['id']) : 0;
        $es_actualizacion = $id > 0;
        
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $nombre = trim($_POST['nombre'] ?? '');
        $apellido = trim($_POST['apellido'] ?? '');
        $password = $_POST['password'] ?? '';
        $activo = isset($_POST['activo']) ? 1 : 0;
        $grupos = isset($_POST['grupos']) ? json_decode($_POST['grupos'], true) : [];
        
        // Validaciones
        if (empty($username) || empty($email)) {
            echo json_encode(['success' => false, 'message' => 'Usuario y email son requeridos']);
            break;
        }
        
        // Verificar si el username ya existe (si es nuevo o si cambió)
        $checkQuery = "SELECT id FROM usuarios WHERE (username = ? OR email = ?) AND id != ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkId = $es_actualizacion ? $id : 0;
        $checkStmt->bind_param("ssi", $username, $email, $checkId);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        
        if ($checkResult->num_rows > 0) {
            $checkStmt->close();
            echo json_encode(['success' => false, 'message' => 'El usuario o email ya existe']);
            break;
        }
        $checkStmt->close();
        
        if ($es_actualizacion) {
            // Actualizar usuario
            if (!empty($password)) {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $query = "UPDATE usuarios SET username = ?, email = ?, nombre = ?, apellido = ?, password = ?, activo = ? WHERE id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("sssssii", $username, $email, $nombre, $apellido, $hashedPassword, $activo, $id);
            } else {
                $query = "UPDATE usuarios SET username = ?, email = ?, nombre = ?, apellido = ?, activo = ? WHERE id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ssssii", $username, $email, $nombre, $apellido, $activo, $id);
            }
            
            if ($stmt->execute()) {
                // Actualizar grupos
                // Eliminar grupos actuales
                $deleteQuery = "DELETE FROM usuarios_grupos WHERE usuario_id = ?";
                $deleteStmt = $conn->prepare($deleteQuery);
                $deleteStmt->bind_param("i", $id);
                $deleteStmt->execute();
                $deleteStmt->close();
                
                // Agregar nuevos grupos
                if (!empty($grupos) && is_array($grupos)) {
                    $insertQuery = "INSERT INTO usuarios_grupos (usuario_id, grupo_id) VALUES (?, ?)";
                    $insertStmt = $conn->prepare($insertQuery);
                    foreach ($grupos as $grupo_id) {
                        $insertStmt->bind_param("ii", $id, $grupo_id);
                        $insertStmt->execute();
                    }
                    $insertStmt->close();
                }
                
                echo json_encode(['success' => true, 'message' => 'Usuario actualizado exitosamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al actualizar el usuario']);
            }
            $stmt->close();
        } else {
            // Crear nuevo usuario
            if (empty($password)) {
                echo json_encode(['success' => false, 'message' => 'La contraseña es requerida para nuevos usuarios']);
                break;
            }
            
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $query = "INSERT INTO usuarios (username, email, password, nombre, apellido, activo) 
                      VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssssi", $username, $email, $hashedPassword, $nombre, $apellido, $activo);
            
            if ($stmt->execute()) {
                $nuevo_id = $conn->insert_id;
                
                // Agregar grupos
                if (!empty($grupos) && is_array($grupos)) {
                    $insertQuery = "INSERT INTO usuarios_grupos (usuario_id, grupo_id) VALUES (?, ?)";
                    $insertStmt = $conn->prepare($insertQuery);
                    foreach ($grupos as $grupo_id) {
                        $insertStmt->bind_param("ii", $nuevo_id, $grupo_id);
                        $insertStmt->execute();
                    }
                    $insertStmt->close();
                }
                
                echo json_encode(['success' => true, 'message' => 'Usuario creado exitosamente', 'id' => $nuevo_id]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al crear el usuario']);
            }
            $stmt->close();
        }
        break;
        
    case 'DELETE':
        $id = $_GET['id'] ?? 0;
        
        // No permitir eliminar el propio usuario
        if ($id == $_SESSION['usuario_id']) {
            echo json_encode(['success' => false, 'message' => 'No puede eliminar su propio usuario']);
            break;
        }
        
        $query = "DELETE FROM usuarios WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Usuario eliminado exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el usuario']);
        }
        $stmt->close();
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}

closeDBConnection($conn);

