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

   case 'privacidad':
    require __DIR__ . '/views/politicaPrivacidad.php';
    break;

    case 'cookies':
        require __DIR__ . '/views/politicaCookies.php';
        break;

    case 'legal':
        require __DIR__ . '/views/avisoLegal.php';
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

    $citas = $citaController->misCitas();

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

    case 'citasPorDiaAjax':

    header('Content-Type: application/json');

    if (!isset($_SESSION['usuario_id'])) {
        echo json_encode([]);
        exit;
    }

    $fecha = $_GET['fecha'] ?? null;

    if (!$fecha) {
        echo json_encode([]);
        exit;
    }

    $citas = $citaController->citasPorFecha($fecha);

    echo json_encode($citas);
    exit;

    case 'eliminarCitaAjax':

    header('Content-Type: application/json');

    if (!isset($_SESSION['usuario_id'])) {
        echo json_encode(['ok' => false, 'msg' => 'No autenticado']);
        exit;
    }

    $id_cita = $_POST['id_cita'] ?? null;

    if (!$id_cita) {
        echo json_encode(['ok' => false, 'msg' => 'ID no recibido']);
        exit;
    }

    case 'calendarioModificar':
    if (!isset($_SESSION['usuario'])) {
        header("Location: index.php?pagina=login");
        exit;
    }

    require __DIR__ . '/views/calendarioModificar.php';
    break;


    case 'citaDetalleAjax':
    header('Content-Type: application/json');

    try {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            echo json_encode(['error' => 'ID vacío']);
            exit;
        }

        $cita = $citaController->getCitaById($id);

        echo json_encode($cita);
    } catch (Throwable $e) {
        echo json_encode([
            'error' => 'Error interno',
            'msg' => $e->getMessage()
        ]);
    }

    exit;

    case 'perfil':
    if (!isset($_SESSION['usuario'])) {
        header("Location: index.php?pagina=login");
        exit;
    }

    require __DIR__ . '/views/perfil.php';
    break;

case 'eliminarPerfil':
    if (!isset($_SESSION['usuario_id'])) {
        echo json_encode(['ok' => false]);
        exit;
    }

    require_once __DIR__ . '/src/models/user.php';
    $model = new User();

    $ok = $model->eliminarUsuario($_SESSION['usuario_id']);

    if ($ok) {
        session_destroy();
    }

    echo json_encode(['ok' => $ok]);
    exit;


   case 'eliminarPerfilAjax':

    header('Content-Type: application/json');

    if (!isset($_SESSION['usuario_id'])) {
        echo json_encode(['ok' => false, 'msg' => 'No autenticado']);
        exit;
    }

    require_once __DIR__ . '/src/models/User.php';
    $model = new User();

    $id = $_SESSION['usuario_id'];

    $ok = $model->eliminarUsuario($id);

    if ($ok) {
        session_destroy();
    }

    echo json_encode(['ok' => $ok]);
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


