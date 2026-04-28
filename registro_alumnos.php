<?php
// 1. "Llamamos" a la conexión que ya configuramos
include("conexion.php"); 

// 2. Revisamos si el usuario hizo clic en el botón "Registrar"
if (isset($_POST['enviar'])) {
    
    // Guardamos los datos del formulario en variables
    // mysqli_real_escape_string ayuda a evitar errores con comillas o caracteres raros
    $nombre   = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $apellido = mysqli_real_escape_string($conexion, $_POST['apellido']);
    $email    = mysqli_real_escape_string($conexion, $_POST['email']);

    // 3. Creamos la orden SQL (Asegúrate de que las columnas se llamen así en phpMyAdmin)
    $sql = "INSERT INTO alumnos (nombre, apellido, email) VALUES ('$nombre', '$apellido', '$email')";

    // 4. Ejecutamos la orden
    if (mysqli_query($conexion, $sql)) {
        echo "<p style='color:green;'>¡Alumno registrado correctamente!</p>";
    } else {
        echo "<p style='color:red;'>Error al registrar: " . mysqli_error($conexion) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Escuela</title>
    <style>
        body { font-family: sans-serif; background: #f0f2f5; display: flex; flex-direction: column; align-items: center; }
        form { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 300px; }
        input { width: 100%; margin-bottom: 15px; padding: 8px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
        a { margin-top: 15px; text-decoration: none; color: #555; }
    </style>
</head>
<body>

    <h2>Registrar Nuevo Alumno</h2>

    <form action="registro_alumnos.php" method="POST">
        <label>Nombre:</label>
        <input type="text" name="nombre" required>

        <label>Apellido:</label>
        <input type="text" name="apellido" required>

        <label>Correo Electrónico:</label>
        <input type="email" name="email" required>

        <button type="submit" name="enviar">Guardar en Base de Datos</button>
    </form>

    <a href="index.php">← Volver al Menú</a>

</body>
</html>