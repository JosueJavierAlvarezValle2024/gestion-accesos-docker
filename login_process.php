<?php
session_start();
require 'conexion.php'; // Incluye el archivo de conexión PDO que creaste antes.

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoge los datos de entrada
    $correo = $_POST['correo'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($correo) || empty($password)) {
        // Maneja error si envían cosas vacías
        echo "Error: Debes proporcionar ambos datos.";
        exit();
    }

    try {
        // Consulta para buscar el usuario
        $stmt = $conexion->prepare('SELECT * FROM usuario WHERE correo = :correo');
        $stmt->execute([':correo' => $correo]);
        $user = $stmt->fetch();

        if ($user && $password === $user['password']) { // Usa password_verify si las contraseñas fuesen hash
            // Login Exitoso
            $_SESSION['id_usuario'] = $user['id_usuario'];
            $_SESSION['nombre'] = $user['nombre_completo'];

            // ¡AQUI ENTRA LA BITÁCORA! Registramos un log de acceso EXITOSO.
            $ip = $_SERVER['REMOTE_ADDR'];
            $log_stmt = $conexion->prepare('INSERT INTO bitacora (id_usuario, direccion_ip, exito) VALUES (:id_usuario, :ip, 1)');
            $log_stmt->execute([':id_usuario' => $user['id_usuario'], ':ip' => $ip]);

            header("Location: dashboard.php");
            exit();
        } else {
            // Login Fallido: ¡También entra a bitácora!
            // Para el fallido asumo un usuario anónimo o un ID conocido (-1 si lo soportas).
            // Pero si *encontraste* el usuario pero falló la pass, úsalo:
            if ($user) {
               $ip = $_SERVER['REMOTE_ADDR'];
               $log_stmt = $conexion->prepare('INSERT INTO bitacora (id_usuario, direccion_ip, exito) VALUES (:id_usuario, :ip, 0)');
               $log_stmt->execute([':id_usuario' => $user['id_usuario'], ':ip' => $ip]);
            }

            echo "Error: Correo o contraseña incorrectos.";
            exit();
        }

    } catch (PDOException $e) {
        die("Error de sistema: " . $e->getMessage());
    }
} else {
     header("Location: index.html");
     exit();
}