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
        content="Gestiona usuarios, citas y horarios de forma rápida y sencilla desde el panel de administración.">

    <meta
        name="keywords"
        content="crear usuario, gestión usuarios, administración citas, panel administrador">

    <meta name="author" content="Rebeca">

    <meta name="robots" content="index, follow">

    <meta name="theme-color" content="#C26A4A">

    <!-- ===== FAVICON ===== -->
    <link
        rel="shortcut icon"
        href="assets/img/30-dias.png"
        type="image/x-icon">

    <!-- ===== HOJAS DE ESTILO ===== -->
    <link rel="stylesheet" href="assets/css/carga.css">
    <link rel="stylesheet" href="assets/css/crearUserAdmin.css">
    <link rel="stylesheet" href="assets/css/calendario.css">
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

        <!-- ===== CREAR USUARIO ===== -->
        <section class="crear-user-page">

            <div class="crear-user-card">

                <div class="crear-user-header">

                    <h1>
                        Crear usuario
                    </h1>

                    <p>
                        Añade clientes o trabajadores al sistema.
                    </p>

                </div>

                <!-- ===== FORMULARIO ===== -->
                <form
                    id="formCrearUsuario"
                    method="POST"
                    class="crear-user-form"
                    novalidate>

                    <div class="crear-user-field">

                        <label>
                            Nombre
                        </label>

                        <input
                            type="text"
                            name="nombre"
                            >

                    </div>

                    <div class="crear-user-field">

                        <label>
                            Apellidos
                        </label>

                        <input
                            type="text"
                            name="apellidos"
                            >

                    </div>

                    <div class="crear-user-field">

                        <label>
                            Correo electrónico
                        </label>

                        <input
                            type="email"
                            name="correo"
                            >

                    </div>

                    <div class="crear-user-field">

                        <label>
                            Teléfono
                        </label>

                        <input
                            type="text"
                            name="telefono"
                            >

                    </div>

                    <div class="crear-user-field">
                        <label>
                            Contraseña
                        </label>
                        <div style="position: relative;">
                            <input
                                type="password"
                                name="password"
                                id="password">
                            <img
                                src="assets/img/ojo-abierto.png"
                                class="mostrar-password"
                                data-target="password"
                                style="position:absolute; right:10px; top:50%; transform:translateY(-50%); width:22px; cursor:pointer;">
                        </div>
                    </div>

                        <div class="crear-user-field">

                        <label>
                            Rol
                        </label>

                        <select name="rol">

                            <option value="CLIENTE">
                                CLIENTE
                            </option>

                            <option value="ADMIN">
                                TRABAJADOR
                            </option>

                        </select>

                    </div>

                    <button
                        type="button"
                        id="btnCrearUsuario"
                        class="crear-user-btn">

                        Crear usuario

                    </button>

                </form>

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

    <!-- ===== SCRIPT CREAR USUARIO ===== -->
    <script src="assets/js/crearUserAdmin.js"></script>

    <!-- ===== SCRIPT DE CARGA ===== -->
    <script src="assets/js/carga.js"></script>

</body>

</html>        