<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema Escolar - Inicio</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; margin: 0; padding: 20px; text-align: center; }
        .welcome-container { max-width: 800px; margin: 50px auto; background: white; padding: 40px; border-radius: 15px; shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .grid-menu { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 30px; }
        .card { background: #3498db; color: white; padding: 25px; border-radius: 10px; text-decoration: none; font-weight: bold; transition: 0.3s; }
        .card:hover { background: #2980b9; transform: translateY(-5px); }
        .card.profes { background: #e67e22; }
        .card.calif { background: #9b59b6; }
    </style>
</head>
<body>

    <?php include("menu.php"); ?>

    <div class="welcome-container">
        <h1>Sistema Universidad San Carlos</h1>
        <p>Selecciona una opción para gestionar el sistema</p>

        <div class="grid-menu">
            <a href="login_alumno.php" class="card">° Gestión de Alumnos</a>
            <a href="profesores.php" class="card profes">° Gestión de Profesores</a>
            <a href="calificaciones.php" class="card calif">° Calificaciones</a>
        </div>
    </div>

</body>
</html>