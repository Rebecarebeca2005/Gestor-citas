<?php
require_once __DIR__ . '/../config/database.php';

class Cita {
    private $pdo;

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->connect();
    }

    public function getByUsuario($id_usuario) {
        $sql = "SELECT c.*, s.nombre AS servicio 
                FROM citas c 
                INNER JOIN servicios s ON c.id_servicio = s.id_servicio 
                WHERE c.id_usuario = :id_usuario 
                ORDER BY c.fecha ASC, c.hora ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_usuario' => $id_usuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getServicios() {
        $sql = "SELECT * FROM servicios WHERE activo = 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHorasOcupadas($fecha) {
        $sql = "SELECT hora FROM citas 
                WHERE fecha = :fecha 
                AND estado != 'CANCELADA'";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':fecha' => $fecha]);

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function crearCita($id_usuario, $id_servicio, $id_disponibilidad) {

        $sql = "SELECT fecha, hora_inicio 
                FROM disponibilidad 
                WHERE id_disponibilidad = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id_disponibilidad]);
        $disp = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$disp) return false;

        $sql = "INSERT INTO citas 
                (id_usuario, id_servicio, id_disponibilidad, fecha, hora, estado)
                VALUES 
                (:id_usuario, :id_servicio, :id_disp, :fecha, :hora, 'ACTIVA')";

        $stmt = $this->pdo->prepare($sql);

        $ok = $stmt->execute([
            ':id_usuario' => $id_usuario,
            ':id_servicio' => $id_servicio,
            ':id_disp' => $id_disponibilidad,
            ':fecha' => $disp['fecha'],
            ':hora' => $disp['hora_inicio']
        ]);

        if ($ok) {
            $this->pdo->prepare("
                UPDATE disponibilidad 
                SET disponible = 0 
                WHERE id_disponibilidad = :id
            ")->execute([':id' => $id_disponibilidad]);
        }

        return $ok;
    }

    public function getDisponibilidadPorFecha($fecha) {
        $sql = "SELECT * FROM disponibilidad 
                WHERE fecha = :fecha 
                AND disponible = 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':fecha' => $fecha]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

public function getProximaCita($id_usuario) {

    $sql = "SELECT fecha, hora
            FROM citas
            WHERE id_usuario = :id_usuario
              AND estado = 'ACTIVA'
            ORDER BY fecha ASC, hora ASC
            LIMIT 1";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id_usuario' => $id_usuario]);

    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

public function getCitasSemana($id_usuario) {
    $sql = "SELECT COUNT(*) 
            FROM citas 
            WHERE id_usuario = :id
            AND YEARWEEK(fecha, 1) = YEARWEEK(CURDATE(), 1)
            AND estado != 'CANCELADA'";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id_usuario]);

    return $stmt->fetchColumn();
}

public function getCitasMes($id_usuario) {
    $sql = "SELECT COUNT(*) 
            FROM citas 
            WHERE id_usuario = :id
            AND MONTH(fecha) = MONTH(CURDATE())
            AND YEAR(fecha) = YEAR(CURDATE())
            AND estado != 'CANCELADA'";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id_usuario]);

    return $stmt->fetchColumn();
}

}