<?php
declare(strict_types=1);

class Validaciones {
    public static function validarNombreApellido(string $valor): bool {
        $valor = trim($valor);
        return !empty($valor) && 
               strlen($valor) <= 30 && 
               preg_match('/^[a-zA-Z0-9\s]+$/', $valor) === 1;
    }

    public static function validarDocumento(string $valor): bool {
        $valor = trim($valor);
        return !empty($valor) && 
               strlen($valor) <= 12 && 
               preg_match('/^[0-9]+$/', $valor) === 1;
    }

    public static function validarTelefono(string $valor): bool {
        $valor = trim($valor);
        return !empty($valor) && 
               strlen($valor) <= 12 && 
               preg_match('/^[0-9]+$/', $valor) === 1;
    }

    public static function validarCorreo(string $valor): bool {
        $valor = trim($valor);
        return !empty($valor) && 
               strlen($valor) <= 100 && 
               filter_var($valor, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function validarContrasena(string $valor): array {
        $errores = [];
        
        if (empty($valor)) {
            $errores[] = "La contraseña es obligatoria";
        }
        
        if (strlen($valor) > 16) {
            $errores[] = "La contraseña no debe exceder los 16 caracteres";
        }
        
        if (strlen($valor) < 8) {
            $errores[] = "La contraseña debe tener al menos 8 caracteres";
        }
        
        if (!preg_match('/[A-Z]/', $valor)) {
            $errores[] = "Debe contener al menos una mayúscula";
        }
        
        if (!preg_match('/[a-z]/', $valor)) {
            $errores[] = "Debe contener al menos una minúscula";
        }
        
        if (!preg_match('/[0-9]/', $valor)) {
            $errores[] = "Debe contener al menos un número";
        }
        
        if (!preg_match('/[^A-Za-z0-9]/', $valor)) {
            $errores[] = "Debe contener al menos un carácter especial";
        }
        
        return $errores;
    }

    public static function validarDireccion(?string $valor): bool {
        if ($valor === null || $valor === '') {
            return true; 
        }
        $valor = trim($valor);
        return strlen($valor) <= 50 && 
               preg_match('/^[a-zA-Z0-9\s\-#.,]+$/', $valor) === 1;
    }

    public static function validarArchivoCedula(array $archivo): array {
        $errores = [];
        $permitidos = ['image/jpeg', 'image/png', 'application/pdf'];
        $maxSize = 5 * 1024 * 1024; 

        if (empty($archivo) || $archivo['error'] !== UPLOAD_ERR_OK) {
            $errores[] = "El documento de cédula es obligatorio";
            return $errores;
        }

        if (!isset($archivo['type']) || !in_array($archivo['type'], $permitidos)) {
            $errores[] = "El tipo de archivo no es válido. Se permiten: JPG, PNG y PDF";
        }

        if (!isset($archivo['size']) || $archivo['size'] > $maxSize) {
            $errores[] = "El archivo no debe superar los 5MB";
        }

        return $errores;
    }

    public static function sanitizar(string $valor): string {
        $valor = trim($valor);
        $valor = strip_tags($valor);
        return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
    }
}


function validarAlfanumerico($valor, $longitud_maxima) {
    return !empty($valor) && 
           strlen($valor) <= $longitud_maxima && 
           preg_match('/^[a-zA-Z0-9\s]+$/', $valor);
}


function validarNumerico($valor, $longitud_maxima) {
    return !empty($valor) && 
           strlen($valor) <= $longitud_maxima && 
           preg_match('/^[0-9]+$/', $valor);
}


function validarPassword($password) {
    if (strlen($password) < 8 || strlen($password) > 16) {
        return false;
    }
    
    
    if (!preg_match('/[A-Z]/', $password)) {
        return false;
    }
    
    if (!preg_match('/[a-z]/', $password)) {
        return false;
    }
    
    if (!preg_match('/[0-9]/', $password)) {
        return false;
    }
    
    if (!preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $password)) {
        return false;
    }
    
    return true;
}


function validarEmail($email) {
    return !empty($email) && 
           strlen($email) <= 100 && 
           filter_var($email, FILTER_VALIDATE_EMAIL);
}


function validarArchivo($archivo, $tipos_permitidos = ['pdf', 'jpg', 'jpeg', 'png']) {
    if (!isset($archivo) || $archivo['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    
    $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
    return in_array($extension, $tipos_permitidos);
} 