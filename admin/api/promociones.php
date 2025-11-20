<?php
session_start();
header('Content-Type: application/json');
date_default_timezone_set('America/Argentina/Buenos_Aires');

require_once '../../config/auth.php';

// Verificar autenticación
requerirAutenticacion();

require_once '../../config/database.php';

$method = $_SERVER['REQUEST_METHOD'];
$conn = getDBConnection();

switch ($method) {
    case 'GET':
        // Obtener todas las promociones
        $query = "SELECT * FROM promociones ORDER BY orden ASC, fecha_creacion DESC";
        $result = $conn->query($query);
        $promociones = [];
        
        while ($row = $result->fetch_assoc()) {
            $promociones[] = $row;
        }
        
        echo json_encode(['success' => true, 'data' => $promociones]);
        break;
        
    case 'PUT':
    case 'POST':
        // Crear o actualizar promoción
        // Si viene un ID, es actualización
        $id = isset($_POST['id']) && !empty($_POST['id']) ? intval($_POST['id']) : 0;
        $es_actualizacion = $id > 0;
        
        $titulo = $_POST['titulo'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        // $fecha_inicio = !empty($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : null;
        // $fecha_fin = !empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null;
        $fecha_inicio = !empty($_POST['fecha_inicio']) ? date('Y-m-d', strtotime($_POST['fecha_inicio'])): null;
        $fecha_fin = !empty($_POST['fecha_fin']) ? date('Y-m-d', strtotime($_POST['fecha_fin'])): null;
        $activo = isset($_POST['activo']) ? 1 : 0;
        $orden = isset($_POST['orden']) ? intval($_POST['orden']) : 0;
        
        // Si es actualización, obtener imagen actual
        $imagen = null;
        if ($es_actualizacion) {
            $query = "SELECT imagen FROM promociones WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $current = $result->fetch_assoc();
            $imagen = $current['imagen'] ?? null;
            $stmt->close();
        }
        
        // Si hay nueva imagen, subirla
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../../Recursos/imagenes/promociones/';
            
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Eliminar imagen anterior si existe y es actualización
            if ($es_actualizacion && $imagen && file_exists($uploadDir . $imagen)) {
                unlink($uploadDir . $imagen);
            }
            
            $fileExtension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
            $fileName = 'promocion_' . time() . '_' . uniqid() . '.' . $fileExtension;
            $uploadFile = $uploadDir . $fileName;
            
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array(strtolower($fileExtension), $allowedTypes)) {
                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadFile)) {
                    $imagen = $fileName;
                }
            }
        }
        
        if ($es_actualizacion) {
            // Actualizar
            $query = "UPDATE promociones SET titulo = ?, descripcion = ?, imagen = ?, fecha_inicio = ?, fecha_fin = ?, activo = ?, orden = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssssiii", $titulo, $descripcion, $imagen, $fecha_inicio, $fecha_fin, $activo, $orden, $id);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Promoción actualizada exitosamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al actualizar la promoción']);
            }
            $stmt->close();
        } else {
            // Crear nueva
            $query = "INSERT INTO promociones (titulo, descripcion, imagen, fecha_inicio, fecha_fin, activo, orden) 
                      VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssssii", $titulo, $descripcion, $imagen, $fecha_inicio, $fecha_fin, $activo, $orden);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Promoción creada exitosamente', 'id' => $conn->insert_id]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al crear la promoción']);
            }
            $stmt->close();
        }
        break;
        
    case 'DELETE':
        // Eliminar promoción
        $id = $_GET['id'] ?? 0;
        
        // Obtener imagen para eliminarla
        $query = "SELECT imagen FROM promociones WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $promocion = $result->fetch_assoc();
        
        if ($promocion && $promocion['imagen']) {
            $imagePath = '../../Recursos/imagenes/promociones/' . $promocion['imagen'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        $stmt->close();
        
        $query = "DELETE FROM promociones WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Promoción eliminada exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar la promoción']);
        }
        $stmt->close();
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}

closeDBConnection($conn);

