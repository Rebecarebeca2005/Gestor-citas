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
    <link rel="stylesheet" href="assets/css/calendarioEliminar.css">
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
            <li class="activo">Seleccionar</li>
            <li>Confirmar</li>
            <li>Eliminar</li>
        </ul>

        <form id="formCita" class="formulario">
            <!-- PASO 1: SERVICIO -->
            <fieldset class="seccion activo">
                <h3>Selecciona la cita</h3>
                 <select name="id_cita" required>
                    <option value="">Selecciona una cita</option>
                    <option value="1">Lunes 10:00 - Corte de pelo</option>
                    <option value="2">Miércoles 16:30 - Revisión médica</option>
                    <option value="3">Viernes 12:00 - Entrenamiento personal</option>
                </select>
                <button type="button" class="btn-siguiente">Siguiente</button>
            </fieldset>

            <!-- PASO 2: CONFIRMACIÓN CITA -->
            <fieldset class="seccion">
                <h3>Confirmar eliminación</h3>
                <p>
                    Estás a punto de eliminar esta cita. Revisa la información antes de continuar:
                </p>

                <div class="info-cita">
                    <p><strong>Servicio:</strong> Corte de pelo</p>
                    <p><strong>Título:</strong> Corte semanal</p>
                    <p><strong>Fecha:</strong> 2026-01-20</p>
                    <p><strong>Hora:</strong> 10:00</p>
                    <p><strong>Descripción:</strong> Repaso de puntas y degradado</p>
                </div>

                <p style="color: var(--color-primario); font-weight: 600;">
                    ¿Seguro que quieres eliminar esta cita?
                </p>

                <button type="button" class="btn-atras">
                    Atrás
                </button>

                <button type="button" class="btn-siguiente">
                    Siguiente
                </button>
            </fieldset>

            <!-- PASO 3: DISPONIBILIDAD -->
            <fieldset class="seccion">
                <h3>Eliminación completada</h3>
                <p style="font-size: 1.1rem; font-weight: 600;">
                    Has eliminado esta cita.
                </p>
                <button type="submit" class="btn-enviar">Aceptar</button>
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