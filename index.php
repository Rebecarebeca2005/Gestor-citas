<?php

require_once __DIR__ . '/src/controllers/AuthController.php';

session_start();

$pagina = $_GET['pagina'] ?? 'home';

$controller = new AuthController();

switch ($pagina) {

    case 'home':
        require __DIR__ . '/views/home.php';
        break;

    case 'login':
        require __DIR__ . '/views/login.php';
        break;

    case 'sobreNosotros':
        require __DIR__ . '/views/sobreNosotros.php';
        break;

    case 'register':

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->register($_POST);
            exit;
        }

        require __DIR__ . '/views/registro.php';
        break;

    case 'login_post':

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->login($_POST);
            exit;
        }

        break;


    /*
    =========================
    ZONA CLIENTE
    =========================
    */

    case 'centroControl':

        if (!isset($_SESSION['usuario'])) {
            header("Location: index.php?pagina=login");
            exit;
        }

        require __DIR__ . '/views/centroControl.php';
        break;

    case 'calendario':

        if (!isset($_SESSION['usuario'])) {
            header("Location: index.php?pagina=login");
            exit;
        }

        require __DIR__ . '/views/calendario.php';
        break;

    case 'misCitas':

        if (!isset($_SESSION['usuario'])) {
            header("Location: index.php?pagina=login");
            exit;
        }

        require __DIR__ . '/views/misCitas.php';
        break;


    /*
    =========================
    ZONA ADMIN
    =========================
    */

    case 'centroControlAdmin':

    if (!isset($_SESSION['usuario'])) {
        header("Location: index.php?pagina=login");
        exit;
    }

    if (strtolower(trim($_SESSION['usuario_rol'])) !== 'admin') {
        header("Location: index.php?pagina=centroControl");
        exit;
    }

    require __DIR__ . '/views/centroControlAdmin.php';
    break;

     case 'calendarioAdmin':

        if (!isset($_SESSION['usuario'])) {
            header("Location: index.php?pagina=login");
            exit;
        }

        require __DIR__ . '/views/calendarioAdmin.php';
        break;

    case 'misCitasAdmin':

        if (!isset($_SESSION['usuario'])) {
            header("Location: index.php?pagina=login");
            exit;
        }

        require __DIR__ . '/views/misCitasAdmin.php';
        break;



}