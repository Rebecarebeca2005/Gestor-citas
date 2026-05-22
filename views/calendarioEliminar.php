<!DOCTYPE html>
<html lang="es">

<head>

    <!-- ===== METADATOS SEO ===== -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Mis Citas | Gestor de Citas
    </title>

    <meta
        name="description"
        content="Consulta, modifica y elimina tus citas de forma sencilla desde tu gestor de reservas online.">

    <meta
        name="keywords"
        content="mis citas, reservas online, gestión de citas, calendario de reservas">

    <meta name="author" content="Rebeca">

    <meta name="robots" content="index, follow">

    <meta name="theme-color" content="#C26A4A">

    <!-- ===== FAVICON ===== -->
    <link
        rel="shortcut icon"
        href="assets/img/30-dias.png"
        type="image/x-icon">

    <!-- ===== HOJAS DE ESTILO ===== -->
    <link rel="stylesheet" href="assets/css/eliminarcita.css">
    <link rel="stylesheet" href="assets/css/calendario.css">
    <link rel="stylesheet" href="assets/css/misCitas.css">
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
                    Eliminar cita
                </h1>

                <p>
                    Selecciona un día del calendario para
                    eliminar una reserva fácilmente.
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

                    <h2 id="mesActual"></h2>

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

        <!-- ===== MODAL CITAS DEL DÍA ===== -->
        <div id="modalCita" class="modal hidden">

            <div class="modal-content">

                <span
                    class="cerrar-modal"
                    onclick="cerrarCita()">

                    ✖

                </span>

                <h2>
                    Citas del día, ¿Qué cita deseas eliminar?
                </h2>

                <ul class="lista-citas"></ul>

            </div>

        </div>

        <!-- ===== CONFIRMAR ELIMINACIÓN ===== -->
        <div id="popupEliminar" class="modal hidden">

            <div class="modal-content confirm">

                <h3>
                    ¿Eliminar esta cita?
                </h3>

                <div class="confirm-buttons">

                    <button
                        id="btnConfirmarEliminar"
                        class="btn-eliminar">

                        Sí, eliminar

                    </button>

                    <button
                        id="btnCancelarEliminar"
                        class="btn-cancelar">

                        Cancelar

                    </button>

                </div>

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
    <script src="assets/js/calendarioEliminar.js"></script>

    <!-- ===== SCRIPT ELIMINAR CITA ===== -->
    <script src="assets/js/eliminarcita.js"></script>

</body>

</html>