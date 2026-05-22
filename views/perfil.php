<!DOCTYPE html>
<html lang="es">

<head>

    <!-- ===== METADATOS SEO ===== -->
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Perfil de Usuario | Gestor de Citas
    </title>

    <meta
        name="description"
        content="Consulta tu información personal y gestiona tu cuenta desde tu perfil de usuario.">

    <meta
        name="keywords"
        content="perfil usuario, cuenta usuario, gestión perfil, gestor de citas">

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
    <link rel="stylesheet" href="assets/css/perfil.css">
    <link rel="stylesheet" href="assets/css/centroControl.css">
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

$nombre =
    $_SESSION['usuario']['nombre']
    ?? 'Usuario';

$correo =
    $_SESSION['usuario']['email']
    ?? 'Email';

$tlfn =
    $_SESSION['usuario']['telefono']
    ?? 'Teléfono';

?>

    <!-- ===== MENÚ LATERAL ===== -->
    <div class="sidebar" id="sidebar">

        <div
            class="close-sidebar"
            onclick="toggleMenu()">

            ✖

        </div>

        <div class="sidebar-header">

            <h3>
                Hola
                <?= htmlspecialchars($nombre) ?>
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

    <!-- ===== CABECERA ===== -->
    <header class="topbar">

        <img
            src="assets/img/hamburguesa.png"
            class="menu-icon"
            alt="Abrir menú"
            onclick="toggleMenu()">

        <h2>
            Perfil del usuario
        </h2>

    </header>

    <!-- ===== CONTENIDO PRINCIPAL ===== -->
    <main class="contenido">

        <!-- ===== PERFIL ===== -->
        <section class="perfil-container">

            <h2>
                Mi perfil
            </h2>

            <div class="perfil-card">

                <p>

                    <strong>
                        Nombre:
                    </strong>

                    <?= $nombre ?>

                </p>

                <p>

                    <strong>
                        Correo electrónico:
                    </strong>

                    <?= $correo ?>

                </p>

                <p>

                    <strong>
                        Teléfono:
                    </strong>

                    <?= $tlfn ?>

                </p>

                <button
                    id="btnEliminar"
                    class="btn-eliminar">

                    Eliminar cuenta

                </button>

            </div>

        </section>

    </main>

    <!-- ===== CONFIRMAR ELIMINACIÓN ===== -->
    <div id="popupEliminar" class="hidden">

        <div class="modal-content confirm">

            <h3>
                ¿Seguro que quieres eliminar tu cuenta?
            </h3>

            <p>
                Se borrarán todos tus datos y citas.
            </p>

            <div class="confirm-buttons">

                <button
                    class="btn-eliminar"
                    onclick="eliminarPerfil(<?= $_SESSION['usuario']['id_usuario'] ?>)">

                    Sí, eliminar

                </button>

                <button
                    class="btn-cancelar"
                    onclick="cerrarPopup()">

                    Cancelar

                </button>

            </div>

        </div>

    </div>

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

    <!-- ===== LOADER ===== -->
    <div id="loader" class="loader hidden">

        <div class="spinner"></div>

        <p>
            Cargando...
        </p>

    </div>

    <!-- ===== SCRIPT DE CARGA ===== -->
    <script src="assets/js/carga.js"></script>

    <!-- ===== SCRIPT PERFIL ===== -->
    <script>

        function toggleMenu() {

            document
                .getElementById("sidebar")
                .classList
                .toggle("active");
        }

        const popup =
            document.getElementById(
                "popupEliminar"
            );

        document
            .getElementById(
                "btnEliminar"
            )
            .onclick = () => {

                popup.classList.remove(
                    "hidden"
                );
            };

        function cerrarPopup() {

            popup.classList.add(
                "hidden"
            );
        }

        function deleteCookie(nombre) {

            document.cookie =
                nombre +
                "=;expires=Thu, 01 Jan 1970 00:00:00 UTC;path=/";
        }

        function showPopup(msg) {

            const popup =
                document.getElementById(
                    "popup"
                );

            const text =
                document.getElementById(
                    "popup-text"
                );

            text.textContent = msg;

            popup.classList.remove(
                "hidden"
            );

            setTimeout(() => {

                popup.classList.add(
                    "hidden"
                );

            }, 2000);
        }

        function eliminarPerfil(idUsuario) {

            fetch(
                "index.php?pagina=eliminarPerfilAjax",
                {

                    method: "POST",

                    headers: {
                        "Content-Type":
                            "application/x-www-form-urlencoded"
                    },

                    body:
                        "id=" + idUsuario
                }
            )

            .then(res => res.json())

            .then(data => {

                if (data.ok) {

                    // Elimina la cookie de aceptación
                    deleteCookie(
                        "cookies_aceptadas"
                    );

                    showPopup(
                        "Cuenta eliminada correctamente"
                    );

                    setTimeout(() => {

                        window.location.href =
                            "index.php?pagina=login";

                    }, 2000);

                } else {

                    showPopup(
                        "No se ha podido eliminar la cuenta"
                    );
                }

            })

            .catch(err => {

                console.error(err);

                showPopup(
                    "Error eliminando cuenta"
                );

            });
        }

    </script>

</body>

</html>