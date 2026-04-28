<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("conexion.php");

// --- 1. LÓGICA PARA ELIMINAR ---
if (isset($_GET['eliminar'])) {
    $id_m_el = mysqli_real_escape_string($conexion, $_GET['eliminar']);
    $sql_del = "DELETE FROM materia WHERE id_materia = '$id_m_el'";
    if (mysqli_query($conexion, $sql_del)) {
        header("Location: materias.php?deleted=1");
        exit();
    }
}

// --- 2. LÓGICA PARA REGISTRAR ---
if (isset($_POST['enviar'])) {
    $id_materia      = mysqli_real_escape_string($conexion, $_POST['id_materia']);
    $nombre_materia  = mysqli_real_escape_string($conexion, $_POST['nombre_materia']);
    $id_profesor     = mysqli_real_escape_string($conexion, $_POST['id_profesor']);

    // Verificamos si el ID de materia ya existe para evitar el error de Duplicate Entry
    $check = mysqli_query($conexion, "SELECT id_materia FROM materia WHERE id_materia = '$id_materia'");
    
    if (mysqli_num_rows($check) > 0) {
        header("Location: materias.php?error_id=1");
        exit();
    } else {
        $sql = "INSERT INTO materia (id_materia, nombre_materia, id_profesor) 
                VALUES ('$id_materia', '$nombre_materia', '$id_profesor')";
        
        if (mysqli_query($conexion, $sql)) {
            header("Location: materias.php?success=1");
            exit();
        } else {
            // Si sale el error de Foreign Key, lo atrapamos amigablemente
            header("Location: materias.php?error_fk=1");
            exit();
        }
    }
}

// --- 3. CONSULTAS PARA LA VISTA ---
// Consulta de materias con sus profesores
$sql_ver = "SELECT m.id_materia, m.nombre_materia, m.id_profesor, p.nombre, p.apellido 
            FROM materia m
            LEFT JOIN profesores p ON m.id_profesor = p.id_profesor";
$resultado = mysqli_query($conexion, $sql_ver);

// Consulta para llenar el SELECT de profesores (NUEVO)
$lista_profesores = mysqli_query($conexion, "SELECT id_profesor, nombre, apellido FROM profesores");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Materias</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; padding: 20px; }
        .container { max-width: 900px; margin: auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
        .header-nav { display: flex; justify-content: flex-end; margin-bottom: 20px; }
        .btn-index { background: #2c3e50; color: white; text-decoration: none; padding: 10px 20px; border-radius: 50px; font-weight: 600; }
        h2 { color: #2c3e50; border-left: 5px solid #3498db; padding-left: 15px; }
        .form-box { background: #f9f9f9; padding: 20px; border-radius: 10px; border: 1px solid #e1e4e8; margin-bottom: 30px; }
        form { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        input, select { padding: 12px; border: 1px solid #ccc; border-radius: 6px; }
        .btn-save { grid-column: span 2; padding: 14px; background: #3498db; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; border-bottom: 1px solid #eee; text-align: left; }
        th { background: #f8f9fa; }
        .btn-del { background: #fee2e2; color: #dc2626; padding: 6px 12px; text-decoration: none; border-radius: 6px; font-size: 12px; }
        .alert { padding: 10px; border-radius: 6px; margin-bottom: 10px; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <div class="header-nav">
        <a href="index.php" class="btn-index">Volver al Inicio</a>
    </div>

    <h2>Gestión de Materias</h2>
    
    <?php if(isset($_GET['success'])) echo "<div class='alert' style='background:#d4edda; color:green;'>✅ Materia registrada.</div>"; ?>
    <?php if(isset($_GET['error_id'])) echo "<div class='alert' style='background:#fff3cd; color:#856404;'>⚠️ El ID de materia ya existe.</div>"; ?>
    <?php if(isset($_GET['error_fk'])) echo "<div class='alert' style='background:#f8d7da; color:#721c24;'>❌ Error: El profesor seleccionado no es válido.</div>"; ?>

    <div class="form-box">
        <form method="POST">
            <input type="number" name="id_materia" placeholder="ID Materia" required>
            <input type="text" name="nombre_materia" placeholder="Nombre de Materia" required>
            
            <div style="grid-column: span 2;">
                <label>Seleccionar Profesor:</label>
                <select name="id_profesor" required style="width: 100%;">
                    <option value="">-- Seleccione un profesor registrado --</option>
                    <?php while($p = mysqli_fetch_assoc($lista_profesores)): ?>
                        <option value="<?php echo $p['id_profesor']; ?>">
                            <?php echo $p['nombre'] . " " . $p['apellido'] . " (ID: " . $p['id_profesor'] . ")"; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <button type="submit" name="enviar" class="btn-save">GUARDAR MATERIA</button>
        </form>
    </div>

    <h3>Materias Registradas</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Materia</th>
                <th>Profesor</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php while($f = mysqli_fetch_assoc($resultado)): ?>
            <tr>
                <td><b><?php echo $f['id_materia']; ?></b></td>
                <td><?php echo $f['nombre_materia']; ?></td>
                <td><?php echo !empty($f['nombre']) ? $f['nombre']." ".$f['apellido'] : "No asignado"; ?></td>
                <td>
                    <a href="materias.php?eliminar=<?php echo $f['id_materia']; ?>" class="btn-del" onclick="return confirm('¿Eliminar?')">Eliminar</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>