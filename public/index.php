<?php
require_once __DIR__ . '/src/controllers/AuthController.php';

$pagina = $_GET['pagina'] ?? 'home';

$auth = new AuthController();

switch ($pagina) {
    case 'login':
        require 'views/login.html';
        break;

    case 'register':
        require 'views/registro.html';
        break;

    case 'sobreNosotros':
        require 'views/sobreNosotros.html';
        break;

    default:
        require 'views/home.html';
        break;
}