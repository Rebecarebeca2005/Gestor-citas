<?php

require_once __DIR__ . '/src/controllers/AuthController.php';

session_start();

$pagina = $_GET['pagina'] ?? 'centroControl';

$controller = new AuthController();

switch ($pagina) {
    case 'login':
        require __DIR__ . '/views/login.php';
        break;

    case 'register':

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->register($_POST);
            header("Location: index.php?pagina=login");
            exit;
        }

        require __DIR__ . '/views/registro.php';
        break;

    case 'login_post':

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $ok = $controller->login($_POST);

            if ($ok) {
                $_SESSION['usuario'] = $_POST['email'];

                header("Location: index.php?pagina=centroControl");
                exit;
            } else {
                header("Location: index.php?pagina=login&error=1");
                exit;
            }
        }

        break;

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
        
    default:
        header("Location: index.php?pagina=centroControl");
        exit;
}