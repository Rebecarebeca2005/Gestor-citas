<?php
require_once __DIR__ . '/../models/cita.php';

class CitaController {
    private $model;

    public function __construct() {
        $this->model = new Cita(); //obtenemos el modelo de cita para usar sus variables
    }

    public function datosFormulario() {
        return [
            'servicios' => $this->model->getServicios() //modal de citas
        ];
    }

    /* ===== OBTENER LAS HORAS DISPONIBLES ===== */
    public function obtenerHorasDisponibles($fecha) {
        $horasOcupadas = $this->model->getHorasOcupadas($fecha); //vamos al modal 
        $disponibles = [];

        // Genera todas las horas posibles entre las 9 y las 18
        // comprobando cuáles no están ocupadas para añadirlas al listado
        // de horarios disponibles.

        for ($h = 9; $h <= 18; $h++) { 
            $hora = str_pad($h, 2, "0", STR_PAD_LEFT) . ":00:00";
            if (!in_array($hora, $horasOcupadas)) {
                $disponibles[] = substr($hora, 0, 5);
            }
        }

        return $disponibles;
    }
    
    /* ===== CREAR LAS CITAS ===== */
  public function crear($data) {

    if (session_status() === PHP_SESSION_NONE) { 
        session_start();
    }

    $id_usuario = $_SESSION['usuario_id'] ?? null;
    $id_servicio = $data['id_servicio'] ?? null;

    $id_disponibilidad = $data['id_disponibilidad'] ?? null;

    if (!$id_usuario || !$id_servicio || !$id_disponibilidad) {

    header("Location: index.php?pagina=calendarioAñadir&sinHora=1");
    exit;
}

    $ok = $this->model->crearCita( //Llamamos al modelo
        $id_usuario,
        $id_servicio,
        $id_disponibilidad
    );

    if ($ok) { // Obtiene la URL de la página anterior desde la que accedió el usuario
        $redirect = $_SERVER['HTTP_REFERER'] ?? 'index.php?pagina=calendarioAñadir';

        header("Location: $redirect&ok=1");
        exit;

    } else {
        header("Location: index.php?pagina=calendarioAñadir&error=1");
    }
    exit;
}
    /* ===== OBTENER LA DISPONIBILIDAD ===== */
    public function obtenerDisponibilidad(
        $fecha,
        $id_cita = null
    ) {

        return $this->model
            ->getDisponibilidadPorFecha( //Llamamos al modelo 
                $fecha,
                $id_cita
            );
    }

    /* ===== OBTENER TODAS LAS CITAS ===== */
    public function misCitas() {
        if (session_status() === PHP_SESSION_NONE) { //Que haya sesión
            session_start();
        }

        $id_usuario = $_SESSION['usuario_id'] ?? null; //Que haya usuario

        if (!$id_usuario) return []; //Si no hay, devuelve array vacío

        return $this->model->getByUsuario($id_usuario); //Llamamos al metodo
    }

    /* ===== OBTENER LAS CITAS POR FECHA ===== */
    public function citasPorFecha($fecha) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $id_usuario = $_SESSION['usuario_id'] ?? null;

        if (!$id_usuario) return [];

        return $this->model->getByUsuarioYFecha($id_usuario, $fecha); //al modelo
    }

    /* ===== OBTENER TODAS LAS CITAS POR FECHA ===== */
    public function todasLasCitasPorFecha($fecha) {

        return $this->model
            ->getTodasLasCitasPorFecha($fecha);
    }

    /* ===== OBTENER TODAS LAS CITAS ===== */
    public function todasLasCitas() {

        return $this->model->obtenerTodasLasCitas(); //devolvemos el modal 
    }

    /* ===== OBTENER CITA X SU ID ===== */
    public function getCitaById($id) {
        return $this->model->getById($id); //vamos al modelo
    }

    /* ===== EDITAR CITA ===== */
    public function editarCita($data) {

        return $this->model->editarCita( //Llamamos al modelo

            $data['id_cita'],
            $data['fecha'],
            $data['id_disponibilidad']
        );
    }

    /* ===== CANCELAR CITA ===== */
    public function cancelarCita($id_cita) {

        return $this->model->cancelarCita($id_cita); //al modelo
    }

    /* ===== EDITAR CITA ADMINISTRADOR ===== */
    public function editarCitaAdmin($data) {

        return $this->model->editarCitaAdmin( //al modal

            $data['id_cita'] ?? null,
            $data['fecha'] ?? null,
            $data['id_disponibilidad'] ?? null,
            $data['estado'] ?? 'ACTIVA'
        );
    }

    /* ===== OBTENER CITA POR ID DE ADMIN ===== */
    public function getCitaByIdAdmin($id) {

        return $this->model->getByIdAdmin($id); //al modal
    }


    }