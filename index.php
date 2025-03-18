<?php
require_once "config/config.php";
require_once "config/database.php";

$titulo_pagina = "Sistema de Clientes";


try {
    $sql = "SELECT * FROM clientes ORDER BY fecha_registro DESC";
    $result = $mysqli->query($sql);
    $clientes = $result->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    error_log($e->getMessage());
    $_SESSION['mensaje'] = "Error al obtener la lista de clientes";
    $_SESSION['tipo_mensaje'] = "danger";
}

include_once "includes/header.php";
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">
                            <i class="bi bi-house-door"></i> Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#modalNuevoCliente">
                            <i class="bi bi-person-plus"></i> Nuevo Cliente
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Lista de Clientes</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="input-group">
                        <input type="text" class="form-control" id="searchInput" placeholder="Buscar clientes...">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoCliente">
                            <i class="bi bi-person-plus"></i> Nuevo Cliente
                        </button>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Documento</th>
                            <th>Teléfono</th>
                            <th>Correo</th>
                            <th>Dirección</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($clientes)): ?>
                            <tr>
                                <td colspan="6" class="text-center">No hay clientes registrados</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($clientes as $cliente): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido']); ?></td>
                                    <td><?php echo htmlspecialchars($cliente['documento_identidad']); ?></td>
                                    <td><?php echo htmlspecialchars($cliente['telefono']); ?></td>
                                    <td><?php echo htmlspecialchars($cliente['correo']); ?></td>
                                    <td><?php echo htmlspecialchars($cliente['direccion'] ?? ''); ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-info" onclick="verCliente(<?php echo $cliente['id']; ?>)">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-warning" onclick="editarCliente(<?php echo $cliente['id']; ?>)">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="eliminarCliente(<?php echo $cliente['id']; ?>)">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<div class="modal fade" id="modalNuevoCliente" tabindex="-1" aria-labelledby="modalNuevoClienteLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNuevoClienteLabel">
                    <i class="bi bi-person-plus"></i> Nuevo Cliente
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="clientes/create.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required maxlength="30">
                    </div>
                    <div class="mb-3">
                        <label for="apellido" class="form-label">Apellido</label>
                        <input type="text" class="form-control" id="apellido" name="apellido" required maxlength="30">
                    </div>
                    <div class="mb-3">
                        <label for="documento_identidad" class="form-label">Documento de Identidad</label>
                        <input type="text" class="form-control" id="documento_identidad" name="documento_identidad" required maxlength="12">
                    </div>
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="tel" class="form-control" id="telefono" name="telefono" required maxlength="12">
                    </div>
                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="correo" name="correo" required maxlength="100">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="direccion" name="direccion" maxlength="50">
                    </div>
                    <div class="mb-3">
                        <label for="documento_cedula" class="form-label">Documento Escaneado</label>
                        <input type="file" class="form-control" id="documento_cedula" name="documento_cedula" accept=".pdf,.jpg,.jpeg,.png" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cliente</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditarCliente" tabindex="-1" aria-labelledby="modalEditarClienteLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarClienteLabel">
                    <i class="bi bi-pencil"></i> Editar Cliente
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="clientes/update.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" id="editar_id" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editar_nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="editar_nombre" name="nombre" required maxlength="30">
                    </div>
                    <div class="mb-3">
                        <label for="editar_apellido" class="form-label">Apellido</label>
                        <input type="text" class="form-control" id="editar_apellido" name="apellido" required maxlength="30">
                    </div>
                    <div class="mb-3">
                        <label for="editar_documento_identidad" class="form-label">Documento de Identidad</label>
                        <input type="text" class="form-control" id="editar_documento_identidad" name="documento_identidad" required maxlength="12">
                    </div>
                    <div class="mb-3">
                        <label for="editar_telefono" class="form-label">Teléfono</label>
                        <input type="tel" class="form-control" id="editar_telefono" name="telefono" required maxlength="12">
                    </div>
                    <div class="mb-3">
                        <label for="editar_correo" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="editar_correo" name="correo" required maxlength="100">
                    </div>
                    <div class="mb-3">
                        <label for="editar_password" class="form-label">Contraseña (dejar en blanco para mantener la actual)</label>
                        <input type="password" class="form-control" id="editar_password" name="password">
                    </div>
                    <div class="mb-3">
                        <label for="editar_direccion" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="editar_direccion" name="direccion" maxlength="50">
                    </div>
                    <div class="mb-3">
                        <label for="editar_documento_cedula" class="form-label">Documento Escaneado (opcional)</label>
                        <input type="file" class="form-control" id="editar_documento_cedula" name="documento_cedula" accept=".pdf,.jpg,.jpeg,.png">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalVerCliente" tabindex="-1" aria-labelledby="modalVerClienteLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalVerClienteLabel">
                    <i class="bi bi-person"></i> Detalles del Cliente
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold">Nombre:</div>
                    <div class="col-sm-8" id="ver_nombre"></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold">Apellido:</div>
                    <div class="col-sm-8" id="ver_apellido"></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold">Documento:</div>
                    <div class="col-sm-8" id="ver_documento_identidad"></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold">Teléfono:</div>
                    <div class="col-sm-8" id="ver_telefono"></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold">Correo:</div>
                    <div class="col-sm-8" id="ver_correo"></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold">Dirección:</div>
                    <div class="col-sm-8" id="ver_direccion"></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold">Documento Escaneado:</div>
                    <div class="col-sm-8">
                        <a href="#" id="ver_documento_cedula" target="_blank" class="btn btn-sm btn-primary">
                            <i class="bi bi-file-earmark"></i> Ver Documento
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});

function verCliente(id) {
    fetch(`clientes/read.php?id=${id}`)
        .then(response => response.json())
        .then(cliente => {
            document.getElementById('ver_nombre').textContent = cliente.nombre;
            document.getElementById('ver_apellido').textContent = cliente.apellido;
            document.getElementById('ver_documento_identidad').textContent = cliente.documento_identidad;
            document.getElementById('ver_telefono').textContent = cliente.telefono;
            document.getElementById('ver_correo').textContent = cliente.correo;
            document.getElementById('ver_direccion').textContent = cliente.direccion || 'No especificada';
            
            const documentoLink = document.getElementById('ver_documento_cedula');
            if (cliente.documento_cedula) {
                documentoLink.href = cliente.documento_url;
                documentoLink.style.display = 'inline-flex';
            } else {
                documentoLink.style.display = 'none';
            }
            
            new bootstrap.Modal(document.getElementById('modalVerCliente')).show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar los datos del cliente');
        });
}

function editarCliente(id) {
    fetch(`clientes/read.php?id=${id}`)
        .then(response => response.json())
        .then(cliente => {
            document.getElementById('editar_id').value = cliente.id;
            document.getElementById('editar_nombre').value = cliente.nombre;
            document.getElementById('editar_apellido').value = cliente.apellido;
            document.getElementById('editar_documento_identidad').value = cliente.documento_identidad;
            document.getElementById('editar_telefono').value = cliente.telefono;
            document.getElementById('editar_correo').value = cliente.correo;
            document.getElementById('editar_direccion').value = cliente.direccion || '';
            
            new bootstrap.Modal(document.getElementById('modalEditarCliente')).show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar los datos del cliente');
        });
}

function eliminarCliente(id) {
    if (confirm('¿Está seguro de que desea eliminar este cliente?')) {
        window.location.href = `clientes/delete.php?id=${id}`;
    }
}
</script>

<?php include_once "includes/footer.php"; ?> 