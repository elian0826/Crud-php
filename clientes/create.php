<?php
require_once "../config/config.php";
require_once "../config/database.php";
require_once "../includes/validaciones.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errores = [];
    
    $valores = [
        'nombre' => isset($_POST['nombre']) ? trim($_POST['nombre']) : '',
        'apellido' => isset($_POST['apellido']) ? trim($_POST['apellido']) : '',
        'documento_identidad' => isset($_POST['documento_identidad']) ? trim($_POST['documento_identidad']) : '',
        'telefono' => isset($_POST['telefono']) ? trim($_POST['telefono']) : '',
        'correo' => isset($_POST['correo']) ? trim($_POST['correo']) : '',
        'password' => $_POST['password'] ?? '',
        'direccion' => isset($_POST['direccion']) ? trim($_POST['direccion']) : ''
    ];

    if (!validarAlfanumerico($valores['nombre'], 30)) {
        $errores[] = "El nombre es requerido, debe ser alfanumérico y tener máximo 30 caracteres";
    }

    if (!validarAlfanumerico($valores['apellido'], 30)) {
        $errores[] = "El apellido es requerido, debe ser alfanumérico y tener máximo 30 caracteres";
    }

    if (!validarNumerico($valores['documento_identidad'], 12)) {
        $errores[] = "El documento es requerido, debe ser numérico y tener máximo 12 caracteres";
    }

    if (!validarNumerico($valores['telefono'], 12)) {
        $errores[] = "El teléfono es requerido, debe ser numérico y tener máximo 12 caracteres";
    }

    if (!validarEmail($valores['correo'])) {
        $errores[] = "El correo electrónico es requerido y debe ser válido";
    }

    if (!validarPassword($valores['password'])) {
        $errores[] = "La contraseña debe tener entre 8 y 16 caracteres, incluir mayúsculas, minúsculas, números y caracteres especiales";
    }

    if (!empty($valores['direccion']) && !validarAlfanumerico($valores['direccion'], 50)) {
        $errores[] = "La dirección debe ser alfanumérica y tener máximo 50 caracteres";
    }

    if (!validarArchivo($_FILES['documento_cedula'])) {
        $errores[] = "El documento escaneado es requerido y debe ser PDF, JPG o PNG";
    }

    if (empty($errores)) {
        try {
            $stmt = $mysqli->prepare("SELECT id FROM clientes WHERE documento_identidad = ? OR correo = ? LIMIT 1");
            $stmt->bind_param("ss", $valores['documento_identidad'], $valores['correo']);
            $stmt->execute();
            
            if ($stmt->get_result()->num_rows > 0) {
                throw new Exception("El documento de identidad o correo ya está registrado");
            }

            $extension = pathinfo($_FILES['documento_cedula']['name'], PATHINFO_EXTENSION);
            $nombre_archivo = uniqid() . "." . $extension;
            $ruta_archivo = "../uploads/" . $nombre_archivo;
            
            if (move_uploaded_file($_FILES['documento_cedula']['tmp_name'], $ruta_archivo)) {
                $stmt = $mysqli->prepare("
                    INSERT INTO clientes (
                        nombre, apellido, documento_identidad, telefono, 
                        correo, password, documento_cedula, direccion
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ");

                $password_hash = password_hash($valores['password'], PASSWORD_DEFAULT);
                
                $stmt->bind_param(
                    "ssssssss",
                    $valores['nombre'],
                    $valores['apellido'],
                    $valores['documento_identidad'],
                    $valores['telefono'],
                    $valores['correo'],
                    $password_hash,
                    $nombre_archivo,
                    $valores['direccion']
                );

                if ($stmt->execute()) {
                    $_SESSION['mensaje'] = "Cliente registrado exitosamente";
                    $_SESSION['tipo_mensaje'] = "success";
                    header("Location: ../index.php");
                    exit;
                } else {
                    throw new Exception("Error al registrar el cliente");
                }
            } else {
                throw new Exception("Error al subir el archivo");
            }
        } catch (Exception $e) {
            $_SESSION['mensaje'] = "Error: " . $e->getMessage();
            $_SESSION['tipo_mensaje'] = "danger";
            if (isset($ruta_archivo) && file_exists($ruta_archivo)) {
                unlink($ruta_archivo);
            }
        }
    } else {
        $_SESSION['mensaje'] = "Errores de validación: " . implode(", ", $errores);
        $_SESSION['tipo_mensaje'] = "danger";
    }
    
    header("Location: ../index.php");
    exit;
}

header("Location: ../index.php");
exit; 