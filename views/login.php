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

    <link rel="stylesheet" href="assets/css/login.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="assets/js/login.js"></script>
</head>
<body>
<!-- POPUP
<div id="popup" class="popup hidden">
    <span id="popup-text"></span>
    <span id="popup-close">✖</span>
</div> -->

     <header>
    <nav>
        <div class="izq">
           <a href="?pagina=home">
             <img src="assets/img/Logo-Nexo.png" alt="Logo-nexo">
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

       <form class="formulario" method="post" action="?pagina=login_post">

  <fieldset class="seccion activo">
    <h3>Iniciar sesión</h3>

    <input type="email" name="email" placeholder="Correo electrónico" required>

    <div class="contenedor-password">
      <input type="password" id="password" name="password" placeholder="Contraseña" required>
      <img src="assets/img/ojo-abierto.png" class="mostrar-password" data-target="password">
    </div>

    <button class="btn-enviar" type="submit">Entrar</button>
  </fieldset>

</form>
      </div>
    </div>
  </section>
</main>

    <footer class="footer">
    <p>Gestor de Citas © 2026</p>
    <a href="https://github.com/Rebecarebeca2005/Gestor-citas.git" target="_blank">
        Ver en GitHub
    </a>
</footer>
</body>
</html>