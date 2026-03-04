<?php
session_start();
require 'conexion.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $correo = $_POST['correo'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($correo) || empty($password)) {
        
        echo "Error: Debes proporcionar ambos datos.";
        exit();
    }

    try {
        
        $stmt = $conexion->prepare('SELECT * FROM usuario WHERE correo = :correo');
        $stmt->execute([':correo' => $correo]);
        $user = $stmt->fetch();

        if ($user && $password === $user['password']) { 
            
            $_SESSION['id_usuario'] = $user['id_usuario'];
            $_SESSION['nombre'] = $user['nombre_completo'];

            
            $ip = $_SERVER['REMOTE_ADDR'];
            $log_stmt = $conexion->prepare('INSERT INTO bitacora (id_usuario, direccion_ip, exito) VALUES (:id_usuario, :ip, 1)');
            $log_stmt->execute([':id_usuario' => $user['id_usuario'], ':ip' => $ip]);

            header("Location: dashboard.php");
            exit();
        } else {
            
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