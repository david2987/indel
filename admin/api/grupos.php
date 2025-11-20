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
        // Obtener todos los grupos con conteo de usuarios
        $query = "SELECT g.*, 
                  COUNT(ug.usuario_id) as total_usuarios
                  FROM grupos g
                  LEFT JOIN usuarios_grupos ug ON g.id = ug.grupo_id
                  GROUP BY g.id
                  ORDER BY g.nombre ASC";
        
        $result = $conn->query($query);
        $grupos = [];
        
        while ($row = $result->fetch_assoc()) {
            $grupos[] = $row;
        }
        
        echo json_encode(['success' => true, 'data' => $grupos]);
        break;
        
    case 'POST':
        // Crear o actualizar grupo
        $id = isset($_POST['id']) && !empty($_POST['id']) ? intval($_POST['id']) : 0;
        $es_actualizacion = $id > 0;
        
        $nombre = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $activo = isset($_POST['activo']) ? 1 : 0;
        
        if (empty($nombre)) {
            echo json_encode(['success' => false, 'message' => 'El nombre del grupo es requerido']);
            break;
        }
        
        // Verificar si el nombre ya existe
        $checkQuery = "SELECT id FROM grupos WHERE nombre = ? AND id != ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkId = $es_actualizacion ? $id : 0;
        $checkStmt->bind_param("si", $nombre, $checkId);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        
        if ($checkResult->num_rows > 0) {
            $checkStmt->close();
            echo json_encode(['success' => false, 'message' => 'El nombre del grupo ya existe']);
            break;
        }
        $checkStmt->close();
        
        if ($es_actualizacion) {
            // Actualizar grupo
            $query = "UPDATE grupos SET nombre = ?, descripcion = ?, activo = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssii", $nombre, $descripcion, $activo, $id);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Grupo actualizado exitosamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al actualizar el grupo']);
            }
            $stmt->close();
        } else {
            // Crear nuevo grupo
            $query = "INSERT INTO grupos (nombre, descripcion, activo) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssi", $nombre, $descripcion, $activo);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Grupo creado exitosamente', 'id' => $conn->insert_id]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al crear el grupo']);
            }
            $stmt->close();
        }
        break;
        
    case 'DELETE':
        $id = $_GET['id'] ?? 0;
        
        // Verificar si hay usuarios en el grupo
        $checkQuery = "SELECT COUNT(*) as total FROM usuarios_grupos WHERE grupo_id = ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param("i", $id);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        $check = $checkResult->fetch_assoc();
        $checkStmt->close();
        
        if ($check['total'] > 0) {
            echo json_encode(['success' => false, 'message' => 'No se puede eliminar el grupo porque tiene usuarios asignados']);
            break;
        }
        
        $query = "DELETE FROM grupos WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Grupo eliminado exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el grupo']);
        }
        $stmt->close();
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}

closeDBConnection($conn);

