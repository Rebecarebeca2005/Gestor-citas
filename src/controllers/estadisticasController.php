<?php

require_once __DIR__ . '/../models/estadisticasModel.php';

class EstadisticasController {

    private $model;

    public function __construct() {

        $this->model = new Estadisticas();
    }

    // =========================
    // KPIs
    // =========================
    public function obtenerEstadisticas($tipo) {

        return [

    'usuarios' =>
        $this->model->totalUsuarios(),

    'citas' =>
        $this->model->totalCitas($tipo),

    'activas' =>
        $this->model->citasActivas($tipo),

    'canceladas' =>
        $this->model->citasCanceladas($tipo),

    'nuevosUsuarios' =>
        $this->model->nuevosUsuariosMes(),

    'citasSemana' =>
        $this->model->citasSemana(),

    'porcentaje_activas' =>
        $this->model->porcentajeActivas(),

    'porcentaje_canceladas' =>
        $this->model->porcentajeCanceladas()
];
    }

    // =========================
    // SERVICIOS TOP
    // =========================
    public function serviciosMasReservados($tipo) {

        return $this->model
            ->serviciosMasReservados($tipo);
    }

    // =========================
    // HORAS TOP
    // =========================
    public function horasMasReservadas($tipo) {

        return $this->model
            ->horasMasReservadas($tipo);
    }

    // =========================
    // USUARIOS TOP
    // =========================
    public function usuariosMasActivos($tipo) {

        return $this->model
            ->usuariosMasActivos($tipo);
    }

    public function citasPorMes() {

    return $this->model
        ->citasPorMes();
}

}