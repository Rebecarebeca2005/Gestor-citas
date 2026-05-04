<?php
require_once __DIR__ . '/../models/cita.php';

class CentroControlController {

    private $model;

    public function __construct() {
        $this->model = new Cita();
    }

    public function index() {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $id_usuario = $_SESSION['usuario_id'] ?? null;

        if (!$id_usuario) {
            header("Location: index.php?pagina=login");
            exit;
        }

        $stats = [
            'proxima' => $this->model->getProximaCita($id_usuario),
            'semana'  => $this->model->getCitasSemana($id_usuario),
            'mes'     => $this->model->getCitasMes($id_usuario)
        ];

        // esto lo mandas a la vista
        require __DIR__ . '../../../views/centroControl.php';
    }
}