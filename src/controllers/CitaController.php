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

public function obtenerDisponibilidad(
    $fecha,
    $id_cita = null
) {

    return $this->model
        ->getDisponibilidadPorFecha(
            $fecha,
            $id_cita
        );
}

public function misCitas() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $id_usuario = $_SESSION['usuario_id'] ?? null;

    if (!$id_usuario) return [];

    return $this->model->getByUsuario($id_usuario);
}

public function citasPorFecha($fecha) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $id_usuario = $_SESSION['usuario_id'] ?? null;

    if (!$id_usuario) return [];

    return $this->model->getByUsuarioYFecha($id_usuario, $fecha);
}

public function getCitaById($id) {
    return $this->model->getById($id);
}

public function editarCita($data) {

    return $this->model->editarCita(

        $data['id_cita'],
        $data['fecha'],
        $data['id_disponibilidad'],
        $data['descripcion'] ?? ''
    );
}

public function cancelarCita($id_cita) {

    return $this->model->cancelarCita($id_cita);
}


}