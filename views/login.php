<!DOCTYPE html>
<html lang="es">

<head>

    <!-- ===== METADATOS SEO ===== -->
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Gestor de Citas | Organiza tus reservas fácilmente
    </title>

    <meta
        name="description"
        content="Accede a tu cuenta para gestionar citas, reservas y horarios de forma rápida y sencilla.">

    <meta
        name="keywords"
        content="iniciar sesión, acceso usuarios, gestor de citas, reservas online">

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
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="assets/css/carga.css">
    <link rel="stylesheet" href="assets/css/header.css">

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
    <header>

        <nav>

            <div class="izq">

                <a href="?pagina=home">

                    <img
                        src="assets/img/Logo-Nexo.png"
                        alt="Logo Nexo">

                </a>

            </div>

            <div class="menu">

                <a href="?pagina=sobreNosotros">
                    Sobre nosotros
                </a>

                <a href="?pagina=register">
                    Regístrate
                </a>

                <a href="?pagina=login">
                    Iniciar sesión
                </a>

            </div>

        </nav>

    </header>

    <!-- ===== CONTENIDO PRINCIPAL ===== -->
    <main>

        <!-- ===== FORMULARIO LOGIN ===== -->
        <section>

            <div class="contenedor">

                <div class="envoltorio">

                    <form
                        class="formulario"
                        method="post"
                        action="?pagina=login_post">

                        <fieldset class="seccion activo">

                            <h1>
                                Iniciar sesión
                            </h1>

                            <input
                                type="email"
                                name="email"
                                placeholder="Correo electrónico"
                                required>

                            <div class="contenedor-password">

                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    placeholder="Contraseña"
                                    required>

                                <img
                                    src="assets/img/ojo-abierto.png"
                                    alt="Mostrar contraseña"
                                    class="mostrar-password"
                                    data-target="password">
                                </div>

                            <button
                                class="btn-enviar"
                                type="submit">

                                Entrar

                            </button>

                        </fieldset>

                    </form>

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

    <!-- ===== LOADER ===== -->
    <div id="loader" class="loader hidden">

        <div class="spinner"></div>

        <p>
            Cargando...
        </p>

    </div>

    <!-- ===== SCRIPT LOGIN ===== -->
    <script src="assets/js/login.js"></script>

    <!-- ===== SCRIPT DE CARGA ===== -->
    <script src="assets/js/carga.js"></script>

</body>

</html>