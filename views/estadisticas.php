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
    <link rel="stylesheet" href="assets/css/calendario.css">
    <link rel="stylesheet" href="assets/css/carga.css">
    <link rel="stylesheet" href="assets/css/estadisticas.css">
    <link rel="stylesheet" href="assets/css/footer.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    
</head>

<body>
    <?php
    $stats = $stats ?? [];

$graficoMeses = $graficoMeses ?? [];

$serviciosTop = $serviciosTop ?? [];

$horasTop = $horasTop ?? [];

$usuariosTop = $usuariosTop ?? [];
?>

<div id="popup" class="popup hidden">
    <span id="popup-text"></span>
    <span id="popup-close">✖</span>
</div>
<!-- =========================
     TOPBAR (MENU HAMBURGUESA)
========================= -->
<header class="topbar">
    <img src="assets/img/hamburguesa.png" class="menu-icon" onclick="toggleMenu()">
</header>

<!-- =========================
     SIDEBAR (MENU OCULTO)
========================= -->
<aside class="sidebar" id="sidebar">

    <div class="close-sidebar" onclick="toggleMenu()">✖</div>

    <div class="sidebar-header">
        <h3>Menú</h3>
    </div>

    <nav class="sidebar-menu">
        <a href="index.php?pagina=centroControlAdmin">Inicio</a>
        <a href="index.php?pagina=crearUsuarioAdmin">Usuarios</a>
        <a href="index.php?pagina=misCitasAdmin">Citas</a>
        <a href="index.php?pagina=estadisticas">Estadísticas</a>
        <a href="index.php?pagina=logout">Cerrar sesión</a>
    </nav>

</aside>

<!-- =========================
        ESTADÍSTICAS
========================= -->
<section class="stats-page">

    <!-- HEADER -->
    <div class="stats-header">

        <div>
            <h1>Estadísticas</h1>
            <p>
                Resumen general del sistema y actividad de citas.
            </p>
        </div>

    </div>

    <!-- KPIs -->
    <div class="stats-cards">

        <div class="stats-card">
            <span class="stats-icon">
                <img src="assets/img/usuario-activo.png" alt="usuario-activo">
            </span>

            <h2><?= $stats['usuarios'] ?></h2>

            <p>Usuarios registrados</p>

            <small>+<?= $stats['nuevosUsuarios'] ?> este mes</small>
        </div>

        <div class="stats-card">
            <span class="stats-icon">
                <img src="assets/img/calendario.png" alt="calendario">
            </span>

            <h2><?= $stats['citas'] ?></h2>

            <p>Citas totales</p>

            <small>+<?= $stats['citasSemana'] ?> esta semana</small>
        </div>

        <div class="stats-card">
            <span class="stats-icon">
                <img src="assets/img/boton-de-verificacion.png" alt="tic-verificacion">
            </span>

            <h2><?= $stats['activas'] ?></h2>

            <p>Citas activas</p>

            <small> <?= $stats['porcentaje_activas'] ?>% completadas</small>
        </div>

        <div class="stats-card">
            <span class="stats-icon">
                <img src="assets/img/cerrar.png" alt="cancelar">
            </span>

            <h2><?= $stats['canceladas'] ?></h2>

            <p>Citas canceladas</p>

            <small><?= $stats['porcentaje_canceladas'] ?>% cancelaciones</small>
        </div>

    </div>

    <!-- GRID -->
    <div class="stats-grid">

        <!-- GRÁFICO GRANDE -->
        <div class="stats-box large">

            <div class="stats-box-header">
                <h3>Citas por mes</h3>
            </div>

            <div class="fake-chart">

<?php

$maximo = 0;

foreach($graficoMeses as $m) {

    if ($m['total'] > $maximo) {
        $maximo = $m['total'];
    }
}

?>

<?php foreach($graficoMeses as $mes): ?>

    <?php

    $altura = 0;

    if ($maximo > 0) {

        $altura =
            ($mes['total'] / $maximo) * 220;
    }

    ?>

    <div class="chart-column">

        <div
            class="bar"
            style="
                height: <?= $altura ?>px;
            "
            title="<?= $mes['total'] ?> citas"
        ></div>

        <span>
            <?=
                [
                    1=>"Ene",
                    2=>"Feb",
                    3=>"Mar",
                    4=>"Abr",
                    5=>"May",
                    6=>"Jun",
                    7=>"Jul",
                    8=>"Ago",
                    9=>"Sep",
                    10=>"Oct",
                    11=>"Nov",
                    12=>"Dic"
                ][$mes['mes']]
            ?>
        </span>

    </div>

<?php endforeach; ?>

</div>

        </div>

        <!-- SERVICIOS -->
        <div class="stats-box">

            <div class="stats-box-header">
                <h3>Servicios más reservados</h3>
            </div>

            <ul class="stats-list">

                 <?php foreach($serviciosTop as $servicio): ?>

        <li>

            <span>
                <?= $servicio['nombre'] ?>
            </span>

            <strong>
                <?= $servicio['total'] ?>
            </strong>

        </li>

    <?php endforeach; ?>

            </ul>

        </div>

        <!-- CANCELACIONES -->
        <div class="stats-box">

            <div class="stats-box-header">
                <h3>Estado de citas</h3>
            </div>

            <div class="progress-group">

                <div class="progress-item">

    <span>
        Activas
        (<?= $stats['porcentaje_activas'] ?>%)
    </span>

    <div class="progress-bar">

        <div
            class="progress-value active-progress"
            style="width: <?= $stats['porcentaje_activas'] ?>%;"
        ></div>

    </div>

</div>

<div class="progress-item">

    <span>
        Canceladas
        (<?= $stats['porcentaje_canceladas'] ?>%)
    </span>

    <div class="progress-bar">

        <div
            class="progress-value cancel-progress"
            style="width: <?= $stats['porcentaje_canceladas'] ?>%;"
        ></div>

    </div>

</div>

            </div>

        </div>

        <!-- HORAS -->
        <div class="stats-box">

            <div class="stats-box-header">
                <h3>Horas más reservadas</h3>
            </div>

            <ul class="stats-list">
    <?php foreach($horasTop as $hora): ?>
                <li>
                     <span>
                <?= substr($hora['hora'], 0, 5) ?>
            </span>

            <strong>
                <?= $hora['total'] ?> citas
            </strong>
                </li>

     <?php endforeach; ?>
            </ul>

        </div>

        <!-- USUARIOS -->
        <div class="stats-box">

            <div class="stats-box-header">
                <h3>Usuarios más activos</h3>
            </div>

           <ul class="stats-list">

    <?php foreach($usuariosTop as $usuario): ?>

        <li>

            <span>
                <?= $usuario['nombre'] ?>
            </span>

            <strong>
                <?= $usuario['total'] ?> citas
            </strong>

        </li>

    <?php endforeach; ?>

        </div>

    </div>

</section>

<!-- =========================
        FOOTER
========================= -->
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


<!-- =========================
        JS MENU
========================= -->
<script>
function toggleMenu() {
    document.getElementById("sidebar").classList.toggle("active");
    document.body.classList.toggle("menu-open");
}
</script>
 <div id="loader" class="loader hidden">
    <div class="spinner"></div>
    <p>Cargando...</p>
</div>
<script src="assets/js/carga.js"></script>

</body>
</html>