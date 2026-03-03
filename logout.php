<?php
session_start(); // Localiza la sesión actual
session_unset(); // Limpia las variables de sesión
session_destroy(); // Destruye la sesión por completo

// Redirige al login
header("Location: index.html");
exit();
?>