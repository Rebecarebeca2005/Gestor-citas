<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gestor de Citas | Organiza tus reservas fácilmente</title>
    <meta name="description" content="Gestiona tus citas, reservas y horarios de forma rápida y sencilla con nuestro gestor online. Organiza todo en un solo lugar.">
    <meta name="keywords" content="gestor de citas, reservas online, agenda digital, citas online, organizar citas">
    <meta name="author" content="Rebeca">

    <meta name="robots" content="index, follow">

    <link rel="shortcut icon" href="assets/img/30-dias.png" type="image/x-icon">

    <link rel="stylesheet" href="assets/css/centroControl.css">
</head>
<body>

<?php
$nombre = $_SESSION['usuario']['nombre'] ?? 'Admin';
?>

<div class="sidebar" id="sidebar">
    <div class="close-sidebar" onclick="toggleMenu()">✖</div>

    <div class="sidebar-header">
        <h3>Admin: <?= htmlspecialchars($nombre) ?></h3>
    </div>

    <nav class="sidebar-menu">
        <a href="index.php?pagina=centroControlAdmin">Inicio</a>
        <a href="index.php?pagina=gestionUsuarios">Usuarios</a>
        <a href="index.php?pagina=gestionCitas">Citas</a>
        <a href="index.php?pagina=calendarioGlobal">Calendario</a>
        <a href="index.php?pagina=estadisticas">Estadísticas</a>
        <a href="index.php?pagina=logout">Cerrar sesión</a>
    </nav>
</div>

<header class="topbar">
    <img src="assets/img/hamburguesa.png" class="menu-icon" onclick="toggleMenu()">
    <h2>Panel de administración</h2>
</header>

<main class="contenido">

    <!-- SALUDO -->
    <section class="saludo">
        <h1>Bienvenido, <?= htmlspecialchars($nombre) ?></h1>
        <p>Control total del sistema de citas</p>
    </section>

    <!-- MÉTRICAS -->
    <section class="grid-resumen">

        <div class="card">
            <h3>Usuarios</h3>
            <p>-- total registrados</p>
        </div>

        <div class="card">
            <h3>Citas hoy</h3>
            <p>-- citas activas</p>
        </div>

        <div class="card">
            <h3>Citas mes</h3>
            <p>-- este mes</p>
        </div>

        <div class="card">
            <h3>Canceladas</h3>
            <p>-- cancelaciones</p>
        </div>

    </section>

    <!-- ACCIONES RÁPIDAS -->
    <section class="acciones-carousel">
        <h2>Acciones rápidas</h2>

        <div class="scene">

            <div class="left-zone">
                <ul class="list">

                    <li class="item">
                        <input type="radio" id="a1" name="acciones" checked>
                        <label for="a1">Crear usuario</label>

                        <div class="content">
                            <div class="content-full">
                                <h1>Nuevo usuario</h1>
                                <p>Crear cliente o trabajadora.</p>
                                <button class="btn">Ir</button>
                            </div>
                        </div>
                    </li>

                    <li class="item">
                        <input type="radio" id="a2" name="acciones">
                        <label for="a2">Ver citas</label>

                        <div class="content">
                            <div class="content-full">
                                <h1>Gestión de citas</h1>
                                <p>Ver y editar todas las citas del sistema.</p>
                                <button class="btn">Ir</button>
                            </div>
                        </div>
                    </li>

                    <li class="item">
                        <input type="radio" id="a3" name="acciones">
                        <label for="a3">Calendario</label>

                        <div class="content">
                            <div class="content-full">
                                <h1>Calendario global</h1>
                                <p>Visualiza todas las reservas del sistema.</p>
                                <button class="btn">Ir</button>
                            </div>
                        </div>
                    </li>

                    <li class="item">
                        <input type="radio" id="a4" name="acciones">
                        <label for="a4">Estadísticas</label>

                        <div class="content">
                            <div class="content-full">
                                <h1>Estadísticas</h1>
                                <p>Análisis del sistema de citas.</p>
                                <button class="btn">Ir</button>
                            </div>
                        </div>
                    </li>

                </ul>
            </div>
        </div>
    </section>

    <!-- ACTIVIDAD RECIENTE -->
    <section class="card" style="margin-top:40px;">
        <h3>Actividad reciente</h3>
        <p>• Usuario X creó una cita</p>
        <p>• Usuario Y canceló una cita</p>
        <p>• Nuevo usuario registrado</p>
    </section>

</main>
</body>
</html>

<script>
function toggleMenu() {
    document.getElementById("sidebar").classList.toggle("active");
}
</script>