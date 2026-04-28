<?php
include("conexion.php");

// --- LÓGICA PARA INSERTAR ---
if (isset($_POST['enviar'])) {
    $id_alumno = mysqli_real_escape_string($conexion, $_POST['id_alumno']);
    $id_materia = mysqli_real_escape_string($conexion, $_POST['id_materia']);
    $nota = mysqli_real_escape_string($conexion, $_POST['nota']);
    $fecha = mysqli_real_escape_string($conexion, $_POST['fecha_evaluacion']);

    $sql_insert = "INSERT INTO calificaciones (id_alumno, id_materia, nota, fecha_evaluacion) 
                   VALUES ('$id_alumno', '$id_materia', '$nota', '$fecha')";
    
    if (mysqli_query($conexion, $sql_insert)) {
        header("Location: calificaciones.php?success=1");
        exit();
    } else {
        die("Error al guardar: " . mysqli_error($conexion));
    }
}

// --- LÓGICA PARA ELIMINAR ---
if (isset($_POST['btn_eliminar'])) {
    $id_a_borrar = mysqli_real_escape_string($conexion, $_POST['id_calificacion']);
    $sql_delete = "DELETE FROM calificaciones WHERE id_calificacion = '$id_a_borrar'";
    
    if (mysqli_query($conexion, $sql_delete)) {
        header("Location: calificaciones.php?deleted=1");
        exit();
    } else {
        die("Error al eliminar: " . mysqli_error($conexion));
    }
}

// --- CONSULTAS PARA LOS SELECTS DEL FORMULARIO ---
$res_alumnos = mysqli_query($conexion, "SELECT id_alumno, nombre, apellido FROM alumnos ORDER BY nombre ASC");
$res_materias = mysqli_query($conexion, "SELECT id_materia, nombre_materia FROM materia ORDER BY nombre_materia ASC");

// --- CONSULTA PARA LA TABLA ---
$consulta = "SELECT 
                calificaciones.id_calificacion, 
                alumnos.nombre AS nombre_alumno, 
                alumnos.apellido AS apellido_alumno, 
                materia.nombre_materia AS nombre_materia, 
                calificaciones.nota, 
                profesores.nombre AS nombre_profe, 
                profesores.apellido AS apellido_profe
             FROM calificaciones 
             INNER JOIN alumnos ON calificaciones.id_alumno = alumnos.id_alumno 
             INNER JOIN materia ON calificaciones.id_materia = materia.id_materia
             LEFT JOIN profesores ON materia.id_profesor = profesores.id_profesor
             ORDER BY calificaciones.id_calificacion DESC";

$resultado = mysqli_query($conexion, $consulta);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Académico</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f7f6; padding: 20px; }
        .container { max-width: 1100px; margin: auto; background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        h2 { color: #2c3e50; border-bottom: 3px solid #3498db; padding-bottom: 10px; }
        
        form.registro { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; background: #ebf5fb; padding: 20px; border-radius: 8px; margin-bottom: 30px; }
        select, input { padding: 8px; border: 1px solid #ccc; border-radius: 4px; width: 100%; }
        .btn-guardar { grid-column: span 4; padding: 10px; background: #3498db; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background: #34495e; color: white; }
        tr:nth-child(even) { background: #f2f2f2; }
        .nota-box { font-weight: bold; color: #2980b9; background: #d6eaf8; padding: 5px 10px; border-radius: 4px; }
        .btn-delete { background: #e74c3c; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>

    <?php include("menu.php"); ?>

    <div class="container">
        <h2>Asignar Calificación</h2>
        
        <form method="POST" class="registro">
            <select name="id_alumno" required>
                <option value="">Seleccione Alumno</option>
                <?php while($row = mysqli_fetch_assoc($res_alumnos)): ?>
                    <option value="<?php echo $row['id_alumno']; ?>">
                        <?php echo $row['nombre'] . " " . $row['apellido']; ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <select name="id_materia" required>
                <option value="">Seleccione Materia</option>
                <?php while($row = mysqli_fetch_assoc($res_materias)): ?>
                    <option value="<?php echo $row['id_materia']; ?>">
                        <?php echo $row['nombre_materia']; ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <input type="number" step="0.1" name="nota" placeholder="Nota (Ej: 8.5)" required>
            <input type="date" name="fecha_evaluacion" required>
            
            <button type="submit" name="enviar" class="btn-guardar">Guardar Calificación</button>
        </form>

        <h2>Reporte de Calificaciones y Docentes</h2>
        <table>
            <thead>
                <tr>
                    <th>Alumno</th>
                    <th>Materia</th>
                    <th>Nota</th>
                    <th>Profesor</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while($f = mysqli_fetch_assoc($resultado)): ?>
                <tr>
                    <td><?php echo $f['nombre_alumno'] . " " . $f['apellido_alumno']; ?></td>
                    <td><?php echo $f['nombre_materia']; ?></td>
                    <td><span class="nota-box"><?php echo $f['nota']; ?></span></td>
                    <td><?php echo "Prof. " . $f['nombre_profe'] . " " . $f['apellido_profe']; ?></td>
                    <td>
                        <form method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta calificación?');">
                            <input type="hidden" name="id_calificacion" value="<?php echo $f['id_calificacion']; ?>">
                            <button type="submit" name="btn_eliminar" class="btn-delete">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>