<?php
// Obtenemos los datos del controlador
$datos = $citaController->datosFormulario();
$servicios = $datos['servicios'];
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <!-- ===== METADATOS SEO ===== -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gestor de Citas | Organiza tus reservas fácilmente</title>

    <meta
        name="description"
        content="Gestiona tus citas, reservas y horarios de forma rápida y sencilla con nuestro gestor online. Organiza todo en un solo lugar.">

    <meta
        name="keywords"
        content="gestor de citas, reservas online, agenda digital, citas online, organizar citas">

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
    <link rel="stylesheet" href="assets/css/calendarioAnadir.css">
    <link rel="stylesheet" href="assets/css/carga.css">
    <link rel="stylesheet" href="assets/css/footer.css">

    <!-- ===== JQUERY ===== -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>

    <!-- ===== MENSAJES EMERGENTES ===== -->
    <div id="popup" class="popup hidden">

        <span id="popup-text"></span>

        <span id="popup-close">
            ✖
        </span>

    </div>

<?php
$nombre = $_SESSION['usuario']['nombre'] ?? 'Usuario';
?>

    <!-- ===== TOPBAR ===== -->
    <header class="topbar">

        <img
            src="assets/img/hamburguesa.png"
            class="menu-icon"
            alt="Abrir menú"
            onclick="toggleMenu()">

    </header>

    <!-- ===== MENÚ LATERAL ===== -->
    <div class="sidebar" id="sidebar">

        <div
            class="close-sidebar"
            onclick="toggleMenu()">

            ✖

        </div>

        <div class="sidebar-header">

            <h3>
                Hola <?= htmlspecialchars($nombre) ?>
            </h3>

        </div>

        <nav class="sidebar-menu">

            <a href="index.php?pagina=centroControl">
                Inicio
            </a>

            <a href="index.php?pagina=misCitas">
                Mis citas
            </a>

            <a href="index.php?pagina=calendarioAñadir">
                Nueva cita
            </a>

            <a href="index.php?pagina=calendarioModificar">
                Editar cita
            </a>

            <a href="index.php?pagina=calendarioEliminar">
                Eliminar cita
            </a>

            <a href="index.php?pagina=perfil">
                Perfil
            </a>

            <a href="index.php?pagina=login">
                Cerrar sesión
            </a>

        </nav>

    </div>

    <!-- ===== CONTENIDO PRINCIPAL ===== -->
    <main>

        <!-- ===== HERO CALENDARIO ===== -->
        <section class="hero-calendario">

            <div class="hero-info">

                <span class="hero-tag">
                    Gestión de citas
                </span>

                <h1>
                    Nueva cita
                </h1>

                <p>
                    Selecciona un día del calendario para
                    crear una nueva reserva fácilmente.
                </p>

            </div>

        </section>

        <!-- ===== CALENDARIO ===== -->
        <section class="calendario">

            <div class="cal-header">

                <div class="cal-nav">

                    <button id="prevMes">
                        ‹
                    </button>

                    <h2 id="mesActual">
                        Enero 2026
                    </h2>

                    <button id="nextMes">
                        ›
                    </button>

                </div>

                <button
                    class="btn-hoy"
                    id="btnHoy">

                    Hoy

                </button>

            </div>

            <div class="cal-wrapper">

                <!-- ===== DÍAS DE LA SEMANA ===== -->
                <div class="cal-weekdays">

                    <div>Lun</div>
                    <div>Mar</div>
                    <div>Mié</div>
                    <div>Jue</div>
                    <div>Vie</div>
                    <div>Sáb</div>
                    <div>Dom</div>

                </div>

                <!-- ===== CUADRÍCULA DEL CALENDARIO ===== -->
                <div
                    id="calGrid"
                    class="cal-grid">
                </div>

            </div>

        </section>

        <!-- ===== MODAL CREAR CITA ===== -->
        <div id="modalCita" class="modal hidden">

            <div class="modal-content">

                <span
                    class="cerrar-modal"
                    onclick="cerrarCita()">

                    ✖

                </span>

                <!-- ===== PASOS DEL FORMULARIO ===== -->
                <ul class="pasos">

                    <li class="activo">
                        Servicio
                    </li>

                    <li>
                        Datos
                    </li>

                    <li>
                        Disponibilidad
                    </li>

                </ul>

                <!-- ===== FORMULARIO DE CREACIÓN ===== -->
                <form
                    id="formCita"
                    class="formulario"
                    method="POST"
                    action="index.php?pagina=calendarioAñadir">

                    <!-- ===== PASO 1: SERVICIO ===== -->
                    <fieldset class="seccion activo">

                        <h3>
                            Elige servicio
                        </h3>

                        <select name="id_servicio" required>

                            <?php foreach ($servicios as $s): ?>

                                <option value="<?= $s['id_servicio'] ?>">

                                    <?= htmlspecialchars($s['nombre']) ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                        <button
                            type="button"
                            class="btn-siguiente">

                            Siguiente

                        </button>

                    </fieldset>

                    <!-- ===== PASO 2: DATOS DE LA CITA ===== -->
                    <fieldset class="seccion">

                        <h3>
                            Datos de la cita
                        </h3>

                        <textarea
                            name="descripcion"
                            placeholder="Descripción opcional de la cita">
                        </textarea>

                        <input
                            type="date"
                            name="fecha"
                            required>

                        <div class="botones-formulario">

                            <button
                                type="button"
                                class="btn-atras">

                                Atrás

                            </button>

                            <button
                                type="button"
                                class="btn-siguiente">

                                Siguiente

                            </button>

                        </div>

                    </fieldset>

                    <!-- ===== PASO 3: DISPONIBILIDAD ===== -->
                    <fieldset class="seccion">

                        <h3>
                            Disponibilidad
                        </h3>

                        <select
                            name="id_disponibilidad"
                            required>

                            <option value="">
                                Selecciona horario disponible
                            </option>

                            <?php

                            $fechaSeleccionada =
                                $_POST['fecha']
                                ??
                                date('Y-m-d');

                            $disponibilidad =
                                $citaController
                                    ->obtenerDisponibilidad(
                                        $fechaSeleccionada
                                    );

                            foreach ($disponibilidad as $d):
                            ?>

                                <option
                                    value="<?= $d['id_disponibilidad'] ?>">

                                    <?= substr($d['hora_inicio'], 0, 5) ?>
                                    -
                                    <?= substr($d['hora_fin'], 0, 5) ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                        <div class="botones-formulario">

                            <button
                                type="button"
                                class="btn-atras">

                                Atrás

                            </button>

                            <button
                                type="submit"
                                class="btn-enviar">

                                Crear cita

                            </button>

                        </div>

                    </fieldset>

                </form>

            </div>

        </div>

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

    <!-- ===== DATOS DEL CALENDARIO ===== -->
    <script>

        const diasConCitas =
            <?= json_encode($diasConCitas ?? []) ?>;

        const diasCanceladas =
            <?= json_encode($diasCanceladas ?? []) ?>;

    </script>

    <!-- ===== SCRIPT DE CARGA ===== -->
    <script src="assets/js/carga.js"></script>

    <!-- ===== SCRIPT CALENDARIO ===== -->
    <script src="assets/js/calendarioAnadir.js"></script>

    <!-- ===== SCRIPT CREAR CITA ===== -->
    <script src="assets/js/anadircita.js"></script>

</body>

</html>