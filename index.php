<?php
/* ===== CARPETA DE SESIONES ===== */
$rutaSesiones = __DIR__ . "/sesiones"; //Guardamos en una variable la ruta de la carpeta de sesiones

if (!is_dir($rutaSesiones)) { //Si no existe...

    mkdir( //Se crea con todos lo permisos
        $rutaSesiones,
        0777,
        true
    );
}

session_save_path($rutaSesiones); //Guarda las sesiones en la carpeta

date_default_timezone_set('Europe/Madrid'); //Configuración de la zona horaria
setlocale(LC_TIME, 'es_ES.UTF-8'); //Fechas en español

session_start(); //Iniciamos sesión del usuario para que podamos acceder a las variables de sesión

//Cargamos los controladores
require_once __DIR__ . '/src/controllers/AuthController.php';
require_once __DIR__ . '/src/controllers/CitaController.php';
require_once __DIR__ . '/src/controllers/centroControlController.php';
require_once __DIR__ . '/src/controllers/estadisticasController.php';

$authController = new AuthController(); //Creamos las instancias de los controladores
$citaController = new CitaController(); // ($authController->login())

$pagina = $_GET['pagina'] ?? 'home'; //Si se visita index.php?pagina=login -> $pagina = "login"

switch ($pagina) {

    /* ===== PÚBLICO ===== */

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

    /* ===== AUTH ===== */

    case 'register':

        if ($_SERVER['REQUEST_METHOD'] === 'POST') { //Si ha llegado mediante post

            $authController->register($_POST); //Llamamos al método register y se le pasan todos los datos
            exit;
        }

        require __DIR__ . '/views/registro.php'; //Añadimos la vista

        break;

    case 'login_post':

        if ($_SERVER['REQUEST_METHOD'] === 'POST') { //Si ha llegado mediante post

            $authController->login($_POST); //Llamamos al metodo login y se le pasan los datos
            exit;
        }

        break;

    /* ===== ZONA CLIENTE ===== */

    case 'centroControl':

        if (!isset($_SESSION['usuario'])) { //Si no ha iniciado sesión...

            header("Location: index.php?pagina=login"); //Pal login
            exit;
        }

        $controller = new CentroControlController(); //Añadimos el controlador del centro de control
        $controller->index(); //Llamamos al metodo index

        break;

    case 'calendario':

        if (!isset($_SESSION['usuario'])) {

            header("Location: index.php?pagina=login");
            exit;
        }

        require __DIR__ . '/views/calendario.php';

        break;

    /* ===== AÑADIR CITA ===== */

    /* 
    1. Comprueba que el usuario está logueado
    2. Obtiene las citas para marcar dias
    3. Gestiona AJAX para disponibilidad y creacion de nuevas citas
    */

    case 'calendarioAñadir':

        if (!isset($_SESSION['usuario'])) {

            header("Location: index.php?pagina=login");
            exit;
        }
        
        $citas = $citaController->misCitas(); //Capturamos la funcion miscitas

        $diasConCitas = []; //Creamos los arrays vacios
        $diasCanceladas = [];

        foreach ($citas as $cita) { //Recorremos una por una todas las citas

            if ($cita['estado'] === 'CANCELADA') { //Si está cancelada...

                $diasCanceladas[] =
                    $cita['fecha']; //Al array de citas canceladas (la fecha)

            } else {

                $diasConCitas[] =
                    $cita['fecha']; //Al array de citas activas (la fecha)
            }
    }

        // AJAX disponibilidad
        if ( //Comprobamos que venga de AJAX
            isset($_GET['ajax']) &&
            $_GET['ajax'] === 'disponibilidad'
        ) {

            header('Content-Type: application/json'); //Será JSON

            $fecha = $_GET['fecha'] ?? null; //rEecoge la fecha enviada por ajax

            if (!$fecha) { //si no llega ninguna fecha...

                echo json_encode([]); //array vacio
                exit;
            }

            $datos =
                $citaController->obtenerDisponibilidad($fecha); //consultar disponibilidad

            echo json_encode($datos); //Y lo pasamos a JSON

            exit;
        }

        // crear cita
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $citaController->crear($_POST); //Creamos la cita
            exit;
        }

        require __DIR__ . '/views/calendarioAnadir.php';

        break;

    /* ===== ELIMINAR CITA ===== */

    case 'calendarioEliminar':

        if (!isset($_SESSION['usuario'])) {

            header("Location: index.php?pagina=login");
            exit;
        }
        
        $citas = $citaController->misCitas(); //Llamamos a miscitas

        $diasConCitas = [];
        $diasCanceladas = [];

        foreach ($citas as $cita) { //Recorremos todas las citas

            if ($cita['estado'] === 'CANCELADA') {

                $diasCanceladas[] =
                    $cita['fecha'];

            } else {

                $diasConCitas[] =
                    $cita['fecha'];
            }
}

        require __DIR__ . '/views/calendarioEliminar.php';

        break;

   /* ===== MODIFICAR CITA ===== */

    case 'calendarioModificar':

        if (!isset($_SESSION['usuario'])) {

            header("Location: index.php?pagina=login");
            exit;
        }
        
        $citas = $citaController->misCitas();

        $diasConCitas = [];
        $diasCanceladas = [];

        foreach ($citas as $cita) { //Recorremos todas las citas

            if ($cita['estado'] === 'CANCELADA') {

                $diasCanceladas[] =
                    $cita['fecha'];

            } else {

                $diasConCitas[] =
                    $cita['fecha'];
            }
}
        require __DIR__ . '/views/calendarioModificar.php';

        break;

    /* ===== MIS CITAS ===== */

    case 'misCitas':

        if (!isset($_SESSION['usuario'])) {

            header("Location: index.php?pagina=login");
            exit;
        }

        $citas = $citaController->misCitas();
        
    $diasConCitas = [];
    $diasCanceladas = [];

    foreach ($citas as $cita) { //Recorremos todas las citas

        if ($cita['estado'] === 'CANCELADA') {

            $diasCanceladas[] =
                $cita['fecha'];

        } else {

            $diasConCitas[] =
                $cita['fecha'];
        }
}
        require __DIR__ . '/views/misCitas.php';

        break;

    /* ===== PERFIL ===== */

    case 'perfil':

        if (!isset($_SESSION['usuario'])) {

            header("Location: index.php?pagina=login");
            exit;
        }

        require __DIR__ . '/views/perfil.php';

        break;


    /* ===== AJAX - DETALLE CITA ===== */

    case 'citaDetalleAjax':

        header('Content-Type: application/json'); //Va a ser JSON

        $id = $_GET['id'] ?? null;

        if (!$id) {

            echo json_encode([
                'error' => 'ID vacío' 
            ]);

            exit;
        }

        $cita =
            $citaController->getCitaById($id); //Obtenemos cita x id

        echo json_encode($cita);

        exit;

    /* ===== AJAX - DETALLE CITA ADMIN ===== */

    case 'citaDetalleAdminAjax':

        header('Content-Type: application/json');

        $id = $_GET['id'] ?? null;

        if (!$id) {

            echo json_encode([
                'error' => 'ID vacío'
            ]);

            exit;
        }

    $cita = $citaController->getCitaByIdAdmin($id); //obtenemos la cita x id, del administrador

    echo json_encode($cita);

    exit;

    /* ===== AJAX - HORAS DISPONIBLES ===== */

    case 'horasDisponiblesAjax':

    header('Content-Type: application/json'); //Va a ser json

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
            ->obtenerDisponibilidad( //vamos al controlador
                $fecha,
                $id_cita
            );

    echo json_encode($horas);

    exit;

        /* ===== AJAX - EDITAR FECHA  ===== */

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

            $resultado = $citaController->editarCita($_POST); //Llamamos al controlador

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

    /* ===== AJAX - DETALLE CITA ===== */

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

        /* ===== AJAX - ELIMINAR CITA ===== */

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
                $citaController->cancelarCita($id_cita); //Llamamos al controlador

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

    /* ===== AJAX - CITAS X CITA ===== */

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
            $citaController->citasPorFecha($fecha); //llamamos al controlador

        echo json_encode($citas);

        exit;

        /* ===== AJAX - CITAS X CITA ADMIN ===== */

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

        /* ===== ZONA ADMINISTRADOR ===== */

        /* ===== CENTRO DE CONTROL ADMIN ===== */

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


            require_once __DIR__ . '/src/controllers/estadisticasController.php';

        $statsController =
            new EstadisticasController();

        // hoy
        $statsHoy =
            $statsController
                ->obtenerEstadisticas('dia'); //llamamos al controlador

        // mes
        $statsMes =
            $statsController
                ->obtenerEstadisticas('mes'); //llamamos al controlador

        $actividadReciente =
        $statsController
            ->actividadReciente(); //llamamos al controlador

            require __DIR__ . '/views/centroControlAdmin.php';

            break;

         /* ===== MIS CITAS ADMINISTRADOR ===== */

        case 'misCitasAdmin':

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

        // TODAS LAS CITAS
        $citas =
            $citaController->todasLasCitas();

        // FECHAS PARA LOS PUNTOS
        $diasConCitas = [];
        $diasCanceladas = [];

        foreach ($citas as $cita) {

            if ($cita['estado'] === 'CANCELADA') {

                $diasCanceladas[] =
                    $cita['fecha'];

            } else {

                $diasConCitas[] =
                    $cita['fecha'];
            }
        }

        require __DIR__ . '/views/misCitasAdmin.php';

        break;

         /* ===== CREAR USUARIOS ADMINISTRADOR ===== */

        case 'crearUsuarioAdmin':

            if (!isset($_SESSION['usuario'])) {

                header("Location: index.php?pagina=login");
                exit;
            }

            require __DIR__ . '/views/crearUserDesdeAdmin.php';

        break;

        /* ===== AJAX - CREAR USUARIOS ===== */

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

        /* ===== ESTADISTICAS ===== */

        case 'estadisticas':

        if (!isset($_SESSION['usuario'])) {

            header("Location: index.php?pagina=login");
            exit;
        }

        require_once __DIR__ . '/src/controllers/estadisticasController.php';

        $controller =
            new EstadisticasController();

        $tipo =
            $_GET['tipo'] ?? 'mes';

        /* ===== KPIS ===== */
        $stats =
            $controller->obtenerEstadisticas($tipo);

        /* ===== MEJORES SERVICIOS ===== */
        $serviciosTop =
            $controller->serviciosMasReservados($tipo);

       /* ===== MEJORES HORAS ===== */
        $horasTop =
            $controller->horasMasReservadas($tipo);

        /* ===== MEJORES USUARIOS ===== */
        $usuariosTop =
            $controller->usuariosMasActivos($tipo);

        /* ===== CITA POR MES - GRAFICO ===== */
        $graficoMeses =
            $controller->citasPorMes();

        require __DIR__ . '/views/estadisticas.php';

        break;

    /* ===== AJAX - ELIMINAR PERFIL ===== */

    case 'eliminarPerfilAjax':

        header('Content-Type: application/json');

        if (!isset($_SESSION['usuario'])) {

            echo json_encode([
                'ok' => false
            ]);

            exit;
        }

        require_once __DIR__ . '/src/models/user.php';

        $usuarioModel = new user();

        $id =
            $_POST['id'] ?? null;

        if (!$id) {

            echo json_encode([
                'ok' => false
            ]);

            exit;
        }

        $ok =
            $usuarioModel->eliminarUsuario($id);

        if ($ok) {

            session_destroy();

            echo json_encode([
                'ok' => true
            ]);

        } else {

            echo json_encode([
                'ok' => false
            ]);
        }

        exit;


        /* ===== POR DEFECTO ===== */

        default:

            require __DIR__ . '/views/home.php';

            break;
    }