<?php
require_once __DIR__ . '/../config/database.php';

class Cita {
    private $pdo;

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->connect();
    }

   public function getByUsuario($id_usuario) {
    $sql = "SELECT 
                c.id_cita,
                c.fecha,
                c.hora,
                c.estado,
                s.nombre AS servicio,
                d.hora_inicio,
                d.hora_fin,
                d.fecha AS fecha_disponibilidad
            FROM citas c
            INNER JOIN servicios s ON c.id_servicio = s.id_servicio
            INNER JOIN disponibilidad d ON c.id_disponibilidad = d.id_disponibilidad
            WHERE c.id_usuario = :id_usuario
            ORDER BY c.fecha ASC, c.hora ASC";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id_usuario' => $id_usuario]);
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

public function getServicios() {
    $sql = "SELECT * FROM servicios WHERE activo = 1";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getByUsuarioYFecha($id_usuario, $fecha) {
    $sql = "SELECT 
                c.id_cita,
                c.fecha,
                c.hora,
                c.estado,
                s.nombre AS servicio,
                d.hora_inicio,
                d.hora_fin
            FROM citas c
            INNER JOIN servicios s ON c.id_servicio = s.id_servicio
            INNER JOIN disponibilidad d ON c.id_disponibilidad = d.id_disponibilidad
            WHERE c.id_usuario = :id_usuario
            AND c.fecha = :fecha
            ORDER BY c.hora ASC";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
        ':id_usuario' => $id_usuario,
        ':fecha' => $fecha
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function cancelarCita($id_cita) {

    // 1. Obtener el id_disponibilidad de la cita
    $stmt = $this->pdo->prepare("
        SELECT id_disponibilidad 
        FROM citas 
        WHERE id_cita = :id
    ");
    $stmt->execute([':id' => $id_cita]);
    $cita = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$cita) return false;

    // 2. Cancelar la cita
    $stmt = $this->pdo->prepare("
        UPDATE citas 
        SET estado = 'CANCELADA' 
        WHERE id_cita = :id
    ");
    $ok = $stmt->execute([':id' => $id_cita]);

    if ($ok) {
        // 3. LIBERAR la disponibilidad 
        $this->pdo->prepare("
            UPDATE disponibilidad 
            SET disponible = 1 
            WHERE id_disponibilidad = :id_disp
        ")->execute([':id_disp' => $cita['id_disponibilidad']]);
    }

    return $ok;
}

public function getById($id) {

    $sql = "SELECT 
                c.id_cita,
                c.fecha,
                c.id_disponibilidad,
                d.hora_inicio,
                d.hora_fin,
                s.nombre AS servicio
            FROM citas c
            INNER JOIN disponibilidad d 
                ON c.id_disponibilidad = d.id_disponibilidad
            INNER JOIN servicios s 
                ON c.id_servicio = s.id_servicio
            WHERE c.id_cita = :id";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function editarCita($id, $fecha, $hora_inicio, $hora_fin, $descripcion) {

    $sql = "UPDATE citas 
            SET fecha = :fecha,
                hora_inicio = :hora_inicio,
                hora_fin = :hora_fin,
                descripcion = :descripcion
            WHERE id_cita = :id";

    $stmt = $this->pdo->prepare($sql);

    return $stmt->execute([
        ':id' => $id,
        ':fecha' => $fecha,
        ':hora_inicio' => $hora_inicio,
        ':hora_fin' => $hora_fin,
        ':descripcion' => $descripcion
    ]);
}


}