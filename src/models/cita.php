<?php
require_once __DIR__ . '/../config/database.php';

class Cita {
    private $pdo;

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->connect();
    }

    /* ===== OBTENER EL USUARIO POR ID ===== */
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
            ORDER BY c.fecha ASC, c.hora ASC"; //Consulta para obtener todas las citas del usuario con el servicio y la disponibilidad

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id_usuario' => $id_usuario]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC); //Devolvemos todos los valores del dato
}


    /* ===== OBTENER LAS HORAS OCUPADAS ===== */
    public function getHorasOcupadas($fecha) {
        $sql = "SELECT hora FROM citas 
                WHERE fecha = :fecha 
                AND estado != 'CANCELADA'"; //selecciona las horas ocupadas

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':fecha' => $fecha]);

        return $stmt->fetchAll(PDO::FETCH_COLUMN); //el valor de la primera fila
    }

    /* ===== CREAR LA CITA ===== */
    public function crearCita($id_usuario, $id_servicio, $id_disponibilidad) {

        $sql = "SELECT fecha, hora_inicio 
                FROM disponibilidad 
                WHERE id_disponibilidad = :id"; // Crea una nueva cita para el usuario. Primero obtiene la fecha y hora asociadas

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id_disponibilidad]); // a la disponibilidad seleccionada, inserta la cita como activa en la base de datos
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
            ")->execute([':id' => $id_disponibilidad]); // y, si el registro se realiza correctamente, marca esa disponibilidad como no disponible.
        }

        return $ok;
    }

    /* ===== OBTENER DISPONIBILIDAD X LA FECHA ===== */
    public function getDisponibilidadPorFecha(
    $fecha,
    $id_cita = null
) {

    $sql = "
        SELECT DISTINCT
            d.id_disponibilidad,
            d.hora_inicio,
            d.hora_fin,
            d.disponible
        FROM disponibilidad d

        WHERE d.fecha = :fecha

        AND (
            d.disponible = 1
    "; 

    // añadir la hora actual de la cita
    if ($id_cita) {

        $sql .= "
            OR d.id_disponibilidad = (
                SELECT id_disponibilidad
                FROM citas
                WHERE id_cita = :id_cita
            )
        "; //Añade texto SQL existente (.=)
    }

    $sql .= ")

    ORDER BY d.hora_inicio ASC
    ";

    $stmt = $this->pdo->prepare($sql);

    $params = [
        ':fecha' => $fecha
    ];

    if ($id_cita) {
        $params[':id_cita'] = $id_cita;
    }

    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    /* ===== OBTENER LA PROXIMA CITA ===== */
    public function getProximaCita($id_usuario) {

        $sql = "SELECT fecha, hora
                FROM citas
                WHERE id_usuario = :id_usuario
                AND estado = 'ACTIVA'
                AND (
                        fecha > CURDATE()
                        OR (fecha = CURDATE() AND hora >= CURTIME())
                )
                ORDER BY fecha ASC, hora ASC
                LIMIT 1"; // Ultima cita activa del usuario, que sea ACTIVA y que la fecha y hora no haya pasado

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':id_usuario' => $id_usuario
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null; //Devolvemos la asociación, es decir $id_usuario['fecha', 'hora']
    }

    /* ===== OBTENER LAS CITAS POR SEMANA ===== */
    public function getCitasSemana($id_usuario) {

        $sql = "SELECT COUNT(*) 
                FROM citas 
                WHERE id_usuario = :id
                AND estado = 'ACTIVA'
                AND fecha >= CURDATE()
                AND YEARWEEK(fecha, 1) = YEARWEEK(CURDATE(), 1)"; //Devuelve el total de citas activas de la semana

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':id' => $id_usuario
        ]);

        return $stmt->fetchColumn(); //Devuelve solo solo un valor concreto, el contenido de la primera fila (en vez de devolver 'fecha' => 'YYYY-MM-DD' devuelve 'YYYY-MM-DD')
    }

    /* ===== OBTENER LAS CITAS POR MES ===== */
    public function getCitasMes($id_usuario) {

        $sql = "SELECT COUNT(*) 
                FROM citas 
                WHERE id_usuario = :id
                AND estado = 'ACTIVA'
                AND MONTH(fecha) = MONTH(CURDATE())
                AND YEAR(fecha) = YEAR(CURDATE())
                AND fecha >= CURDATE()"; //Devuelve el total de citas activas del mes

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':id' => $id_usuario
        ]);

        return $stmt->fetchColumn(); //Lo mismo, devuelve solo el valor concreto
    }

    public function getServicios() {
        $sql = "SELECT * FROM servicios WHERE activo = 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); //selecciona todos los servicios disponibles 
    }

    /* ===== OBTENER EL ID Y LA FECHA DEL USUARIO ===== */
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
                INNER JOIN servicios s 
                    ON c.id_servicio = s.id_servicio
                INNER JOIN disponibilidad d 
                    ON c.id_disponibilidad = d.id_disponibilidad
                WHERE c.id_usuario = :id_usuario
                AND c.fecha = :fecha
                AND c.estado != 'CANCELADA'
                ORDER BY c.hora ASC";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':id_usuario' => $id_usuario,
            ':fecha' => $fecha
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ===== CANCELAR LA CITA ===== */
    public function cancelarCita($id_cita) {

        // obtener datos cita
        $stmt = $this->pdo->prepare("
            SELECT id_disponibilidad, estado
            FROM citas
            WHERE id_cita = :id
        ");

        $stmt->execute([
            ':id' => $id_cita
        ]);

        $cita = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cita) {
            return false;
        }

        //  SI YA ESTÁ CANCELADA
        if ($cita['estado'] === 'CANCELADA') {

            return "ya_cancelada";
        }

        // cancelar cita
        $stmt = $this->pdo->prepare("
            UPDATE citas
            SET estado = 'CANCELADA'
            WHERE id_cita = :id
        ");

        $ok = $stmt->execute([
            ':id' => $id_cita
        ]);

        // liberar disponibilidad
        if ($ok) {

            $this->pdo->prepare("
                UPDATE disponibilidad
                SET disponible = 1
                WHERE id_disponibilidad = :id_disp
            ")->execute([
                ':id_disp' => $cita['id_disponibilidad']
            ]);
        }

        return $ok;
    }

    /* ===== OBTENER TODAS LAS CITAS ===== */
    public function obtenerTodasLasCitas() {

        $sql = "

            SELECT *
            FROM citas

            ORDER BY fecha DESC, hora DESC
        ";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ===== OBTENER CITA POR ID ===== */
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
                WHERE c.id_cita = :id"; //Obtenemos la cita por id

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC); //Solo la fila y el valor real
    }

    /* ===== EDITAR CITA ===== */
    public function editarCita(
        $id_cita,
        $fecha,
        $id_disponibilidad
    ) {

        /* ===== NUEVA DISPONIBILIDAD ===== */
        $stmt = $this->pdo->prepare("
            SELECT hora_inicio
            FROM disponibilidad
            WHERE id_disponibilidad = :id
        ");

        $stmt->execute([
            ':id' => $id_disponibilidad
        ]);

        $dispNueva = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$dispNueva) {
            return false;
        }

        /* ===== VIEJA DISPONIBILIDAD ===== */
        $stmt = $this->pdo->prepare("
            SELECT id_disponibilidad
            FROM citas
            WHERE id_cita = :id
        ");

        $stmt->execute([
            ':id' => $id_cita
        ]);

        $citaVieja = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$citaVieja) {
            return false;
        }

        /* ===== LIBERAR LA DISPONIBLE VIEJA ===== */
        $this->pdo->prepare("
            UPDATE disponibilidad
            SET disponible = 1
            WHERE id_disponibilidad = :id
        ")->execute([
            ':id' => $citaVieja['id_disponibilidad']
        ]);

        /* ===== OCUPAR LA NUEVA DISPONIBILIDAD ===== */
        $this->pdo->prepare("
            UPDATE disponibilidad
            SET disponible = 0
            WHERE id_disponibilidad = :id
        ")->execute([
            ':id' => $id_disponibilidad
        ]);

        /* ===== ACTUALIZAR LA CITA ===== */
        $sql = "
            UPDATE citas
            SET fecha = :fecha,
                hora = :hora,
                id_disponibilidad = :id_disp
            WHERE id_cita = :id
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([

            ':id' => $id_cita,
            ':fecha' => $fecha,
            ':hora' => $dispNueva['hora_inicio'],
            ':id_disp' => $id_disponibilidad
        ]);

        /* ===== LA RESPUESTA ===== */
        if ($stmt->rowCount() > 0) {

            return "actualizada";

        } else {

            return "sin_cambios";
        }
    }

    /* ===== EDITAR CITA ADMIN ===== */
    public function editarCitaAdmin(
        $id_cita,
        $fecha,
        $id_disponibilidad,
        $estado
    ) {

        // obtener cita actual
        $stmt = $this->pdo->prepare("
            SELECT id_disponibilidad, estado
            FROM citas
            WHERE id_cita = :id
        ");

        $stmt->execute([
            ':id' => $id_cita
        ]);

        $citaActual = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$citaActual) {
            return false;
        }

        // NO permitir ACTIVA / CANCELADA
        if (
            $citaActual['estado'] === 'ACTIVA'
            &&
            $estado === 'CANCELADA'
        ) {
            return "no_permitido";
        }

        // obtener nueva disponibilidad
        $stmt = $this->pdo->prepare("
            SELECT hora_inicio
            FROM disponibilidad
            WHERE id_disponibilidad = :id
        ");

        $stmt->execute([
            ':id' => $id_disponibilidad
        ]);

        $dispNueva = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$dispNueva) {
            return false;
        }

        // SOLO si cambia disponibilidad
        if (
            $citaActual['id_disponibilidad']
            !=
            $id_disponibilidad
        ) {

            // liberar vieja
            $this->pdo->prepare("
                UPDATE disponibilidad
                SET disponible = 1
                WHERE id_disponibilidad = :id
            ")->execute([
                ':id' => $citaActual['id_disponibilidad']
            ]);

            // ocupar nueva
            $this->pdo->prepare("
                UPDATE disponibilidad
                SET disponible = 0
                WHERE id_disponibilidad = :id
            ")->execute([
                ':id' => $id_disponibilidad
            ]);
        }

        // actualizar cita
        $stmt = $this->pdo->prepare("
            UPDATE citas
            SET
                fecha = :fecha,
                hora = :hora,
                id_disponibilidad = :id_disp,
                estado = :estado
            WHERE id_cita = :id
        ");

        $ok = $stmt->execute([
            ':id' => $id_cita,
            ':fecha' => $fecha,
            ':hora' => $dispNueva['hora_inicio'],
            ':id_disp' => $id_disponibilidad,
            ':estado' => $estado
        ]);

        if ($ok) {
            return "actualizada";
        }

        return false;
    }

    /* ===== OBTENER TODAS LAS CITAS POR FECHA ===== */
    public function getTodasLasCitasPorFecha($fecha) {

        $sql = "SELECT 
                    c.id_cita,
                    c.fecha,
                    c.estado,

                    s.nombre AS servicio,

                    d.hora_inicio,
                    d.hora_fin,

                    u.nombre,
                    u.apellidos,
                    u.telefono

                FROM citas c

                INNER JOIN usuarios u
                    ON c.id_usuario = u.id_usuario

                INNER JOIN servicios s
                    ON c.id_servicio = s.id_servicio

                INNER JOIN disponibilidad d
                    ON c.id_disponibilidad = d.id_disponibilidad

                WHERE c.fecha = :fecha

                ORDER BY d.hora_inicio ASC";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':fecha' => $fecha
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ===== OBTENER ID DE ADMIN ===== */
    public function getByIdAdmin($id) {

        $sql = "SELECT 
                    c.id_cita,
                    c.fecha,
                    c.estado,
                    c.id_disponibilidad,
                    d.hora_inicio,
                    d.hora_fin,
                    s.nombre AS servicio
                FROM citas c
                INNER JOIN disponibilidad d 
                    ON c.id_disponibilidad = d.id_disponibilidad
                INNER JOIN servicios s 
                    ON c.id_servicio = s.id_servicio
                WHERE c.id_cita = :id"; //obtener la cita por id

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
        
    }