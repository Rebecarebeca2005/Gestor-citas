<?php
$stats = $stats ?? [];
?>

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
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/carga.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    
</head>
<body>
<div id="popup" class="popup hidden">
    <span id="popup-text"></span>
    <span id="popup-close">✖</span>
</div>
<?php
$nombre = $_SESSION['usuario']['nombre'] ?? 'Usuario';
?>

<div class="sidebar" id="sidebar">
<div class="close-sidebar" onclick="toggleMenu()">✖</div>
    <div class="sidebar-header">
        <h3>Hola  <?= htmlspecialchars($nombre) ?></h3>
    </div>

    <nav class="sidebar-menu">
        <a href="index.php?pagina=centroControl">Inicio</a>
        <a href="index.php?pagina=misCitas">Mis citas</a>
        <a href="index.php?pagina=calendarioAñadir">Nueva cita</a>
        <a href="index.php?pagina=calendarioModificar">Editar cita</a>
        <a href="index.php?pagina=perfil">Perfil</a> 
        <a href="index.php?pagina=login">Cerrar sesión</a> 
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

    <p>
    <?php if ($stats['proxima']): ?>
        
        <?= $stats['proxima']['fecha'] ?> <?= substr($stats['proxima']['hora'], 0, 5) ?>
   <?php else: ?>
    Ninguna
<?php endif; ?>
</p>
</div>

        <div class="card">
        <h3>Esta semana</h3>
        <p><?= $stats['semana'] ?> citas programadas</p>
    </div>

        <div class="card">
        <h3>Total mes</h3>
        <p><?= $stats['mes'] ?> citas</p>
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
                                <a href="index.php?pagina=calendarioAñadir" class="btn">Ir</a>
                        </div>
                    </li>

                    <li class="item">
                        <input type="radio" id="cita2" name="acciones">
                        <label for="cita2">Ver citas</label>

                        <div class="content">
                            <div class="content-full">
                                <h1>Mis citas</h1>
                                <p>Consulta todas tus citas programadas.</p>
                                <a href="index.php?pagina=misCitas" class="btn">Ver</a>
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
                                <a href="index.php?pagina=calendarioModificar" class="btn">Ver</a>
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
                                <a href="index.php?pagina=calendarioEliminar" class="btn">Ver</a>
                            </div>
                        </div>
                    </li>

                </ul>
            </div>

        </div>
    </section>

</main>

<footer class="footer"> 
    <p>Gestor de Citas © 2026</p> 
    <a href="https://github.com/Rebecarebeca2005/Gestor-citas.git" target="_blank">
        Ver en GitHub
    </a>
    <a href="index.php?pagina=privacidad">
    Política de privacidad
    </a>

    <a href="index.php?pagina=cookies">
        Política de cookies
    </a>

    <a href="index.php?pagina=legal">
        Aviso legal
    </a>
</footer>
<div id="loader" class="loader hidden">
    <div class="spinner"></div>
    <p>Cargando...</p>
</div>
<script>
function toggleMenu() {
    document.getElementById("sidebar").classList.toggle("active");
}
</script>
<script src="assets/js/carga.js"></script>
</body>
</html>


