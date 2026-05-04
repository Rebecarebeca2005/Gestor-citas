<?php

$sessionPath = __DIR__ . '/sesiones';
if (!is_dir($sessionPath)) {
    mkdir($sessionPath, 0777, true);
}
session_save_path($sessionPath);

session_start();

require_once __DIR__ . '/src/controllers/AuthController.php';
require_once __DIR__ . '/src/controllers/CitaController.php';
require_once __DIR__ . '/src/controllers/CentroControlController.php';

$pagina = $_GET['pagina'] ?? 'home';

$authController = new AuthController();
$citaController = new CitaController(); 

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
            $authController->register($_POST);
            exit;
        }
        require __DIR__ . '/views/registro.php';
        break;

    case 'login_post':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->login($_POST);
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

    $controller = new CentroControlController();
    $controller->index();
    break;

    case 'calendario':
        if (!isset($_SESSION['usuario'])) {
            header("Location: index.php?pagina=login");
            exit;
        }
        require __DIR__ . '/views/calendario.php';
        break;

    case 'calendarioAñadir':

       if (!isset($_SESSION['usuario'])) {
        header("Location: index.php?pagina=login");
        exit;
    }

    // AJAX separado (IMPORTANTE)
    if (isset($_GET['ajax']) && $_GET['ajax'] === 'disponibilidad') {

        $fecha = $_GET['fecha'] ?? null;

        if (!$fecha) {
            echo json_encode([]);
            exit;
        }

        $datos = $citaController->obtenerDisponibilidad($fecha);

        echo json_encode($datos);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $citaController->crear($_POST);
        exit;
    }

    require __DIR__ . '/views/calendarioAnadir.php';
    break;

    case 'calendarioEliminar':
        if (!isset($_SESSION['usuario'])) {
            header("Location: index.php?pagina=login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // $citaController->eliminar($_POST['id_cita']);
            header("Location: index.php?pagina=misCitas");
            exit;
        }

        require __DIR__ . '/views/calendarioEliminar.php';
        break;

    case 'misCitas':
        if (!isset($_SESSION['usuario'])) {
            header("Location: index.php?pagina=login");
            exit;
        }

        // $citas = $citaController->misCitas();

        require __DIR__ . '/views/misCitas.php';
        break;

   case 'citasMesAjax':

    header('Content-Type: application/json');

    if (!isset($_SESSION['usuario_id'])) {
        echo json_encode([]);
        exit;
    }

    $mes = $_GET['mes'] ?? 0;
    $anio = $_GET['anio'] ?? 0;
    $id = $_SESSION['usuario_id'];

    require_once __DIR__ . '/src/config/database.php';

    $db = (new Database())->connect();

    $stmt = $db->prepare("
            SELECT 
        DAY(fecha) AS dia,
        COUNT(*) AS total
    FROM citas
    WHERE MONTH(fecha) = :mes
    AND YEAR(fecha) = :anio
    AND id_usuario = :id
    AND estado = 'ACTIVA'
    GROUP BY DAY(fecha)
    ");

    $stmt->execute([
        ':mes' => $mes,
        ':anio' => $anio,
        ':id' => $id
    ]);

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
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

    default:
        require __DIR__ . '/views/home.php';
        break;
}

