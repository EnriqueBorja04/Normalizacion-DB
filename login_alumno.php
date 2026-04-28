<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("conexion.php"); // Se conecta usando tu archivo existente

$alumno = null;

if (isset($_POST['acceder'])) {
    $id_buscado = mysqli_real_escape_string($conexion, $_POST['id_alumno']);
    
    // Consulta con los campos exactos de tu tabla
    $sql = "SELECT id_alumno, nombre, apellido, fecha_nacimiento, id_grado, id_salon 
            FROM alumnos 
            WHERE id_alumno = '$id_buscado'";
    
    $resultado = mysqli_query($conexion, $sql);

    if (mysqli_num_rows($resultado) > 0) {
        $alumno = mysqli_fetch_assoc($resultado);
    } else {
        $error = "La matrícula $id_buscado no existe.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consulta de Datos - Alumno</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; padding: 20px; }
        .login-box { max-width: 400px; margin: 40px auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); text-align: center; }
        .data-box { max-width: 500px; margin: 20px auto; background: white; padding: 25px; border-left: 5px solid #3498db; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #3498db; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
        .dato { margin: 10px 0; border-bottom: 1px solid #eee; padding-bottom: 5px; }
        b { color: #2c3e50; }
    </style>
</head>
<body>

    <?php include("menu.php"); ?>

    <div class="login-box">
        <h2>Área de Alumnos</h2>
        <form method="POST">
            <input type="text" name="id_alumno" placeholder="Tu Matrícula" required>
            <button type="submit" name="acceder">Consultar Mis Datos</button>
        </form>
        <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    </div>

    <?php if($alumno): ?>
    <div class="data-box">
        <h3 style="color: #3498db;">Tu Información</h3>
        <div class="dato"><b>Matrícula:</b> <?php echo $alumno['id_alumno']; ?></div>
        <div class="dato"><b>Nombre:</b> <?php echo $alumno['nombre']; ?></div>
        <div class="dato"><b>Apellido:</b> <?php echo $alumno['apellido']; ?></div>
        <div class="dato"><b>Fecha de Nacimiento:</b> <?php echo $alumno['fecha_nacimiento']; ?></div>
        <div class="dato"><b> Grado:</b> <?php echo $alumno['id_grado']; ?></div>
        <div class="dato"><b> Salón:</b> <?php echo $alumno['id_salon']; ?></div>
    </div>
    <?php endif; ?>

</body>
</html>