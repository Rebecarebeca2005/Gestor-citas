<?php
$stats = $stats ?? [];
$citas = $citas ?? [];

// ===== LEER Y BORRAR NOTIFICACIONES =====
$id_usuario_notif = $_SESSION['usuario_id'] ?? null;
$notificaciones = [];

if ($id_usuario_notif) {
    $archivo = __DIR__ . "/../storage/notif_{$id_usuario_notif}.json";
    if (file_exists($archivo)) {
        $notificaciones = json_decode(file_get_contents($archivo), true) ?? [];
        unlink($archivo);
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de Citas | Organiza tus reservas fácilmente</title>
    <meta name="description" content="Gestiona tus citas, reservas y horarios de forma rápida y sencilla con nuestro gestor online.">
    <meta name="keywords" content="gestor de citas, reservas online, agenda digital, citas online, organizar citas">
    <meta name="author" content="Rebeca">
    <meta name="robots" content="index, follow">
    <meta name="theme-color" content="#C26A4A">
    <link rel="shortcut icon" href="assets/img/30-dias.png" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/centroControl.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/carga.css">
    <link rel="stylesheet" href="assets/css/editarcita.css">
    <link rel="stylesheet" href="assets/css/eliminarcita.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <!-- ===== MENSAJES EMERGENTES ===== -->
    <div id="popup" class="popup hidden">
        <span id="popup-text"></span>
        <span id="popup-close">✖</span>
    </div>

    <?php $nombre = $_SESSION['usuario']['nombre'] ?? 'Usuario'; ?>

    <!-- ===== NOTIFICACIONES DEL ADMIN ===== -->
    <?php if (!empty($notificaciones)): ?>
    <div id="notificaciones-admin" class="notificaciones-admin">
        <div class="notif-header">Avisos sobre tus citas</div>
        <?php foreach ($notificaciones as $notif): ?>
        <div class="notif-item">
            <span class="notif-mensaje"><?= htmlspecialchars($notif['mensaje']) ?></span>
            <span class="notif-fecha"><?= htmlspecialchars($notif['fecha']) ?></span>
        </div>
        <?php endforeach; ?>
        <button id="cerrar-notificaciones">Entendido</button>
    </div>
    <?php endif; ?>

    <!-- ===== MENÚ LATERAL ===== -->
    <div class="sidebar" id="sidebar">
        <div class="close-sidebar" onclick="toggleMenu()">✖</div>
        <div class="sidebar-header">
            <h3>Hola <?= htmlspecialchars($nombre) ?></h3>
        </div>
        <nav class="sidebar-menu">
            <a href="index.php?pagina=centroControl">Inicio</a>
            <a href="index.php?pagina=misCitas">Mis citas</a>
            <a href="index.php?pagina=calendarioAñadir">Nueva cita</a>
            <a href="index.php?pagina=calendarioModificar">Editar cita</a>
            <a href="index.php?pagina=perfil">Perfil</a>
            <a href="index.php?pagina=login">Cerrar sesión</a>
        </nav>
    </div>

    <!-- ===== CABECERA ===== -->
    <header class="topbar">
    <img
        src="assets/img/hamburguesa.png"
        class="menu-icon"
        alt="Abrir menú"
        onclick="toggleMenu()">
    <h2><h2>Panel de citas</h2></h2>
    <a href="index.php?pagina=perfil" class="topbar-perfil" title="Mi perfil">
        <img src="assets/img/usuario.png" class="perfil-icon" alt="Perfil">
    </a>
</header>


    <!-- ===== CONTENIDO PRINCIPAL ===== -->
    <main class="contenido">

        <!-- ===== SALUDO ===== -->
   
<section class="saludo">
    <h1>Hola, <span><?= htmlspecialchars($nombre) ?></span></h1>
    <p><?= strftime('%A, %d de %B', time()) ?></p>
</section>

        <!-- ===== RESUMEN DE CITAS ===== -->
        <section class="grid-resumen">
            <div class="card">
                <h3>Próxima cita</h3>
                <p>
                    <?php if ($stats['proxima']): ?>
                        <?= $stats['proxima']['fecha'] ?>
                        <?= substr($stats['proxima']['hora'], 0, 5) ?>
                    <?php else: ?>
                        Ninguna
                    <?php endif; ?>
                </p>
            </div>
            <div class="card">
                <h3>Esta semana</h3>
                <p><?= $stats['semana'] ?> citas programadas</p>
            </div>
            <div class="card">
                <h3>Total mes</h3>
                <p><?= $stats['mes'] ?> citas</p>
            </div>
        </section>

        <!-- ===== ACCIONES RÁPIDAS ===== -->
        <section class="acciones-carousel">

            <h2>¿Qué quieres hacer hoy?</h2>

            <div class="scene">
                <div class="left-zone">
                    <ul class="list">

                        <!-- ===== NUEVA CITA ===== -->
                        <li class="item">
                            <input type="radio" id="cita1" name="acciones" checked>
                            <label for="cita1">Nueva cita</label>
                            <div class="content">
                                <div class="content-full">
                                    <h1>Nueva cita</h1>
                                    <p>Reserva una nueva cita de forma rápida y sencilla.</p>
                                    <a href="index.php?pagina=calendarioAñadir" class="btn">Ver calendario</a>
                                </div>
                            </div>
                        </li>

                        <!-- ===== VER CITAS ===== -->
<li class="item">
    <input type="radio" id="cita2" name="acciones">
    <label for="cita2">Ver citas</label>
    <div class="content">
        <div class="content-full content-con-lista">
            <div class="content-info">
                <h1>Mis citas</h1>
                <p>Consulta todas tus citas programadas.</p>
                <a href="index.php?pagina=misCitas" class="btn">Ver calendario</a>
            </div>
            <ul class="mini-lista-citas">
                <?php
                $citasActivas = array_filter($citas, fn($c) => $c['estado'] !== 'CANCELADA');
                if (empty($citasActivas)): ?>
                    <li class="mini-cita-vacia">No tienes citas próximas</li>
                <?php else: ?>
                    <?php foreach ($citasActivas as $c): ?>
                    <li class="mini-cita">
                        <span class="mini-servicio"><?= htmlspecialchars($c['servicio']) ?></span>
                        <span class="mini-fecha"><?= date('d M', strtotime($c['fecha'])) ?> · <?= substr($c['hora_inicio'], 0, 5) ?></span>
                    </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</li>

<!-- ===== MODIFICAR ===== -->
<li class="item">
    <input type="radio" id="cita3" name="acciones">
    <label for="cita3">Modificar</label>
    <div class="content">
        <div class="content-full content-con-lista">
            <div class="content-info">
                <h1>Modificar cita</h1>
                <p>Edita la fecha o detalles de tus citas.</p>
                <a href="index.php?pagina=calendarioModificar" class="btn">Ver calendario</a>
            </div>
            <ul class="mini-lista-citas">
                <?php if (empty($citasActivas)): ?>
                    <li class="mini-cita-vacia">No tienes citas para modificar</li>
                <?php else: ?>
                    <?php foreach ($citasActivas as $c): ?>
                    <li class="mini-cita">
                        <span class="mini-servicio"><?= htmlspecialchars($c['servicio']) ?></span>
                        <span class="mini-fecha"><?= date('d M', strtotime($c['fecha'])) ?> · <?= substr($c['hora_inicio'], 0, 5) ?></span>
                        <a href="#" class="mini-btn-accion" title="Editar"
                        onclick="event.preventDefault(); abrirModalEditarCita(<?= $c['id_cita'] ?>)">
                            <img src="assets/img/lapiz.png" alt="Editar" style="width:16px">
                        </a>
                    </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</li>

<!-- ===== CANCELAR ===== -->
<li class="item">
    <input type="radio" id="cita4" name="acciones">
    <label for="cita4">Cancelar</label>
    <div class="content">
        <div class="content-full content-con-lista">
            <div class="content-info">
                <h1>Cancelar cita</h1>
                <p>Elimina una cita si ya no la necesitas.</p>
                <a href="index.php?pagina=calendarioEliminar" class="btn">Ver calendario</a>
            </div>
            <ul class="mini-lista-citas">
                <?php if (empty($citasActivas)): ?>
                    <li class="mini-cita-vacia">No tienes citas para cancelar</li>
                <?php else: ?>
                    <?php foreach ($citasActivas as $c): ?>
                    <li class="mini-cita">
                        <span class="mini-servicio"><?= htmlspecialchars($c['servicio']) ?></span>
                        <span class="mini-fecha"><?= date('d M', strtotime($c['fecha'])) ?> · <?= substr($c['hora_inicio'], 0, 5) ?></span>
                        <a href="#" class="mini-btn-accion" title="Cancelar"
                        onclick="event.preventDefault(); mostrarPopupEliminar(<?= $c['id_cita'] ?>)">
                            <img src="assets/img/papelera-de-reciclaje.png" alt="Cancelar" style="width:16px">
                        </a>
                    </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</li>

                    </ul>
                </div>
            </div>

        </section>

        <!-- ===== MODAL EDITAR CITA ===== -->
<div id="modalEditar" class="modal hidden">
    <div class="modal-content">
        <span class="cerrar-modal" onclick="cerrarEditar()">✖</span>
        <h2>Editar cita</h2>
        <form id="formEditarCita" method="POST" onsubmit="return false;">
            <input type="hidden" name="id_cita">
            <label for="fecha">Fecha</label>
            <input type="date" name="fecha" id="fecha">
            <label for="hora">Hora</label>
            <select name="id_disponibilidad" id="hora">
                <option value="">Selecciona una hora</option>
            </select>
            <label for="descripcion">Descripción</label>
            <textarea name="descripcion" id="descripcion"></textarea>
            <button type="button" class="btn-guardar" id="btnGuardarEdicion">Guardar cambios</button>
        </form>
    </div>
</div>

<!-- ===== CONFIRMAR ELIMINACIÓN ===== -->
<div id="popupEliminar" class="modal hidden">
    <div class="modal-content confirm">
        <h3>¿Cancelar esta cita?</h3>
        <div class="confirm-buttons">
            <button id="btnConfirmarEliminar" class="btn-eliminar">Sí, cancelar</button>
            <button id="btnCancelarEliminar" class="btn-cancelar">Volver</button>
        </div>
    </div>
</div>

    </main>

    <!-- ===== FOOTER ===== -->
    <footer class="footer">
        <p>Gestor de Citas © 2026</p>
        <a href="https://github.com/Rebecarebeca2005/Gestor-citas.git" target="_blank" rel="noopener noreferrer">Ver en GitHub</a>
        <a href="index.php?pagina=privacidad">Política de privacidad</a>
        <a href="index.php?pagina=cookies">Política de cookies</a>
        <a href="index.php?pagina=legal">Aviso legal</a>
    </footer>

    <!-- ===== LOADER ===== -->
    <div id="loader" class="loader hidden">
        <div class="spinner"></div>
        <p>Cargando...</p>
    </div>

    <!-- ===== SCRIPT MENÚ ===== -->
    <script>
        function toggleMenu() {
            document.getElementById("sidebar").classList.toggle("active");
        }
    </script>

    <!-- ===== SCRIPT NOTIFICACIONES ===== -->
    <script>
        const btnCerrarNotif = document.getElementById("cerrar-notificaciones");
        if (btnCerrarNotif) {
            btnCerrarNotif.addEventListener("click", () => {
                document.getElementById("notificaciones-admin").style.display = "none";
            });
        }
    </script>

    <script src="assets/js/centroControlCitas.js"></script>

    <!-- ===== SCRIPT DE CARGA ===== -->
    <script src="assets/js/carga.js"></script>


</body>

</html>