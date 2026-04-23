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

    <link rel="stylesheet" href="assets/css/registro.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="assets/js/registro.js"></script>
</head>

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


<body>

<div id="popup" class="popup hidden">
    <span id="popup-text"></span>
    <span id="popup-close">✖</span>
</div>

<header>
    <nav>
        <div class="izq">
            <a href="?pagina=home">
                <img src="assets/img/Logo-Nexo.png">
            </a>
        </div>

        <div class="menu">
            <a href="?pagina=sobreNosotros">Sobre nosotros</a>
            <a href="?pagina=register">Regístrate</a>
            <a href="?pagina=login">Iniciar sesión</a>
        </div>
    </nav>
</header>

<main>
<section>
<div class="contenedor">
<div class="envoltorio">

<ul class="pasos">
    <li class="activo">Datos personales</li>
    <li>Acceso</li>
    <li>Finalizar</li>
</ul>

<form class="formulario" method="post" action="?pagina=register">

<!-- PASO 1 -->
<fieldset class="seccion activo">

    <h3>Datos personales</h3>

    <!-- NOMBRE + APELLIDOS -->
    <div class="fila-doble">
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="text" name="apellidos" placeholder="Apellidos" required>
    </div>

    <input type="email" name="email" placeholder="Correo electrónico" required>

    <input type="tel" name="telefono" placeholder="Teléfono" required>

    <p class="etiqueta-radio">Género</p>

    <div class="fila cf">
        <div class="cuatro columna">
            <input type="radio" name="genero" id="hombre" value="Hombre" required>
            <label for="hombre"><h4>Hombre</h4></label>
        </div>

        <div class="cuatro columna">
            <input type="radio" name="genero" id="mujer" value="Mujer">
            <label for="mujer"><h4>Mujer</h4></label>
        </div>

        <div class="cuatro columna">
            <input type="radio" name="genero" id="otro" value="Otro">
            <label for="otro"><h4>Otro</h4></label>
        </div>
    </div>

    <button type="button" class="btn-siguiente">Siguiente</button>
</fieldset>

<!-- PASO 2 -->
<fieldset class="seccion">

    <h3>Credenciales de acceso</h3>

    <div class="contenedor-password">
        <input type="password" id="password" name="password" placeholder="Contraseña" required>
        <img src="assets/img/ojo-abierto.png" class="mostrar-password" data-target="password">
    </div>

    <div class="contenedor-password">
        <input type="password" id="password2" name="password2" placeholder="Repetir contraseña" required>
        <img src="assets/img/ojo-abierto.png" class="mostrar-password" data-target="password2">
    </div>

    <button type="button" class="btn-siguiente">Siguiente</button>
</fieldset>

<!-- PASO 3 -->
<fieldset class="seccion">

    <h3>¡Cuenta creada!</h3>
    <p>Revisa los datos y finaliza el registro.</p>

    <button class="btn-enviar" type="submit">Registrar</button>
</fieldset>

</form>

</div>
</div>
</section>
</main>

<footer class="footer">
    <p>Gestor de Citas © 2026</p>
</footer>

</body>
</html>