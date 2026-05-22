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
        content="Regístrate para gestionar citas, reservas y horarios de forma rápida y sencilla.">

    <meta
        name="keywords"
        content="registro usuario, crear cuenta, gestor de citas, reservas online">

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
    <link rel="stylesheet" href="assets/css/registro.css">
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="assets/css/carga.css">
    <link rel="stylesheet" href="assets/css/header.css">

    <!-- ===== JQUERY ===== -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- ===== SCRIPT REGISTRO ===== -->
    <script src="assets/js/registro.js"></script>

</head>

<body>

    <!-- ===== MENSAJES EMERGENTES ===== -->
    <?php if (isset($_GET['error'])): ?>

        <div class="error">

            <?= htmlspecialchars($_GET['error']) ?>

        </div>

    <?php endif; ?>

    <?php if (isset($_GET['success'])): ?>

        <div class="success">

            <?= htmlspecialchars($_GET['success']) ?>

        </div>

    <?php endif; ?>

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

        <!-- ===== FORMULARIO REGISTRO ===== -->
        <section>

            <div class="contenedor">

                <div class="envoltorio">

                    <!-- ===== PASOS ===== -->
                    <ul class="pasos">

                        <li class="activo">
                            Datos personales
                        </li>

                        <li>
                            Acceso
                        </li>

                        <li>
                            Finalizar
                        </li>

                    </ul>

                    <form
                        class="formulario"
                        method="post"
                        action="?pagina=register">

                        <!-- ===== PASO 1 ===== -->
                        <fieldset class="seccion activo">

                            <h1>
                                Datos personales
                            </h1>
                             <!-- ===== NOMBRE Y APELLIDOS ===== -->
                            <div class="fila-doble">

                                <input
                                    type="text"
                                    name="nombre"
                                    placeholder="Nombre"
                                    required>

                                <input
                                    type="text"
                                    name="apellidos"
                                    placeholder="Apellidos"
                                    required>

                            </div>

                            <input
                                type="email"
                                name="email"
                                placeholder="Correo electrónico"
                                required>

                            <input
                                type="tel"
                                name="telefono"
                                placeholder="Teléfono"
                                required>

                            <p class="etiqueta-radio">
                                Género
                            </p>

                            <!-- ===== SELECCIÓN DE GÉNERO ===== -->
                            <div class="fila cf">

                                <div class="cuatro columna">

                                    <input
                                        type="radio"
                                        name="genero"
                                        id="hombre"
                                        value="Hombre"
                                        required>

                                    <label for="hombre">
                                        Hombre
                                    </label>

                                </div>

                                <div class="cuatro columna">

                                    <input
                                        type="radio"
                                        name="genero"
                                        id="mujer"
                                        value="Mujer">

                                    <label for="mujer">
                                        Mujer
                                    </label>

                                </div>

                                <div class="cuatro columna">

                                    <input
                                        type="radio"
                                        name="genero"
                                        id="otro"
                                        value="Otro">

                                    <label for="otro">
                                        Otro
                                    </label>

                                </div>

                            </div>

                            <button
                                type="button"
                                class="btn-siguiente">

                                Siguiente

                            </button>

                        </fieldset>

                        <!-- ===== PASO 2 ===== -->
                        <fieldset class="seccion">

                            <h2>
                                Credenciales de acceso
                            </h2>

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

                            <div class="contenedor-password">

                                <input
                                    type="password"
                                    id="password2"
                                    name="password2"
                                    placeholder="Repetir contraseña"
                                    required>

                                <img
                                    src="assets/img/ojo-abierto.png"
                                    alt="Mostrar contraseña"
                                    class="mostrar-password"
                                    data-target="password2">

                            </div>

                            <button
                                type="button"
                                class="btn-siguiente">

                                Siguiente

                            </button>

                        </fieldset>

                        <!-- ===== PASO 3 ===== -->
                        <fieldset class="seccion">

                            <h2>
                                ¡Cuenta creada!
                            </h2>

                            <p>
                                Revisa los datos y finaliza
                                el registro.
                            </p>

                            <button
                                class="btn-enviar"
                                type="submit">

                                Registrar

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

    <!-- ===== SCRIPT DE CARGA ===== -->
    <script src="assets/js/carga.js"></script>

</body>

</html>