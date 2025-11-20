<?php
session_start();
header('Content-Type: application/json');

require_once '../../config/auth.php';

// Verificar autenticación
requerirAutenticacion();

require_once '../../config/database.php';

$method = $_SERVER['REQUEST_METHOD'];
$conn = getDBConnection();

switch ($method) {
    case 'GET':
        $query = "SELECT * FROM noticias ORDER BY orden ASC, fecha_publicacion DESC, fecha_creacion DESC";
        $result = $conn->query($query);
        $noticias = [];

        while ($row = $result->fetch_assoc()) {
            $noticias[] = $row;
        }

        echo json_encode(['success' => true, 'data' => $noticias]);
        break;

    case 'PUT':
    case 'POST':
        $id = isset($_POST['id']) && !empty($_POST['id']) ? intval($_POST['id']) : 0;
        $es_actualizacion = $id > 0;

        $titulo = $_POST['titulo'] ?? '';
        $resumen = $_POST['resumen'] ?? '';
        $contenido = $_POST['contenido'] ?? '';
        $fecha_publicacion = !empty($_POST['fecha_publicacion']) ? $_POST['fecha_publicacion'] : null;
        $activo = isset($_POST['activo']) ? 1 : 0;
        $orden = isset($_POST['orden']) ? intval($_POST['orden']) : 0;

        if (empty(trim($titulo))) {
            echo json_encode(['success' => false, 'message' => 'El título es obligatorio']);
            break;
        }

        $imagen = null;
        if ($es_actualizacion) {
            $query = "SELECT imagen FROM noticias WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $current = $result->fetch_assoc();
            $imagen = $current['imagen'] ?? null;
            $stmt->close();
        }

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../../Recursos/imagenes/noticias/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if ($es_actualizacion && $imagen && file_exists($uploadDir . $imagen)) {
                unlink($uploadDir . $imagen);
            }

            $fileExtension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
            $fileName = 'noticia_' . time() . '_' . uniqid() . '.' . $fileExtension;
            $uploadFile = $uploadDir . $fileName;

            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (in_array(strtolower($fileExtension), $allowedTypes)) {
                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadFile)) {
                    $imagen = $fileName;
                }
            }
        }

        if ($es_actualizacion) {
            $query = "UPDATE noticias SET titulo = ?, resumen = ?, contenido = ?, imagen = ?, fecha_publicacion = ?, activo = ?, orden = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssssiii", $titulo, $resumen, $contenido, $imagen, $fecha_publicacion, $activo, $orden, $id);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Noticia actualizada exitosamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al actualizar la noticia']);
            }
            $stmt->close();
        } else {
            $query = "INSERT INTO noticias (titulo, resumen, contenido, imagen, fecha_publicacion, activo, orden)
                      VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssssii", $titulo, $resumen, $contenido, $imagen, $fecha_publicacion, $activo, $orden);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Noticia creada exitosamente', 'id' => $conn->insert_id]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al crear la noticia']);
            }
            $stmt->close();
        }
        break;

    case 'DELETE':
        $id = $_GET['id'] ?? 0;

        $query = "SELECT imagen FROM noticias WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $noticia = $result->fetch_assoc();

        if ($noticia && $noticia['imagen']) {
            $imagePath = '../../Recursos/imagenes/noticias/' . $noticia['imagen'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        $stmt->close();

        $query = "DELETE FROM noticias WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Noticia eliminada exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar la noticia']);
        }
        $stmt->close();
        break;

    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}

closeDBConnection($conn);


