<?php
require_once __DIR__ . '/../models/cita.php';

class CitaController {
    private $model;

    public function __construct() {
        $this->model = new Cita();
    }

    public function datosFormulario() {
        return [
            'servicios' => $this->model->getServicios()
        ];
    }

    public function obtenerHorasDisponibles($fecha) {
        $horasOcupadas = $this->model->getHorasOcupadas($fecha);
        $disponibles = [];

        for ($h = 9; $h <= 18; $h++) {
            $hora = str_pad($h, 2, "0", STR_PAD_LEFT) . ":00:00";
            if (!in_array($hora, $horasOcupadas)) {
                $disponibles[] = substr($hora, 0, 5);
            }
        }

        return $disponibles;
    }
    

  public function crear($data) {

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $id_usuario = $_SESSION['usuario_id'] ?? null;
    $id_servicio = $data['id_servicio'] ?? null;

    $id_disponibilidad = $data['id_disponibilidad'] ?? null;

    

    if (!$id_usuario || !$id_servicio || !$id_disponibilidad) {
        die("Faltan datos");
    }

    

    $ok = $this->model->crearCita(
        $id_usuario,
        $id_servicio,
        $id_disponibilidad
    );

    if ($ok) {
        $redirect = $_SERVER['HTTP_REFERER'] ?? 'index.php?pagina=calendarioAñadir';

        header("Location: $redirect&ok=1");
        exit;

    } else {
        header("Location: index.php?pagina=calendarioAñadir&error=1");
    }
    exit;
}

    public function obtenerDisponibilidad($fecha) {
    return $this->model->getDisponibilidadPorFecha($fecha);
}
}