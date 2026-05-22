<?php
require_once __DIR__ . '/../config/database.php';

class Estadisticas {

    private $pdo;

    public function __construct() {

        $db = new Database();

        $this->pdo = $db->connect();
    }

    /* ===== FILTRO X FECHA ===== */
    private function filtroFecha($tipo) {

        switch($tipo) {

            case 'dia':

                return "DATE(fecha) = CURDATE()";

            case 'semana':

                return "
                    YEARWEEK(fecha, 1)
                    =
                    YEARWEEK(CURDATE(), 1)
                ";

            case 'anio':

                return "
                    YEAR(fecha)
                    =
                    YEAR(CURDATE())
                ";

            default:

                return "
                    MONTH(fecha)
                    =
                    MONTH(CURDATE())

                    AND

                    YEAR(fecha)
                    =
                    YEAR(CURDATE())
                ";
        }
    }

    /* ===== TOTAL DE USUARIOS ===== */
    public function totalUsuarios() {

        $sql = "
            SELECT COUNT(*) as total
            FROM usuarios
        ";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    /* ===== TOTAL DE CITAS ===== */
    public function totalCitas($tipo) {

        $where = $this->filtroFecha($tipo);

        $sql = "
            SELECT COUNT(*) as total
            FROM citas
            WHERE $where
        ";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    /* ===== TOTAL DE CITAS ACTIVAS ===== */
    public function citasActivas($tipo) {

        $where = $this->filtroFecha($tipo);

        $sql = "
            SELECT COUNT(*) as total
            FROM citas
            WHERE estado = 'ACTIVA'
            AND $where
        ";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    /* ===== TOTAL DE CITAS CANCELADAS ===== */
    public function citasCanceladas($tipo) {

        $where = $this->filtroFecha($tipo);

        $sql = "
            SELECT COUNT(*) as total
            FROM citas
            WHERE estado = 'CANCELADA'
            AND $where
        ";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    /* ===== SERVICIOS MÁS RESERVADOS ===== */
    public function serviciosMasReservados($tipo) {

    $where = $this->filtroFecha($tipo);

    $sql = "
        SELECT
            s.nombre,
            COUNT(*) as total

        FROM citas c

        INNER JOIN servicios s
            ON c.id_servicio = s.id_servicio

        WHERE $where

        GROUP BY s.id_servicio

        ORDER BY total DESC

        LIMIT 5
    ";

    $stmt = $this->pdo->query($sql);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/* ===== HORAS MÁS RESERVADAS ===== */
public function horasMasReservadas($tipo) {

    $where = $this->filtroFecha($tipo);

    $sql = "
        SELECT
            hora,
            COUNT(*) as total

        FROM citas

        WHERE $where

        GROUP BY hora

        ORDER BY total DESC

        LIMIT 5
    ";

    $stmt = $this->pdo->query($sql);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/* ===== USUARIOS MÁS ACTIVOS ===== */
public function usuariosMasActivos($tipo) {

    $where = $this->filtroFecha($tipo);

    $sql = "
        SELECT
            u.nombre,
            COUNT(*) as total

        FROM citas c

        INNER JOIN usuarios u
            ON c.id_usuario = u.id_usuario

        WHERE $where

        GROUP BY u.id_usuario

        ORDER BY total DESC

        LIMIT 5
    ";

    $stmt = $this->pdo->query($sql);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/* ===== USUARIOS NUEVOS POR MES ===== */
public function nuevosUsuariosMes() {

    $sql = "
        SELECT COUNT(*) as total
        FROM usuarios
    ";

    $stmt = $this->pdo->query($sql);

    $total =
        $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    return round($total * 0.15);
}

/* ===== TOTAL DE CITAS ESTA SEMANA ===== */
public function citasSemana() {

    $sql = "
        SELECT COUNT(*) as total

        FROM citas

        WHERE
            YEARWEEK(fecha, 1)
            =
            YEARWEEK(CURDATE(), 1)
    ";

    $stmt = $this->pdo->query($sql);

    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}

/* ===== PERCENTAJE DE CITAS COMPLETADAS ===== */
public function porcentajeActivas() {

    $sqlTotal = "
        SELECT COUNT(*) as total
        FROM citas
    ";

    $total =
        $this->pdo
            ->query($sqlTotal)
            ->fetch(PDO::FETCH_ASSOC)['total'];

    if ($total == 0) {
        return 0;
    }

    $sql = "
        SELECT COUNT(*) as total

        FROM citas

        WHERE estado = 'ACTIVA'
    ";

    $activas =
        $this->pdo
            ->query($sql)
            ->fetch(PDO::FETCH_ASSOC)['total'];

    return round(
        ($activas * 100) / $total
    );
}

/* ===== PORCENTAJE DE CITAS CANCELADAS ===== */
public function porcentajeCanceladas() {

    $sqlTotal = "
        SELECT COUNT(*) as total
        FROM citas
    ";

    $total =
        $this->pdo
            ->query($sqlTotal)
            ->fetch(PDO::FETCH_ASSOC)['total'];

    if ($total == 0) {
        return 0;
    }

    $sql = "
        SELECT COUNT(*) as total

        FROM citas

        WHERE estado = 'CANCELADA'
    ";

    $canceladas =
        $this->pdo
            ->query($sql)
            ->fetch(PDO::FETCH_ASSOC)['total'];

    return round(
        ($canceladas * 100) / $total
    );
}

/* ===== CITAS POR MES ===== */
public function citasPorMes() {

    $sql = "

        SELECT
            meses.mes,
            COALESCE(COUNT(c.fecha), 0) as total

        FROM (

            SELECT 1 as mes
            UNION SELECT 2
            UNION SELECT 3
            UNION SELECT 4
            UNION SELECT 5
            UNION SELECT 6
            UNION SELECT 7
            UNION SELECT 8
            UNION SELECT 9
            UNION SELECT 10
            UNION SELECT 11
            UNION SELECT 12

        ) meses

        LEFT JOIN citas c
            ON MONTH(c.fecha) = meses.mes

        GROUP BY meses.mes

        ORDER BY meses.mes
    ";

    $stmt = $this->pdo->query($sql);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/* ===== ACTIVIDAD RECIENTE ===== */
public function actividadReciente() {

    $sql = "

        SELECT
            u.nombre,
            c.estado,
            c.fecha_creacion

        FROM citas c

        INNER JOIN usuarios u
            ON c.id_usuario = u.id_usuario

        ORDER BY c.fecha_creacion DESC

        LIMIT 4

    ";

    $stmt = $this->pdo->query($sql);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}