# Sistema de Gestión de Clientes

Sistema de Gestión de Clientes es una aplicación web que permite realizar operaciones CRUD (Crear, Leer, Actualizar y Eliminar) sobre clientes. Desarrollado con PHP 8.1, MySQL y Bootstrap 5, ofrece una interfaz moderna y responsiva, con validaciones tanto en frontend como en backend para garantizar la integridad de los datos.

## Versiones Utilizadas

- PHP 8.1.2
- MySQL 8.0.31
- Apache 2.4.54
- Bootstrap 5.3.2
- jQuery 3.7.1

## Características

-  Interfaz moderna y responsiva
- Búsqueda en tiempo real de clientes
- Diseño mobile-first
- Validaciones tanto en frontend como backend
- Gestión de documentos escaneados

## Requisitos del Sistema

- PHP >= 8.1
- MySQL >= 8.0
- Apache >= 2.4 o Nginx >= 1.18
- Extensiones PHP requeridas:
  - mysqli
  - fileinfo
  - gd

## Estructura de la Base de Datos

```sql
CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(30) NOT NULL,
    apellido VARCHAR(30) NOT NULL,
    documento_identidad VARCHAR(12) NOT NULL,
    telefono VARCHAR(12) NOT NULL,
    correo VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    direccion VARCHAR(50),
    documento_cedula VARCHAR(255),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## Funcionalidades

### 1. Gestión de Clientes
- Crear nuevos clientes
- Ver detalles de clientes
- Editar información de clientes
- Eliminar clientes

### 2. Validaciones
- **Frontend (JavaScript):**
  - Nombre y apellido: alfanumérico, máximo 30 caracteres
  - Documento: numérico, máximo 12 caracteres
  - Teléfono: numérico, máximo 12 caracteres
  - Correo: formato válido, máximo 100 caracteres
  - Contraseña: 8-16 caracteres, incluye mayúsculas, minúsculas, números y caracteres especiales
  - Dirección: alfanumérico, máximo 50 caracteres
  - Documento escaneado: PDF, JPEG, PNG

- **Backend (PHP):**
  - Validación de datos requeridos
  - Validación de formatos
  - Validación de duplicados
  - Sanitización de entradas

### 3. Características de la Interfaz
- Barra de búsqueda en tiempo real
- Modales para crear, ver y editar clientes
- Botones de acción con iconos intuitivos
- Mensajes de confirmación y error
- Diseño responsivo para todos los dispositivos

## Instrucciones de Instalación

1. Clonar el repositorio:
   ```bash
   git clone https://github.com/elian0826/Crud-php.git
   cd Crud-php
   ```

2. Configurar el archivo de base de datos (`config/database.php`):
   ```php
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'evolutecc_db');
    define('DB_PORT', '3306');
   ```

3. Importar la estructura de la base de datos:
   ```sql
   CREATE TABLE clientes (
       id INT AUTO_INCREMENT PRIMARY KEY,
       nombre VARCHAR(30) NOT NULL,
       apellido VARCHAR(30) NOT NULL,
       documento_identidad VARCHAR(12) NOT NULL,
       telefono VARCHAR(12) NOT NULL,
       correo VARCHAR(100) NOT NULL,
       password VARCHAR(255) NOT NULL,
       direccion VARCHAR(50),
       documento_cedula VARCHAR(255),
       fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   );
   ```

4. Configurar el servidor web:
   - Para Apache, asegurarse de tener el módulo `mod_rewrite` habilitado
   - Configurar el archivo `.htaccess`:
     ```apache
     RewriteEngine On
     RewriteCond %{REQUEST_FILENAME} !-f
     RewriteCond %{REQUEST_FILENAME} !-d
     RewriteRule ^(.*)$ index.php/$1 [L]
     ```

5. Configurar permisos:
   ```bash
   chmod 755 -R ./
   chmod 777 -R ./uploads
   ```

## Instrucciones de Uso

1. **Acceso al Sistema**
   - URL: `http://localhost:8000`
   - No requiere autenticación inicial

2. **Gestión de Clientes**
   - Crear: Click en "Nuevo Cliente" y llenar el formulario
   - Ver: Click en el ícono del ojo 
   - Editar: Click en el ícono del lápiz 
   - Eliminar: Click en el ícono de la papelera 

3. **Búsqueda de Clientes**
   - Usar la barra de búsqueda en la parte superior
   - La búsqueda es en tiempo real
   - Busca en todos los campos visibles

4. **Gestión de Documentos**
   - Formatos permitidos: PDF, JPEG, PNG
   - Tamaño máximo: 5MB
   - Ubicación: `/uploads/cedulas/`


