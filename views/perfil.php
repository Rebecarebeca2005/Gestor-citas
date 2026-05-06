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

    <link rel="stylesheet" href="assets/css/perfil.css">
    <link rel="stylesheet" href="assets/css/centroControl.css">
</head>
<body>

<?php
$nombre = $_SESSION['usuario']['nombre'] ?? 'Usuario';
$correo = $_SESSION['usuario']['email'] ?? 'Email';
$tlfn = $_SESSION['usuario']['telefono'] ?? 'Teléfono';
?>
<?php
$nombre = $_SESSION['usuario']['nombre'] ?? 'Usuario';
?>

<div class="sidebar" id="sidebar">
<div class="close-sidebar" onclick="toggleMenu()">✖</div>
    <div class="sidebar-header">
        <h3>Hola  <?= htmlspecialchars($nombre) ?></h3>
    </div>

    <nav class="sidebar-menu">
        <a href="index.php?pagina=centroControl">Inicio</a>
        <a href="index.php?pagina=misCitas">Mis citas</a>
        <a href="index.php?pagina=calendarioAñadir">Nueva cita</a>
        <a href="index.php?pagina=calendarioModificar">Editar cita</a>
        <a href="index.php?pagina=calendarioEliminar">Eliminar cita</a>
        <a href="index.php?pagina=perfil">Perfil</a> 
        <a href="index.php?pagina=login">Cerrar sesión</a> 
    </nav>

</div>

<header class="topbar">
    <img src="assets/img/hamburguesa.png" class="menu-icon" onclick="toggleMenu()">
    <h2>Perfil del usuario</h2>
</header>

<main class="contenido">

    <div class="perfil-container">

    <h2>Mi perfil</h2>

    <div class="perfil-card">

        <p><strong>Nombre:</strong> <?= $nombre ?></p>
         <p><strong>Correo electrónico:</strong> <?= $correo ?></p>
         <p><strong>Teléfono:</strong> <?= $tlfn ?></p>

        <button id="btnEliminar" class="btn-eliminar">
            Eliminar cuenta
        </button>

    </div>

</div

</main>
<div id="popupEliminar" class="hidden">

    <div class="modal-content confirm">

        <h3>¿Seguro que quieres eliminar tu cuenta?</h3>
        <p>Se borrarán todos tus datos y citas.</p>

        <div class="confirm-buttons">

            <button class="btn-eliminar" onclick="eliminarPerfil()">
                Sí, eliminar
            </button>

            <button class="btn-cancelar" onclick="cerrarPopup()">
                Cancelar
            </button>

        </div>

    </div>

</div>
<footer class="footer"> 
    <p>Gestor de Citas © 2026</p> 
    <a href="https://github.com/Rebecarebeca2005/Gestor-citas.git" target="_blank"> Ver en GitHub </a>
 </footer>
</body>
</html>

<script>
function toggleMenu() {
    document.getElementById("sidebar").classList.toggle("active");
}

const popup = document.getElementById("popupEliminar");

document.getElementById("btnEliminar").onclick = () => {
    popup.classList.remove("hidden");
};

function cerrarPopup() {
    popup.classList.add("hidden");
}

function eliminarPerfil(idUsuario) {

    fetch("index.php?pagina=eliminarPerfilAjax", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "id=" + idUsuario
    })
    .then(res => res.json())
    .then(data => {

        if (data.ok) {
            alert("Cuenta eliminada");
            window.location.href = "index.php?pagina=login";
        } else {
            alert("No se pudo eliminar la cuenta");
        }
    })
    .catch(err => console.error(err));
}
</script>