<?php
// Credenciales de tu contenedor Docker MariaDB
$host = "host.docker.internal"; 
$port = 3307; 
$user = "root";
$pass = "root";
$db_name = "sistema_web";

try {
    // Usamos PDO (PHP Data Objects) por seguridad y versatilidad
    $dsn = "mysql:host=$host;port=$port;dbname=$db_name;charset=utf8mb4";
    
    $conexion = new PDO($dsn, $user, $pass);
    
    // Configuramos PDO para que arroje excepciones en caso de error (crucial para depurar)
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Comenta o borra esta línea cuando pases a producción
    // echo "Conexión exitosa a la base de datos."; 

} catch (PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
?>