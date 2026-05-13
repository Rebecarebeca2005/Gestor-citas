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

    /*
    =========================
    PÚBLICO
    =========================
    */

    case 'home':
        require __DIR__ . '/views/home.php';
        break;

    case 'login':
        require __DIR__ . '/views/login.php';
        break;

    case 'sobreNosotros':
        require __DIR__ . '/views/sobreNosotros.php';
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
    AUTH
    =========================
    */

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

    /*
    =========================
    AÑADIR CITA
    =========================
    */

    case 'calendarioAñadir':

        if (!isset($_SESSION['usuario'])) {

            header("Location: index.php?pagina=login");
            exit;
        }

        // AJAX disponibilidad
        if (
            isset($_GET['ajax']) &&
            $_GET['ajax'] === 'disponibilidad'
        ) {

            header('Content-Type: application/json');

            $fecha = $_GET['fecha'] ?? null;

            if (!$fecha) {

                echo json_encode([]);
                exit;
            }

            $datos =
                $citaController->obtenerDisponibilidad($fecha);

            echo json_encode($datos);

            exit;
        }

        // crear cita
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $citaController->crear($_POST);
            exit;
        }

        require __DIR__ . '/views/calendarioAnadir.php';

        break;

    /*
    =========================
    ELIMINAR CITA
    =========================
    */

    case 'calendarioEliminar':

        if (!isset($_SESSION['usuario'])) {

            header("Location: index.php?pagina=login");
            exit;
        }

        require __DIR__ . '/views/calendarioEliminar.php';

        break;

    /*
    =========================
    MODIFICAR CITA
    =========================
    */

    case 'calendarioModificar':

        if (!isset($_SESSION['usuario'])) {

            header("Location: index.php?pagina=login");
            exit;
        }

        require __DIR__ . '/views/calendarioModificar.php';

        break;

    /*
    =========================
    MIS CITAS
    =========================
    */

    case 'misCitas':

        if (!isset($_SESSION['usuario'])) {

            header("Location: index.php?pagina=login");
            exit;
        }

        $citas = $citaController->misCitas();

        require __DIR__ . '/views/misCitas.php';

        break;

    /*
    =========================
    PERFIL
    =========================
    */

    case 'perfil':

        if (!isset($_SESSION['usuario'])) {

            header("Location: index.php?pagina=login");
            exit;
        }

        require __DIR__ . '/views/perfil.php';

        break;


    /*
    =========================
    AJAX - DETALLE CITA
    =========================
    */

    case 'citaDetalleAjax':

        header('Content-Type: application/json');

        $id = $_GET['id'] ?? null;

        if (!$id) {

            echo json_encode([
                'error' => 'ID vacío'
            ]);

            exit;
        }

        $cita =
            $citaController->getCitaById($id);

        echo json_encode($cita);

        exit;

   case 'citaDetalleAdminAjax':

    header('Content-Type: application/json');

    $id = $_GET['id'] ?? null;

    if (!$id) {

        echo json_encode([
            'error' => 'ID vacío'
        ]);

        exit;
    }

    $cita =
        $citaController->getCitaByIdAdmin($id);

    echo json_encode($cita);

    exit;

    /*
    =========================
    AJAX - HORAS DISPONIBLES
    =========================
    */

    case 'horasDisponiblesAjax':

    header('Content-Type: application/json');

    $fecha =
        $_GET['fecha'] ?? null;

    $id_cita =
        $_GET['id_cita'] ?? null;

    if (!$fecha) {

        echo json_encode([]);
        exit;
    }

    $horas =
        $citaController
            ->obtenerDisponibilidad(
                $fecha,
                $id_cita
            );

    echo json_encode($horas);

    exit;

   /*
=========================
AJAX - EDITAR CITA
=========================
*/

case 'editarCitaAjax':

    header('Content-Type: application/json');

    try {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            echo json_encode([
                'ok' => false,
                'msg' => 'Método inválido'
            ]);

            exit;
        }

        $resultado = $citaController->editarCita($_POST);

        if ($resultado === "actualizada") {

            echo json_encode([
                'ok' => true,
                'msg' => 'Cita actualizada correctamente'
            ]);

        } else if ($resultado === "sin_cambios") {

            echo json_encode([
                'ok' => false,
                'msg' => 'No se han realizado cambios'
            ]);

        } else {

            echo json_encode([
                'ok' => false,
                'msg' => 'Error al actualizar la cita'
            ]);
        }

    } catch (Throwable $e) {

        echo json_encode([
            'ok' => false,
            'msg' => $e->getMessage()
        ]);
    }

    exit;

    /*
=========================
AJAX - EDITAR CITA ADMIN
=========================
*/

case 'editarCitaAdminAjax':

    header('Content-Type: application/json');

    try {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            echo json_encode([
                'ok' => false,
                'msg' => 'Método inválido'
            ]);

            exit;
        }

        $resultado =
            $citaController
                ->editarCitaAdmin($_POST);

        if ($resultado === "actualizada") {

            echo json_encode([
                'ok' => true,
                'msg' => 'Cita actualizada correctamente'
            ]);

        } else if ($resultado === "no_permitido") {

            echo json_encode([
                'ok' => false,
                'msg' => 'No puedes cancelar una cita activa desde edición'
            ]);

        } else {

            echo json_encode([
                'ok' => false,
                'msg' => 'Error actualizando cita'
            ]);
        }

    } catch (Throwable $e) {

        echo json_encode([
            'ok' => false,
            'msg' => $e->getMessage()
        ]);
    }

    exit;

    /*
    =========================
    AJAX - ELIMINAR CITA
    =========================
    */

    case 'eliminarCitaAjax':

        header('Content-Type: application/json');

        if (!isset($_SESSION['usuario_id'])) {

            echo json_encode([
                'ok' => false,
                'msg' => 'No autenticado'
            ]);

            exit;
        }

        $id_cita = $_POST['id_cita'] ?? null;

        if (!$id_cita) {

            echo json_encode([
                'ok' => false,
                'msg' => 'ID no recibido'
            ]);

            exit;
        }

        $ok =
            $citaController->cancelarCita($id_cita);

       if ($ok === "ya_cancelada") {

    echo json_encode([
        'ok' => false,
        'msg' => 'La cita ya ha sido cancelada'
    ]);

} else {

    echo json_encode([
        'ok' => $ok
    ]);
}

        exit;

        /*
=========================
AJAX - CITAS POR DÍA
=========================
*/

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

    $citas =
        $citaController->citasPorFecha($fecha);

    echo json_encode($citas);

    exit;

    case 'citasPorDiaAdminAjax':

    header('Content-Type: application/json');

    $fecha = $_GET['fecha'] ?? null;

    if (!$fecha) {

        echo json_encode([]);
        exit;
    }

    echo json_encode(
        $citaController->todasLasCitasPorFecha($fecha)
    );

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

        if (
            strtolower(trim($_SESSION['usuario_rol']))
            !== 'admin'
        ) {

            header("Location: index.php?pagina=centroControl");
            exit;
        }

        require __DIR__ . '/views/centroControlAdmin.php';

        break;

        case 'misCitasAdmin':
             if (!isset($_SESSION['usuario'])) {

            header("Location: index.php?pagina=login");
            exit;
        }

        $citas = $citaController->misCitas();

        require __DIR__ . '/views/misCitasAdmin.php';

        break;

        case 'crearUsuarioAdmin':

    if (!isset($_SESSION['usuario'])) {

        header("Location: index.php?pagina=login");
        exit;
    }

    require __DIR__ . '/views/crearUserDesdeAdmin.php';

    break;

    case 'crearUsuarioAjax':

    header('Content-Type: application/json');

    try {

        $resultado =
            $authController->crearUsuarioAdmin($_POST);

        echo json_encode($resultado);

    } catch(Throwable $e) {

        echo json_encode([
            'ok' => false,
            'msg' => $e->getMessage()
        ]);
    }

    exit;

    case 'estadisticas':

    if (!isset($_SESSION['usuario'])) {

        header("Location: index.php?pagina=login");
        exit;
    }

    require_once __DIR__ . '/src/controllers/EstadisticasController.php';

    $controller =
        new EstadisticasController();

    $tipo =
        $_GET['tipo'] ?? 'mes';

    // =========================
    // KPIs
    // =========================
    $stats =
        $controller->obtenerEstadisticas($tipo);

    // =========================
    // TOP SERVICIOS
    // =========================
    $serviciosTop =
        $controller->serviciosMasReservados($tipo);

    // =========================
    // TOP HORAS
    // =========================
    $horasTop =
        $controller->horasMasReservadas($tipo);

    // =========================
    // TOP USUARIOS
    // =========================
    $usuariosTop =
        $controller->usuariosMasActivos($tipo);

    // =========================
    // GRÁFICO CITAS POR MES
    // =========================
    $graficoMeses =
        $controller->citasPorMes();

    // =========================
    // VIEW
    // =========================
    require __DIR__ . '/views/estadisticas.php';

    break;


    /*
    =========================
    DEFAULT
    =========================
    */

    default:

        require __DIR__ . '/views/home.php';

        break;
}