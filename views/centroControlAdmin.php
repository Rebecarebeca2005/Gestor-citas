<?php
$nombre = $_SESSION['usuario']['nombre'] ?? 'Admin';
?>

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
        content="Gestiona usuarios, citas y estadísticas de forma rápida y sencilla desde el panel de administración.">

    <meta
        name="keywords"
        content="administración citas, gestión usuarios, estadísticas citas, panel administrador">

    <meta name="author" content="Rebeca">

    <meta name="robots" content="index, follow">

    <meta name="theme-color" content="#C26A4A">

    <!-- ===== FAVICON ===== -->
    <link
        rel="shortcut icon"
        href="assets/img/30-dias.png"
        type="image/x-icon">

    <!-- ===== HOJAS DE ESTILO ===== -->
    <link rel="stylesheet" href="assets/css/centroControl.css">
    <link rel="stylesheet" href="assets/css/carga.css">
    <link rel="stylesheet" href="assets/css/footer.css">

    <!-- ===== JQUERY ===== -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>

    <!-- ===== MENÚ LATERAL ===== -->
    <div class="sidebar" id="sidebar">

        <div
            class="close-sidebar"
            onclick="toggleMenu()">

            ✖

        </div>

        <div class="sidebar-header">

            <h3>
                Admin:
                <?= htmlspecialchars($nombre) ?>
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

    </div>

    <!-- ===== CABECERA ===== -->
     <header class="topbar">

        <img
            src="assets/img/hamburguesa.png"
            class="menu-icon"
            alt="Abrir menú"
            onclick="toggleMenu()">

    </header>

    <!-- ===== CONTENIDO PRINCIPAL ===== -->
    <main class="contenido">

        <!-- ===== SALUDO ===== -->
        <section class="saludo">

            <h1>
                Bienvenido,
                <?= htmlspecialchars($nombre) ?>
            </h1>

            <p>
                Control total del sistema de citas
            </p>

        </section>

        <!-- ===== MÉTRICAS ===== -->
        <section class="grid-resumen">

            <div class="card">

                <h3>
                    Usuarios
                </h3>

                <p>
                    <?= $statsMes['usuarios'] ?? 0 ?>
                </p>

            </div>

            <div class="card">

                <h3>
                    Citas hoy
                </h3>

                <p>
                    <?= $statsHoy['activas'] ?? 0 ?>
                </p>

            </div>

            <div class="card">

                <h3>
                    Citas mes
                </h3>

                <p>
                    <?= $statsMes['citas'] ?? 0 ?>
                </p>

            </div>

            <div class="card">

                <h3>
                    Canceladas
                </h3>

                <p>
                    <?= $statsMes['canceladas'] ?? 0 ?>
                </p>

            </div>

        </section>

        <!-- ===== ACCIONES RÁPIDAS ===== -->
        <section class="acciones-carousel">

            <h2>
                Acciones rápidas
            </h2>

            <div class="scene">

                <div class="left-zone">

                    <ul class="list">

                        <li class="item">

                            <input
                                type="radio"
                                id="a1"
                                name="acciones"
                                checked>

                            <label for="a1">
                                Crear usuario
                            </label>

                            <div class="content">

                                <div class="content-full">

                                    <h1>
                                        Nuevo usuario
                                    </h1>

                                    <p>
                                        Crear cliente o trabajadora.
                                    </p>

                                    <a
                                        href="index.php?pagina=crearUsuarioAdmin"
                                        class="btn">

                                        Ir

                                    </a>

                                </div>

                            </div>

                        </li>

                        <li class="item">

                            <input
                                type="radio"
                                id="a2"
                                name="acciones">

                            <label for="a2">
                                Ver citas
                            </label>

                            <div class="content">

                                <div class="content-full">

                                    <h1>
                                        Gestión de citas
                                    </h1>

                                    <p>
                                        Ver y editar todas las citas del sistema.
                                    </p>

                                    <a
                                        href="index.php?pagina=misCitasAdmin"
                                        class="btn">

                                        Ir

                                    </a>

                                </div>

                            </div>

                        </li>


                                            <li class="item">

                            <input
                                type="radio"
                                id="a4"
                                name="acciones">

                            <label for="a4">
                                Estadísticas
                            </label>

                            <div class="content">

                                <div class="content-full">

                                    <h1>
                                        Estadísticas
                                    </h1>

                                    <p>
                                        Análisis del sistema de citas.
                                    </p>

                                    <a
                                        href="index.php?pagina=estadisticas"
                                        class="btn">

                                        Ir

                                    </a>

                                </div>

                            </div>

                        </li>

                    </ul>

                </div>

            </div>

        </section>

        <!-- ===== ACTIVIDAD RECIENTE ===== -->
        <section
            class="card actividad-card"
            style="margin-top:40px">

            <div class="actividad-header">

                <h2>
                    Actividad reciente
                </h2>

                <span>
                    Últimos movimientos del sistema:
                </span>

            </div>

            <div class="actividad-lista">

                <?php foreach(($actividadReciente ?? []) as $item): ?>

                    <?php
                        $cancelada =
                            ($item['estado'] === 'CANCELADA');
                    ?>

                    <div class="actividad-item">

                        <div class="
                            actividad-dot
                            <?= $cancelada ? 'cancelada' : 'activa' ?>
                        "></div>

                        <div class="actividad-content">

                            <p>

                                <?php if($cancelada): ?>

                                    <strong>
                                        <?= $item['nombre'] ?>
                                    </strong>

                                    canceló una cita

                                <?php else: ?>

                                    <strong>
                                        <?= $item['nombre'] ?>
                                    </strong>

                                    creó una cita

                                <?php endif; ?>

                            </p>

                            <small>

                                <?= date(
                                    'd/m/Y • H:i',
                                    strtotime(
                                        $item['fecha_creacion']
                                    )
                                ) ?>

                            </small>

                        </div>

                    </div>

                <?php endforeach; ?>

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