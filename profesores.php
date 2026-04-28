<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("conexion.php"); 

// --- 1. LÓGICA PARA ELIMINAR ---
if (isset($_GET['eliminar'])) {
    $id_el = mysqli_real_escape_string($conexion, $_GET['eliminar']);
    $sql_del = "DELETE FROM profesores WHERE id_profesor = '$id_el'";
    if (mysqli_query($conexion, $sql_del)) {
        header("Location: profesores.php?deleted=1");
        exit();
    }
}

// --- 2. LÓGICA PARA REGISTRAR ---
if (isset($_POST['enviar'])) {
    $id_profesor  = mysqli_real_escape_string($conexion, $_POST['id_profesor']);
    $nombre       = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $apellido     = mysqli_real_escape_string($conexion, $_POST['apellido']);
    $especialidad = mysqli_real_escape_string($conexion, $_POST['especialidad']);

    $sql = "INSERT INTO profesores (id_profesor, nombre, apellido, especialidad) 
            VALUES ('$id_profesor', '$nombre', '$apellido', '$especialidad')";
    
    if (mysqli_query($conexion, $sql)) {
        header("Location: profesores.php?success=1");
        exit();
    }
}

// --- 3. CONSULTA PARA LA TABLA ---
$sql_consulta = "SELECT * FROM profesores";
$resultado = mysqli_query($conexion, $sql_consulta);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Profesores</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f0f2f5; padding: 20px; }
        .container { max-width: 850px; margin: auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
        
        /* BOTÓN ELEGANTE PARA VOLVER AL INDEX */
        .header-nav { display: flex; justify-content: flex-end; margin-bottom: 20px; }
        .btn-index { 
            background: #2c3e50; 
            color: #ffffff; 
            text-decoration: none; 
            padding: 10px 20px; 
            border-radius: 50px; 
            font-size: 14px; 
            font-weight: 600;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .btn-index:hover { 
            background: #e67e22; 
            transform: translateY(-2px); 
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }
        .btn-index::before {
            content: '←';
            margin-right: 8px;
            font-size: 18px;
        }

        h2 { color: #2c3e50; border-left: 5px solid #e67e22; padding-left: 15px; margin-top: 0; }
        .form-box { background: #ffffff; padding: 20px; border-radius: 10px; margin-bottom: 30px; border: 1px solid #e1e4e8; }
        form { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        input, select { padding: 12px; border: 1px solid #d1d5da; border-radius: 6px; outline: none; transition: 0.2s; }
        input:focus, select:focus { border-color: #e67e22; box-shadow: 0 0 0 3px rgba(230, 126, 34, 0.1); }
        .btn-add { grid-column: span 2; padding: 14px; background: #e67e22; color: white; border: none; cursor: pointer; font-weight: bold; border-radius: 6px; font-size: 16px; transition: 0.3s; }
        .btn-add:hover { background: #d35400; }

        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px 15px; border-bottom: 1px solid #edf2f7; text-align: left; }
        th { background: #f8f9fa; color: #4a5568; font-weight: 600; text-transform: uppercase; font-size: 12px; }
        .btn-del { background: #fee2e2; color: #dc2626; padding: 6px 12px; text-decoration: none; border-radius: 6px; font-size: 12px; font-weight: 600; transition: 0.2s; }
        .btn-del:hover { background: #dc2626; color: white; }
    </style>
</head>
<body>

<div class="container">
    <div class="header-nav">
        <a href="index.php" class="btn-index">Volver al Inicio</a>
    </div>

    <h2>Gestión de Profesores</h2>

    <?php if(isset($_GET['success'])) echo "<p style='color:#059669; font-weight:bold;'>✅ Registro exitoso</p>"; ?>
    <?php if(isset($_GET['deleted'])) echo "<p style='color:#dc2626; font-weight:bold;'>🗑️ Profesor eliminado</p>"; ?>

    <div class="form-box">
        <form method="POST">
            <input type="number" name="id_profesor" placeholder="Número de Cédula" required>
            
            <select name="especialidad" required>
                <option value="">Seleccione Especialidad</option>
                <option value="Matematicas">Matemáticas</option>
                <option value="Espanol">Español</option>
                <option value="Ciencias">Ciencias</option>
                <option value="Historia">Historia</option>
                <option value="Ingles">Inglés</option>
                <option value="Educacion Fisica">Educación Física</option>
            </select>
            
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="text" name="apellido" placeholder="Apellido" required>
            <button type="submit" name="enviar" class="btn-add">GUARDAR NUEVO PROFESOR</button>
        </form>
    </div>

    <h3 style="color: #4a5568;">Profesores en el Sistema</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre Completo</th>
                <th>Especialidad</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if ($resultado && mysqli_num_rows($resultado) > 0) {
                while($f = mysqli_fetch_assoc($resultado)) {
                    echo "<tr>
                            <td style='font-weight:bold; color:#2c3e50;'>{$f['id_profesor']}</td>
                            <td>{$f['nombre']} {$f['apellido']}</td>
                            <td><span style='background:#f1f5f9; padding:4px 8px; border-radius:4px;'>{$f['especialidad']}</span></td>
                            <td>
                                <a href='profesores.php?eliminar={$f['id_profesor']}' 
                                   class='btn-del' 
                                   onclick='return confirm(\"¿Seguro que desea eliminar este registro?\")'>Eliminar</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4' style='text-align:center; color:#94a3b8;'>No se encontraron registros</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>