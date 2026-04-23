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
$nombre = $_SESSION['usuario']['nombre'] ?? 'Usuario';
?>

<div class="sidebar" id="sidebar">
<div class="close-sidebar" onclick="toggleMenu()">✖</div>
    <div class="sidebar-header">
        <h3>Hola  <?= htmlspecialchars($nombre) ?></h3>
    </div>

    <nav class="sidebar-menu">
        <a href="#">Inicio</a>
        <a href="#">Mis citas</a>
        <a href="#">Nueva cita</a>
        <a href="#">Editar cita</a>
        <a href="#">Perfil</a>
        <a href="#">Cerrar sesión</a>
    </nav>

</div>

<header class="topbar">
    <img src="assets/img/hamburguesa.png" class="menu-icon" onclick="toggleMenu()">
    <h2>Panel de citas</h2>
</header>

<main class="contenido">

    <section class="saludo">
        <h1>Hola  <?= htmlspecialchars($nombre) ?></h1>
        <p>Esto es lo que tienes hoy</p>
    </section>

    <section class="grid-resumen">

        <div class="card">
            <h3>Próxima cita</h3>
            <p>Viernes 16:30</p>
        </div>

        <div class="card">
            <h3>Esta semana</h3>
            <p>2 citas programadas</p>
        </div>

        <div class="card">
            <h3>Total mes</h3>
            <p>5 citas</p>
        </div>

    </section>

    <section class="acciones-carousel">
        <h2>Acciones rápidas</h2>

        <div class="scene">

            <div class="left-zone">
                <ul class="list">

                    <li class="item">
                        <input type="radio" id="cita1" name="acciones" checked>
                        <label for="cita1">Nueva cita</label>

                        <div class="content">
                            <div class="content-full">
                                <h1>Nueva cita</h1>
                                <p>Reserva una nueva cita de forma rápida y sencilla.</p>
                                <button class="btn" onclick="location.href='index.php?pagina=nuevaCita'">Ir</button>
                            </div>
                        </div>
                    </li>

                    <li class="item">
                        <input type="radio" id="cita2" name="acciones">
                        <label for="cita2">Ver citas</label>

                        <div class="content">
                            <div class="content-full">
                                <h1>Mis citas</h1>
                                <p>Consulta todas tus citas programadas.</p>
                                <button class="btn" onclick="location.href='index.php?pagina=misCitas'">Ver</button>
                            </div>
                        </div>
                    </li>

                    <li class="item">
                        <input type="radio" id="cita3" name="acciones">
                        <label for="cita3">Modificar</label>

                        <div class="content">
                            <div class="content-full">
                                <h1>Modificar cita</h1>
                                <p>Edita la fecha o detalles de tus citas.</p>
                                <button class="btn" onclick="location.href='index.php?pagina=misCitas'">Ver</button>
                            </div>
                        </div>
                    </li>

                    <li class="item">
                        <input type="radio" id="cita4" name="acciones">
                        <label for="cita4">Cancelar</label>

                        <div class="content">
                            <div class="content-full">
                                <h1>Cancelar cita</h1>
                                <p>Elimina una cita si ya no la necesitas.</p>
                                <button class="btn" onclick="location.href='index.php?pagina=misCitas'">Ver</button>
                            </div>
                        </div>
                    </li>

                </ul>
            </div>

        </div>
    </section>
    
<footer class="footer"> 
    <p>Gestor de Citas © 2026</p> 
    <a href="https://github.com/Rebecarebeca2005/Gestor-citas.git" target="_blank"> Ver en GitHub </a>
 </footer>
</main>
</body>
</html>

<script>
function toggleMenu() {
    document.getElementById("sidebar").classList.toggle("active");
}
</script>