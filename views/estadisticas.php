<!DOCTYPE html>
<html lang="es">

<head>

    <!-- ===== METADATOS SEO ===== -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Gestor de Citas | Organiza tus reservas fácilmente
    </title>

    <meta
        name="description"
        content="Consulta estadísticas, actividad y métricas del sistema de gestión de citas.">

    <meta
        name="keywords"
        content="estadísticas citas, métricas reservas, administración citas, informes sistema">

    <meta name="author" content="Rebeca">

    <meta name="robots" content="index, follow">

    <meta name="theme-color" content="#C26A4A">

    <!-- ===== FAVICON ===== -->
    <link
        rel="shortcut icon"
        href="assets/img/30-dias.png"
        type="image/x-icon">

    <!-- ===== HOJAS DE ESTILO ===== -->
    <link rel="stylesheet" href="assets/css/calendario.css">
    <link rel="stylesheet" href="assets/css/carga.css">
    <link rel="stylesheet" href="assets/css/estadisticas.css">
    <link rel="stylesheet" href="assets/css/footer.css">

    <!-- ===== JQUERY ===== -->
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

    <!-- ===== MENSAJES EMERGENTES ===== -->
    <div id="popup" class="popup hidden">

        <span id="popup-text"></span>

        <span id="popup-close">
            ✖
        </span>

    </div>

    <!-- ===== CABECERA ===== -->
     <header class="topbar">

        <img
            src="assets/img/hamburguesa.png"
            class="menu-icon"
            alt="Abrir menú"
            onclick="toggleMenu()">

            <!-- ===== VOLVER ATRÁS ===== -->
 <nav class="volver-atras">
        ← Volver atrás
    </nav>

    </header>

    <!-- ===== MENÚ LATERAL ===== -->
    <aside class="sidebar" id="sidebar">

        <div
            class="close-sidebar"
            onclick="toggleMenu()">

            ✖

        </div>

        <div class="sidebar-header">

            <h3>
                Menú
            </h3>

        </div>

        <nav class="sidebar-menu">

            <a href="index.php?pagina=centroControlAdmin">
                Inicio
            </a>

            <a href="index.php?pagina=crearUsuarioAdmin">
                Usuarios
            </a>

            <a href="index.php?pagina=misCitasAdmin">
                Citas
            </a>

            <a href="index.php?pagina=estadisticas">
                Estadísticas
            </a>

            <a href="index.php?pagina=logout">
                Cerrar sesión
            </a>

        </nav>

    </aside>

    <!-- ===== CONTENIDO PRINCIPAL ===== -->
    <main>

        <!-- ===== ESTADÍSTICAS ===== -->
        <section class="stats-page">

            <!-- ===== CABECERA ESTADÍSTICAS ===== -->
            <div class="stats-header">

                <div>

                    <h1>
                        Estadísticas
                    </h1>

                    <p>
                        Resumen general del sistema y actividad de citas.
                    </p>

                </div>

            </div>

            <!-- ===== INDICADORES PRINCIPALES ===== -->
            <div class="stats-cards">

                <div class="stats-card">

                    <span class="stats-icon">

                        <img
                            src="assets/img/usuario-activo.png"
                            alt="Usuarios registrados">

                    </span>

                    <h2>
                        <?= $stats['usuarios'] ?>
                    </h2>

                    <p>
                        Usuarios registrados
                    </p>

                    <small>
                        +<?= $stats['nuevosUsuarios'] ?>
                        este mes
                    </small>

                </div>

                <div class="stats-card">

                    <span class="stats-icon">

                        <img
                            src="assets/img/calendario.png"
                            alt="Citas registradas">

                    </span>

                    <h2>
                        <?= $stats['citas'] ?>
                    </h2>

                    <p>
                        Citas totales
                    </p>

                    <small>
                        +<?= $stats['citasSemana'] ?>
                        esta semana
                    </small>

                </div>

                <div class="stats-card">

                    <span class="stats-icon">

                        <img
                            src="assets/img/boton-de-verificacion.png"
                            alt="Citas activas">

                    </span>

                    <h2>
                        <?= $stats['activas'] ?>
                    </h2>

                    <p>
                        Citas activas
                    </p>

                    <small>
                        <?= $stats['porcentaje_activas'] ?>%
                        completadas
                    </small>

                </div>

                <div class="stats-card">

                    <span class="stats-icon">

                        <img
                            src="assets/img/cerrar.png"
                            alt="Citas canceladas">

                    </span>

                    <h2>
                        <?= $stats['canceladas'] ?>
                    </h2>

                    <p>
                        Citas canceladas
                    </p>

                    <small>
                        <?= $stats['porcentaje_canceladas'] ?>%
                        cancelaciones
                    </small>

                </div>

            </div>

            <!-- ===== PANEL DE ESTADÍSTICAS ===== -->
            <div class="stats-grid">

                <!-- ===== GRÁFICO DE CITAS POR MES ===== -->
                <div class="stats-box large">

                    <div class="stats-box-header">

                        <h3>
                            Citas por mes
                        </h3>

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
                                style="height: <?= $altura ?>px;"
                                title="<?= $mes['total'] ?> citas">

                            </div>

                            <span>
                                 <?=
                                    [
                                        1 => "Ene",
                                        2 => "Feb",
                                        3 => "Mar",
                                        4 => "Abr",
                                        5 => "May",
                                        6 => "Jun",
                                        7 => "Jul",
                                        8 => "Ago",
                                        9 => "Sep",
                                        10 => "Oct",
                                        11 => "Nov",
                                        12 => "Dic"
                                    ][$mes['mes']]
                                ?>
                            </span>

                        </div>

<?php endforeach; ?>

                    </div>

                </div>

                <!-- ===== SERVICIOS MÁS RESERVADOS ===== -->
                <div class="stats-box">

                    <div class="stats-box-header">

                        <h3>
                            Servicios más reservados
                        </h3>

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

                <!-- ===== ESTADO DE CITAS ===== -->
                <div class="stats-box">

                    <div class="stats-box-header">

                        <h3>
                            Estado de citas
                        </h3>

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
                                    style="width: <?= $stats['porcentaje_activas'] ?>%;">

                                </div>

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
                                    style="width: <?= $stats['porcentaje_canceladas'] ?>%;">

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- ===== HORAS MÁS RESERVADAS ===== -->
                <div class="stats-box">

                    <div class="stats-box-header">

                        <h3>
                            Horas más reservadas
                        </h3>

                    </div>

                    <ul class="stats-list">

                        <?php foreach($horasTop as $hora): ?>

                            <li>

                                <span>
                                    <?= substr($hora['hora'], 0, 5) ?>
                                </span>

                                <strong>
                                    <?= $hora['total'] ?>
                                    citas
                                </strong>

                            </li>

                        <?php endforeach; ?>

                    </ul>

                </div>

                <!-- ===== USUARIOS MÁS ACTIVOS ===== -->
                <div class="stats-box">

                    <div class="stats-box-header">

                        <h3>
                            Usuarios más activos
                        </h3>

                    </div>

                    <ul class="stats-list">

                        <?php foreach($usuariosTop as $usuario): ?>

                            <li>

                                <span>
                                    <?= $usuario['nombre'] ?>
                                </span>

                                <strong>
                                    <?= $usuario['total'] ?>
                                    citas
                                </strong>

                            </li>

                        <?php endforeach; ?>

                    </ul>

                </div>

            </div>

        </section>

    </main>

    <!-- ===== FOOTER ===== -->
    <footer class="footer">

        <p>
            Gestor de Citas © 2026
        </p>

        <a
            href="https://github.com/Rebecarebeca2005/Gestor-citas.git"
            target="_blank"
            rel="noopener noreferrer">

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

    <!-- ===== SCRIPT MENÚ ===== -->
    <script>

        function toggleMenu() {

            document
                .getElementById("sidebar")
                .classList
                .toggle("active");

            document.body.classList.toggle(
                "menu-open"
            );
        }

    </script>

    <!-- ===== LOADER ===== -->
    <div id="loader" class="loader hidden">

        <div class="spinner"></div>

        <p>
            Cargando...
        </p>

    </div>

    <!-- ===== SCRIPT DE CARGA ===== -->
    <script src="assets/js/carga.js"></script>

</body>

</html>