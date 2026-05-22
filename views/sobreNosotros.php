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

    <link rel="stylesheet" href="assets/css/sobreNosotros.css">
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="assets/css/carga.css">
    <link rel="stylesheet" href="assets/css/header.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body>
   <main class="sobre-main">
     <header>
    <nav class="navbar">
        <div class="izq">
           <a href="?pagina=home">
             <img src="assets/img/Logo-Nexo.png" alt="Logo-nexo" class="logo">
           </a>
        </div>
         <div class="menu">
            <a href="index.php?pagina=sobreNosotros">Sobre nosotros</a> 
            <a href="index.php?pagina=register">Regístrate</a> 
            <a href="index.php?pagina=login">Iniciar sesión</a>
        </div>
    </nav>
   </header>
    <!-- HERO -->
    <section class="sobre-hero">
        <h1>Sobre <span>Nosotros</span></h1>
        <p>
            Una aplicación web desarrollada como Trabajo de Fin de Grado para facilitar la gestión de citas de forma sencilla, intuitiva y accesible.
        </p>
    </section>

    <!-- HISTORIA -->
    <section class="sobre-bloque">
        <div class="sobre-texto">
            <h2>¿Cómo surgió este proyecto?</h2>
            <p>
                Este gestor de citas ha sido desarrollado como Trabajo de Fin de Grado del ciclo de Desarrollo de Aplicaciones Web. 
                El objetivo principal ha sido crear una herramienta práctica que permita organizar reservas y citas de manera rápida y eficiente.
            </p>
            <p>
                Durante su desarrollo se han aplicado conocimientos de programación, diseño web, 
                bases de datos y experiencia de usuario, buscando ofrecer una interfaz clara, funcional y fácil de utilizar.
            </p>
        </div>

       <div class="sobre-img">
    <div class="flip-card" onclick="this.classList.toggle('girada')">
        
        <div class="flip-inner">

            <!-- CARA DELANTERA -->
            <div class="flip-front">
                <img src="assets/img/Imagen4.png" alt="Equipo trabajando">
            </div>

            <!-- CARA TRASERA -->
            <div class="flip-back">
                <h3>Sobre el desarrollo</h3>
                <p>
                    Este proyecto ha sido diseñado y desarrollado íntegramente 
                    por una única estudiante como parte de su Trabajo de Fin de Grado en Desarrollo de Aplicaciones Web.
                </p>
            </div>

        </div>

    </div>
</div>
    </section>

    <!-- MISIÓN / VALORES -->
    <section class="sobre-cards">

        <article class="card">
            <h3>Objetivo</h3>
            <p>
                Facilitar la gestión de citas mediante una aplicación web intuitiva que permita organizar reservas y horarios de forma eficiente.
            </p>
        </article>

        <article class="card">
            <h3>Desarrollo</h3>
            <p>
                El proyecto ha sido desarrollado utilizando tecnologías web como HTML, CSS, JavaScript, PHP y MySQL, aplicando buenas prácticas de programación y diseño.
            </p>
        </article>

        <article class="card">
            <h3>Aprendizaje</h3>
            <p>
                Este trabajo ha permitido poner en práctica los conocimientos adquiridos durante 
                el ciclo formativo y afrontar un proyecto completo desde su análisis hasta su despliegue.
            </p>
        </article>

    </section>

</main>

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
<div id="loader" class="loader hidden">
    <div class="spinner"></div>
    <p>Cargando...</p>
</div>

<script src="assets/js/carga.js"></script>
</body>
</html>