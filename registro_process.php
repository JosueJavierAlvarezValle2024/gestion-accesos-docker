<?php
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $password = $_POST['password'] ?? '';
    $id_tipo = $_POST['id_tipo'] ?? '';

    if (empty($nombre) || empty($correo) || empty($password) || empty($id_tipo)) {
        die("Error: Todos los campos son obligatorios.");
    }

    try {
        $stmt = $conexion->prepare('INSERT INTO usuario (nombre_completo, correo, password, id_tipo) VALUES (?, ?, ?, ?)');
        $stmt->execute([$nombre, $correo, $password, $id_tipo]);

        echo "<script>alert('Usuario registrado con éxito'); window.location.href='index.html';</script>";
    } catch (PDOException $e) {
        die("Error al registrar: " . $e->getMessage());
    }
}
?>