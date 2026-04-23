<?php

$pagina = $_GET['pagina'] ?? 'home';

switch ($pagina) {
    case 'login':
        require __DIR__ . '/views/login.html';
        break;

    case 'register':
        require __DIR__ . '/views/registro.html';
        break;

    case 'sobreNosotros':
        require __DIR__ . '/views/sobreNosotros.html';
        break;

    default:
        require __DIR__ . '/views/home.html';
        break;
}