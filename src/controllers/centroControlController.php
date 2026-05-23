<?php
require_once __DIR__ . '/../models/cita.php';

class CentroControlController {

    private $model;

    public function __construct() {
        $this->model = new Cita(); //Añadimos al modal de cita para usar sus cosas
    }

    public function index() {

        if (session_status() === PHP_SESSION_NONE) { //Que exista la sesion, sino se crea
            session_start();
        }

        $id_usuario = $_SESSION['usuario_id'] ?? null; //Obtenemos la id del user y la guardamos

        if (!$id_usuario) { //Si no existe, pal login
            header("Location: index.php?pagina=login");
            exit;
        }

        $stats = [ //Guardamos las estadisticas en un array, así lo podemos usar
            'proxima' => $this->model->getProximaCita($id_usuario), //llamamos a los metodos que recopilan esta info
            'semana'  => $this->model->getCitasSemana($id_usuario),
            'mes'     => $this->model->getCitasMes($id_usuario)
        ];

        $citas = $this->model->getByUsuario($id_usuario);

        // esto lo mandamos a la vista
        require __DIR__ . '../../../views/centroControl.php';
    }
}