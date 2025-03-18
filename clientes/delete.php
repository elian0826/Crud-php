<?php
require_once "../config/config.php";
require_once "../config/database.php";

if (!isset($_GET['id'])) {
    $_SESSION['mensaje'] = "ID de cliente no proporcionado";
    $_SESSION['tipo_mensaje'] = "danger";
    header("Location: ../index.php");
    exit;
}

try {
    $id = $mysqli->real_escape_string($_GET['id']);
    
    $stmt = $mysqli->prepare("SELECT documento_cedula FROM clientes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cliente = $result->fetch_assoc();
    
    $stmt = $mysqli->prepare("DELETE FROM clientes WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        if ($cliente && $cliente['documento_cedula']) {
            $ruta_archivo = "../uploads/" . $cliente['documento_cedula'];
            if (file_exists($ruta_archivo)) {
                unlink($ruta_archivo);
            }
        }
        
        $_SESSION['mensaje'] = "Cliente eliminado exitosamente";
        $_SESSION['tipo_mensaje'] = "success";
    } else {
        throw new Exception('Error al eliminar el cliente');
    }
    
} catch (Exception $e) {
    error_log($e->getMessage());
    $_SESSION['mensaje'] = "Error al eliminar el cliente: " . $e->getMessage();
    $_SESSION['tipo_mensaje'] = "danger";
}

header("Location: ../index.php");
exit; 