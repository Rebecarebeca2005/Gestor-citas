<!DOCTYPE html>
<html lang="es">

<head>

    <!-- ===== METADATOS SEO ===== -->
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Gestión de Citas | Panel de Administración
    </title>

    <meta
        name="description"
        content="Visualiza, modifica y elimina citas desde el panel de administración del gestor de reservas.">

    <meta
        name="keywords"
        content="administración citas, modificar citas, eliminar citas, reservas online">

    <meta
        name="author"
        content="Rebeca">

    <meta
        name="robots"
        content="index, follow">

    <meta
        name="theme-color"
        content="#C26A4A">

    <!-- ===== FAVICON ===== -->
    <link
        rel="shortcut icon"
        href="assets/img/30-dias.png"
        type="image/x-icon">

    <!-- ===== HOJAS DE ESTILO ===== -->
    <link rel="stylesheet" href="assets/css/calendario.css">
    <link rel="stylesheet" href="assets/css/misCitas.css">
    <link rel="stylesheet" href="assets/css/carga.css">
    <link rel="stylesheet" href="assets/css/edicionAdmin.css">
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

    <!-- ===== TOPBAR ===== -->
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

      <a href="index.php?pagina=perfil" class="topbar-perfil" title="Mi perfil">
        <img src="assets/img/usuario.png" class="perfil-icon" alt="Perfil">
    </a>

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

        <!-- ===== HERO CALENDARIO ===== -->
        <section class="hero-calendario">

            <div class="hero-info">

                <span class="hero-tag">
                    Gestión de citas
                </span>

                <h1>
                    Modificación de citas
                </h1>

                <p>
                    Selecciona un día del calendario para
                    modificar o eliminar una reserva fácilmente.
                </p>

            </div>

        </section>

        <!-- ===== CALENDARIO ===== -->
        <section class="calendario">

            <div class="cal-header">

                <div class="cal-nav">

                    <button id="prevMes">‹</button>

                    <h2 id="mesActual"></h2>

                    <button id="nextMes">›</button>

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
                    Citas del día
                </h2>

                <ul class="lista-citas"></ul>

            </div>

        </div>

        <!-- ===== MODAL EDICIÓN ADMIN ===== -->
        <div id="modalEditarAdmin" class="modal hidden">

            <div class="modal-content">

                <span
                    class="cerrar-modal"
                    onclick="cerrarEditarAdmin()">

                    ✖

                </span>

                <h2>
                    Editar cita
                </h2>

                <form id="formEditarCitaAdmin">

                    <input
                        type="hidden"
                        name="id_cita">

                    <label for="fechaAdmin">
                        Fecha
                    </label>

                    <input
                        type="date"
                        name="fecha"
                        id="fechaAdmin">

                    <label for="horaAdmin">
                        Hora
                    </label>

                    <select
                        name="id_disponibilidad"
                        id="horaAdmin">

                        <option value="">
                            Selecciona una hora
                        </option>

                    </select>

                    <label for="estadoAdmin">
                        Estado
                    </label>

                    <select
                        name="estado"
                        id="estadoAdmin">

                        <option value="ACTIVA">
                            ACTIVA
                        </option>

                        <option value="CANCELADA">
                            CANCELADA
                        </option>

                    </select>

                            <label for="descripcionAdmin">
                        Descripción
                    </label>

                    <textarea
                        name="descripcion"
                        id="descripcionAdmin">
                    </textarea>

                    <button
                        type="button"
                        id="btnGuardarEdicion"
                        class="btn-guardar">

                        Guardar cambios

                    </button>

                </form>

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
                        type="button"
                        id="btnConfirmarEliminar"
                        class="btn-eliminar">

                        Sí, eliminar

                    </button>

                    <button
                        type="button"
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
                "menu-open");
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

    <!-- ===== SCRIPT CALENDARIO ADMIN ===== -->
    <script src="assets/js/calendarioVerCitasAdmin.js"></script>

    <!-- ===== SCRIPT DE CARGA ===== -->
    <script src="assets/js/carga.js"></script>

</body>

</html>