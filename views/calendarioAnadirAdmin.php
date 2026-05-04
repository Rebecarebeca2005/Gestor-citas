
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
    <link rel="stylesheet" href="assets/css/calendario.css">
</head>

<body>

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
        <a href="index.php?pagina=centroControl">Inicio</a>
        <a href="index.php?pagina=misCitas">Mis citas</a>
        <a href="index.php?pagina=calendarioAñadir">Nueva cita</a>
        <a href="index.php?pagina=calendarioModificar">Editar cita</a>
        <a href="index.php?pagina=calendarioEliminar">Eliminar cita</a>
        <a href="#">Perfil</a> 
        <a href="index.php?pagina=home">Cerrar sesión</a> 
    </nav>

</aside>

<!-- =========================
        CALENDARIO
========================= -->
<section class="calendario">

    <div class="cal-header">

        <div class="cal-nav">
            <button id="prevMes">‹</button>
            <h2 id="mesActual">Enero 2026</h2>
            <button id="nextMes">›</button>
        </div>

        <button class="btn-hoy" id="btnHoy">Hoy</button>
    </div>

    <div class="cal-wrapper">

        <div class="cal-weekdays">
            <div>Lun</div><div>Mar</div><div>Mié</div>
            <div>Jue</div><div>Vie</div><div>Sáb</div><div>Dom</div>
        </div>

        <div id="calGrid" class="cal-grid"></div>

    </div>

</section>

<!-- MODAL REGISTRO / CREAR -->
<div id="modalCita" class="modal hidden">
    <div class="modal-content">
        <span class="cerrar-modal" onclick="cerrarCita()">✖</span>

        <!-- PASOS -->
        <ul class="pasos">
            <li class="activo">Servicio</li>
            <li>Datos</li>
            <li>Disponibilidad</li>
        </ul>

        <form id="formCita" class="formulario">
            <!-- PASO 1: SERVICIO -->
            <fieldset class="seccion activo">
                <h3>Elige servicio</h3>
                <select name="id_servicio" required>
                    <option value="">Selecciona un servicio</option>
                    <option value="1">Corte de pelo</option>
                </select>
                <button type="button" class="btn-siguiente">Siguiente</button>
            </fieldset>

            <!-- PASO 2: INFO CITA -->
            <fieldset class="seccion">
                <h3>Datos de la cita</h3>
                <input type="text" name="titulo" placeholder="Título" required>
                <textarea name="descripcion" placeholder="Descripción"></textarea>
                <input type="date" name="fecha" required>
                <input type="time" name="hora" required>
                <button type="button" class="btn-siguiente">Siguiente</button>
            </fieldset>

            <!-- PASO 3: DISPONIBILIDAD -->
            <fieldset class="seccion">
                <h3>Disponibilidad</h3>
                <select name="id_disponibilidad">
                    <option value="">Selecciona horario disponible</option>
                </select>
                <button type="submit" class="btn-enviar">Crear cita</button>
            </fieldset>
        </form>
    </div>
</div>

<!-- =========================
        FOOTER
========================= -->
<footer class="footer"> 
    <p>Gestor de Citas © 2026</p> 
    <a href="https://github.com/Rebecarebeca2005/Gestor-citas.git" target="_blank">
        Ver en GitHub
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

<script src="assets/js/calendario.js"></script>


</body>
</html>