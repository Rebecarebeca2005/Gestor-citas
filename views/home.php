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
        content="Gestiona tus citas, reservas y horarios de forma rápida y sencilla con nuestro gestor online. Organiza todo en un solo lugar.">

    <meta
        name="keywords"
        content="gestor de citas, reservas online, agenda digital, citas online, organizar citas">

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
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="assets/css/carga.css">
    <link rel="stylesheet" href="assets/css/header.css">

    <!-- ===== JQUERY ===== -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- ===== SCRIPT HOME ===== -->
    <script src="assets/js/index.js"></script>

</head>

<body>

    <!-- ===== CONTENIDO PRINCIPAL ===== -->
    <main>

        <!-- ===== CABECERA ===== -->
        <header>

            <nav class="navbar">

                <div class="izq">

                    <a href="?pagina=home">

                        <img
                            src="assets/img/Logo-Nexo.png"
                            alt="Logo Nexo"
                            class="logo">

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

        <!-- ===== HERO ===== -->
        <section class="hero">

            <article class="hero-contenido">

                <h2>
                    Organiza tus citas sin complicaciones
                </h2>

                <div class="hero-botones">

                    <button
                        class="boton1"
                        onclick="location.href='index.php?pagina=register'">

                        Crear cuenta

                    </button>

                    <button
                        class="boton2"
                        onclick="location.href='index.php?pagina=login'">

                        Iniciar sesión

                    </button>

                </div>

                <h3>

                    Gestiona reservas, controla horarios
                    y mantén todo bajo control desde un
                    solo lugar.

                </h3>

            </article>

        </section>

        <!-- ===== SLIDER ===== -->
        <section class="slider">

            <div class="slider-contenedor">

                <!-- ===== SLIDE 1 ===== -->
                <div class="slide activo">

                    <div class="slide-texto">

                        <h2>
                            Organiza tu
                            <span>agenda</span>
                        </h2>

                        <p>
                            Controla tus citas de forma rápida y sencilla
                            desde cualquier lugar.
                        </p>

                        <button
                            class="boton1"
                            onclick="location.href='index.php?pagina=register'">

                            Empezar ahora

                        </button>

                    </div>

                    <div class="slide-imagen">

                        <img
                            src="assets/img/Imagen1.png"
                            alt="Gestión de citas">

                    </div>

                </div>

                <!-- ===== SLIDE 2 ===== -->
                <div class="slide">

                    <div class="slide-texto">

                        <h2>
                            Recordatorios
                            <span>automáticos</span>
                        </h2>

                        <p>
                            Recibe avisos y no vuelvas a olvidar
                            una cita importante.
                        </p>

                        <button
                            class="boton2"
                            onclick="location.href='index.php?pagina=login'">

                            Ver más

                        </button>

                    </div>

                    <div class="slide-imagen">

                        <img
                            src="assets/img/Imagen2.png"
                            alt="Recordatorios">

                    </div>

                </div>

                 <!-- ===== SLIDE 3 ===== -->
                <div class="slide">

                    <div class="slide-texto">

                        <h2>
                            Gestión
                            <span>sencilla</span>
                        </h2>

                        <p>
                            Una interfaz clara pensada para que todo
                            sea rápido y sencillo.
                        </p>

                        <button
                            class="boton1"
                            onclick="location.href='index.php?pagina=register'">

                            Probar

                        </button>

                    </div>

                    <div class="slide-imagen">

                        <img
                            src="assets/img/Imagen3.png"
                            alt="Gestión sencilla">

                    </div>

                </div>

            </div>

            <!-- ===== INDICADORES DEL SLIDER ===== -->
            <div class="slider-indicadores">

                <span class="indicador activo"></span>

                <span class="indicador"></span>

                <span class="indicador"></span>

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

    <!-- ===== AVISO DE COOKIES ===== -->
    <div id="cookieBanner" class="cookie-banner hidden">

        <p>
            Usamos cookies técnicas para que la aplicación
            funcione correctamente.
        </p>

        <button
            id="acceptCookies"
            class="cookie-btn-accept">

            Aceptar

        </button>

    </div>

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