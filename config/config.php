<?php
ini_set('session.cookie_httponly', '1');
ini_set('session.use_only_cookies', '1');
ini_set('session.cookie_secure', isset($_SERVER['HTTPS']) ? '1' : '0');

session_start();

if (version_compare(PHP_VERSION, '8.1.0', '<')) {
    die('Este sistema requiere PHP 8.1 o superior. Versión actual: ' . PHP_VERSION);
}

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'evolutecc_db');
define('DB_PORT', '3306');

define('APP_NAME', 'Sistema de Gestión de Clientes');
define('APP_VERSION', '1.0.0');

define('UPLOAD_DIR', __DIR__ . '/../uploads/cedulas/');
define('ALLOWED_FILES', ['pdf', 'jpg', 'jpeg', 'png']);
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB

define('RECORDS_PER_PAGE', 10);

define('MIN_PASSWORD_LENGTH', 8);

date_default_timezone_set('America/Bogota');

error_reporting(E_ALL);
ini_set('display_errors', '1');

$required_extensions = ['mysqli', 'fileinfo', 'gd'];
foreach ($required_extensions as $ext) {
    if (!extension_loaded($ext)) {
        die("La extensión PHP '$ext' es requerida para el funcionamiento del sistema.");
    }
}

function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function validate_nombre($nombre) {
    $nombre = trim($nombre);
    return strlen($nombre) <= 30 && 
           preg_match('/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]+$/', $nombre);
}

function validate_documento($documento) {
    $documento = trim($documento);
    return strlen($documento) <= 12 && 
           preg_match('/^[0-9]+$/', $documento);
}

function validate_telefono($telefono) {
    $telefono = trim($telefono);
    return strlen($telefono) <= 12 && 
           preg_match('/^[0-9]+$/', $telefono);
}

function validate_correo($correo) {
    $correo = trim($correo);
    return strlen($correo) <= 100 && 
           filter_var($correo, FILTER_VALIDATE_EMAIL);
}

function validate_password($password) {
    if (strlen($password) < MIN_PASSWORD_LENGTH) return false;
    if (!preg_match('/[A-Z]/', $password)) return false;
    if (!preg_match('/[a-z]/', $password)) return false;
    if (!preg_match('/[0-9]/', $password)) return false;
    if (!preg_match('/[^A-Za-z0-9]/', $password)) return false;
    return true;
}

function validate_direccion($direccion) {
    if (empty($direccion)) {
        return true; 
    }
    $direccion = trim($direccion);
    return strlen($direccion) <= 50 && 
           preg_match('/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ #-]+$/', $direccion);
}

function validate_file($file) {
    if ($file['size'] > MAX_FILE_SIZE) return false;
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    return in_array($ext, ALLOWED_FILES);
}

function get_password_strength($password) {
    $strength = 0;
    
    if (strlen($password) >= 8) $strength++;
    if (strlen($password) >= 12) $strength++;
    if (preg_match('/[A-Z]/', $password)) $strength++;
    if (preg_match('/[a-z]/', $password)) $strength++;
    if (preg_match('/[0-9]/', $password)) $strength++;
    if (preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) $strength++;
    
    return [
        'strength' => $strength,
        'message' => match($strength) {
            0, 1 => 'Muy débil',
            2 => 'Débil',
            3 => 'Media',
            4 => 'Fuerte',
            5, 6 => 'Muy fuerte'
        }
    ];
}

function generate_unique_filename($original_name) {
    $ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
    return uniqid() . '_' . time() . '.' . $ext;
}

function format_date($date) {
    return date('d M Y, H:i', strtotime($date));
}

if (!file_exists(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0755, true);
} 