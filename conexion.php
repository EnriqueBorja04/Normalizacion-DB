<?php
// Configuración de la conexión
$host = "127.0.0.1:3307"; // Forzamos el puerto 3307 que vimos en tu config
$user = "root";
$pass = "";               // Según tu config.inc.php, está vacío
$db   = "escuela"; 

// Crear la conexión
$conexion = mysqli_connect($host, $user, $pass, $db);

// Verificar si hay errores
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Configurar caracteres (ñ y acentos)
mysqli_set_charset($conexion, "utf8");

// Si llegamos aquí, todo está bien
echo "¡Bienvenido al sistema";
?>