<?php
require_once "../config/config.php";
require_once "../config/database.php";

if (isset($_GET['id'])) {
    header('Content-Type: application/json');
    try {
        $id = $mysqli->real_escape_string($_GET['id']);
        
        $stmt = $mysqli->prepare("SELECT id, nombre, apellido, documento_identidad, telefono, correo, direccion, documento_cedula FROM clientes WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($cliente = $result->fetch_assoc()) {
            if ($cliente['documento_cedula']) {
                $cliente['documento_url'] = "../uploads/" . $cliente['documento_cedula'];
            }
            echo json_encode($cliente);
        } else {
            echo json_encode(['error' => 'Cliente no encontrado']);
        }
        exit;
    } catch (Exception $e) {
        error_log($e->getMessage());
        echo json_encode(['error' => 'Error al obtener los datos del cliente']);
        exit;
    }
}

if (isset($_GET['format']) && $_GET['format'] === 'json') {
    header('Content-Type: application/json');
    try {
        $sql = "SELECT id, nombre, apellido, documento_identidad, telefono, correo, direccion FROM clientes ORDER BY fecha_registro DESC";
        $result = $mysqli->query($sql);
        $clientes = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($clientes);
    } catch (Exception $e) {
        error_log($e->getMessage());
        echo json_encode(['error' => 'Error al obtener la lista de clientes']);
    }
    exit;
}

header("Location: ../index.php");
exit; 