<?php
require_once __DIR__ . '/config.php';

try {
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($mysqli->connect_error) {
        throw new Exception("Error de conexión: " . $mysqli->connect_error);
    }

    if (!$mysqli->set_charset("utf8mb4")) {
        throw new Exception("Error cargando el conjunto de caracteres utf8mb4: " . $mysqli->error);
    }

    $mysqli->query("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
    $mysqli->query("SET SESSION sql_mode = 'STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");

    $version = $mysqli->get_server_info();
    if (version_compare($version, '5.7.0', '<')) {
        throw new Exception("Este sistema requiere MySQL 5.7 o superior. Versión actual: " . $version);
    }

} catch (Exception $e) {
    error_log($e->getMessage());
    die('Error de conexión a la base de datos. Por favor, contacte al administrador.');
}

function escape_string($string) {
    global $mysqli;
    return $mysqli->real_escape_string($string);
}
?> 