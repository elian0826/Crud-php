<?php
require_once "../config/config.php";
require_once "../config/database.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['mensaje'] = "Método no permitido";
    $_SESSION['tipo_mensaje'] = "danger";
    header("Location: ../index.php");
    exit;
}

try {
    if (!isset($_POST['id'])) {
        throw new Exception('ID no proporcionado');
    }
    
    $id = $mysqli->real_escape_string($_POST['id']);
    
    $stmt = $mysqli->prepare("SELECT id FROM clientes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    if (!$stmt->get_result()->fetch_assoc()) {
        throw new Exception('Cliente no encontrado');
    }
    
    $stmt = $mysqli->prepare("SELECT id FROM clientes WHERE (documento_identidad = ? OR correo = ?) AND id != ?");
    $stmt->bind_param("ssi", $_POST['documento_identidad'], $_POST['correo'], $id);
    $stmt->execute();
    if ($stmt->get_result()->fetch_assoc()) {
        throw new Exception('El documento de identidad o correo ya está registrado');
    }
    
    $sql = "UPDATE clientes SET 
            nombre = ?,
            apellido = ?,
            documento_identidad = ?,
            telefono = ?,
            correo = ?,
            direccion = ?";
    
    $params = [
        $_POST['nombre'],
        $_POST['apellido'],
        $_POST['documento_identidad'],
        $_POST['telefono'],
        $_POST['correo'],
        $_POST['direccion'] ?? null
    ];
    $types = "ssssss";
    
    if (!empty($_POST['password'])) {
        $sql .= ", password = ?";
        $params[] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $types .= "s";
    }
    
    if (isset($_FILES['documento_cedula']) && $_FILES['documento_cedula']['error'] === UPLOAD_ERR_OK) {
        $documento_cedula = $_FILES['documento_cedula'];
        $extension = strtolower(pathinfo($documento_cedula['name'], PATHINFO_EXTENSION));
        
        if (!in_array($extension, ['pdf', 'jpg', 'jpeg', 'png'])) {
            throw new Exception('Formato de archivo no permitido');
        }
        
        $nombre_archivo = uniqid() . '.' . $extension;
        $ruta_archivo = "../uploads/" . $nombre_archivo;
        
        if (!move_uploaded_file($documento_cedula['tmp_name'], $ruta_archivo)) {
            throw new Exception('Error al guardar el documento');
        }
        
        $stmt = $mysqli->prepare("SELECT documento_cedula FROM clientes WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        
        if ($resultado && $resultado['documento_cedula']) {
            $ruta_anterior = "../uploads/" . $resultado['documento_cedula'];
            if (file_exists($ruta_anterior)) {
                unlink($ruta_anterior);
            }
        }
        
        $sql .= ", documento_cedula = ?";
        $params[] = $nombre_archivo;
        $types .= "s";
    }
    
    $sql .= " WHERE id = ?";
    $params[] = $id;
    $types .= "i";
    
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param($types, ...$params);
    
    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Cliente actualizado exitosamente";
        $_SESSION['tipo_mensaje'] = "success";
    } else {
        throw new Exception('Error al actualizar el cliente');
    }
    
} catch (Exception $e) {
    error_log($e->getMessage());
    $_SESSION['mensaje'] = "Error: " . $e->getMessage();
    $_SESSION['tipo_mensaje'] = "danger";
    // Si hubo error y se subió un nuevo archivo, eliminarlo
    if (isset($ruta_archivo) && file_exists($ruta_archivo)) {
        unlink($ruta_archivo);
    }
}

header("Location: ../index.php");
exit;

$mysqli->close(); 