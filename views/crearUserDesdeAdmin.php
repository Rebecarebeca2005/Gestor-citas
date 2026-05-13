<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gestor de Citas | Organiza tus reservas fácilmente</title>

    <meta name="description" content="Gestiona tus citas, reservas y horarios de forma rápida y sencilla con nuestro gestor online. Organiza todo en un solo lugar.">
    <meta name="keywords" content="gestor de citas, reservas online, agenda digital, citas online, organizar citas">
    <meta name="author" content="Rebeca">

    <meta name="robots" content="index, follow">

    <link rel="shortcut icon" href="assets/img/30-dias.png" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="assets/css/carga.css">
    <link rel="stylesheet" href="assets/css/crearUserAdmin.css">
    <link rel="stylesheet" href="assets/css/calendario.css">


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    
</head>

<body>
<div id="popup" class="popup hidden">
    <span id="popup-text"></span>
    <span id="popup-close">✖</span>
</div>
<!-- =========================
     TOPBAR (MENU HAMBURGUESA)
========================= -->
<header class="topbar">
    <img src="assets/img/hamburguesa.png" class="menu-icon" onclick="toggleMenu()">
</header>

<!-- =========================
     SIDEBAR (MENU OCULTO)
========================= -->
<aside class="sidebar" id="sidebar">

    <div class="close-sidebar" onclick="toggleMenu()">✖</div>

    <div class="sidebar-header">
        <h3>Menú</h3>
    </div>

    <nav class="sidebar-menu">
        <a href="index.php?pagina=centroControlAdmin">Inicio</a>
        <a href="index.php?pagina=crearUsuarioAdmin">Usuarios</a>
        <a href="index.php?pagina=misCitasAdmin">Citas</a>
        <a href="index.php?pagina=estadisticas">Estadísticas</a>
        <a href="index.php?pagina=logout">Cerrar sesión</a>
    </nav>

</aside>


<section class="crear-user-page">

    <div class="crear-user-card">

        <div class="crear-user-header">
            <h1>Crear usuario</h1>
            <p>Añade clientes o trabajadores al sistema.</p>
        </div>

        <form
    id="formCrearUsuario"
    method="POST"
    class="crear-user-form"
>

            <div class="crear-user-field">
                <label>Nombre</label>

                <input
                    type="text"
                    name="nombre"
                    required
                >
            </div>

            <div class="crear-user-field">
    <label>Apellidos</label>

    <input
        type="text"
        name="apellidos"
        required
    >
</div>

            <div class="crear-user-field">
                <label>Correo electrónico</label>

                <input
                    type="email"
                    name="correo"
                    required
                >
            </div>

            <div class="crear-user-field">
    <label>Teléfono</label>

    <input
        type="text"
        name="telefono"
        required
    >
</div>

            <div class="crear-user-field">
                <label>Contraseña</label>

                <input
                    type="password"
                    name="password"
                    required
                >
            </div>



            <div class="crear-user-field">
                <label>Rol</label>

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
                type="submit"
                class="crear-user-btn"
            >
                Crear usuario
            </button>

        </form>

    </div>

</section>
<!-- =========================
        FOOTER
========================= -->
<footer class="footer"> 
    <p>Gestor de Citas © 2026</p> 
    <a href="https://github.com/Rebecarebeca2005/Gestor-citas.git" target="_blank">
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


<!-- =========================
        JS MENU
========================= -->
<script>
function toggleMenu() {
    document.getElementById("sidebar").classList.toggle("active");
    document.body.classList.toggle("menu-open");
}
</script>
 <div id="loader" class="loader hidden">
    <div class="spinner"></div>
    <p>Cargando...</p>
</div>
<script src="assets/js/crearUserAdmin.js"></script>
<script src="assets/js/carga.js"></script>

</body>
</html>