<?php
session_start();
require 'conexion.php';

// Si no hay sesión, regresamos al login por seguridad
if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.html");
    exit();
}

try {
    // Consulta para traer los últimos 10 accesos con el nombre del usuario
    $query = "SELECT b.id_registro, u.correo, b.fecha_acceso, b.direccion_ip, b.exito 
              FROM bitacora b 
              INNER JOIN usuario u ON b.id_usuario = u.id_usuario 
              ORDER BY b.fecha_acceso DESC LIMIT 10";
    $stmt = $conexion->prepare($query);
    $stmt->execute();
    $registros = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error al leer la bitácora: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Historial de Accesos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="#"><i class="bi bi-shield-lock me-2"></i>Sistema de Accesos</a>
        <div class="d-flex align-items-center">
            <span class="navbar-text me-3 text-white">
                <i class="bi bi-person-circle"></i> Hola, <?php echo htmlspecialchars($_SESSION['nombre']); ?>
            </span>
            <a href="logout.php" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-box-arrow-left"></i> Cerrar Sesión
            </a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4"><i class="bi bi-list-check me-2"></i>Historial de Accesos (Bitácora)</h2>
            
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th># Reg.</th>
                                    <th>Usuario (Correo)</th>
                                    <th>Fecha y Hora</th>
                                    <th>Dirección IP</th>
                                    <th>Estado de Acceso</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($registros) > 0): ?>
                                    <?php foreach ($registros as $row): ?>
                                    <tr>
                                        <td><?php echo $row['id_registro']; ?></td>
                                        <td><?php echo htmlspecialchars($row['correo']); ?></td>
                                        <td><?php echo $row['fecha_acceso']; ?></td>
                                        <td><?php echo $row['direccion_ip']; ?></td>
                                        <td>
                                            <?php if ($row['exito']): ?>
                                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Correcto</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Fallido</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">No hay registros de actividad aún.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <p class="text-muted mt-3 small">
                <i class="bi bi-info-circle me-1"></i> Mostrando los últimos 10 intentos de inicio de sesión.
            </p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>