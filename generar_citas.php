<?php
require_once __DIR__ . '/src/config/database.php';

$db = new Database();
$pdo = $db->connect();

// =========================
// OBTENER DISPONIBILIDADES
// =========================
$sql = "
    SELECT *
    FROM disponibilidad
    WHERE disponible = 1
";

$disponibilidades =
    $pdo->query($sql)
        ->fetchAll(PDO::FETCH_ASSOC);

// =========================
// USUARIOS CLIENTE
// =========================
$sqlUsuarios = "
    SELECT id_usuario
    FROM usuarios
    WHERE rol = 'CLIENTE'
";

$usuarios =
    $pdo->query($sqlUsuarios)
        ->fetchAll(PDO::FETCH_COLUMN);

// =========================
// SERVICIOS
// =========================
$sqlServicios = "
    SELECT id_servicio
    FROM servicios
";

$servicios =
    $pdo->query($sqlServicios)
        ->fetchAll(PDO::FETCH_COLUMN);

// =========================
// ESTADOS
// =========================
$estados = [
    'ACTIVA',
    'ACTIVA',
    'ACTIVA',
    'ACTIVA',
    'CANCELADA'
];

// =========================
// INSERT CITA
// =========================
$stmt = $pdo->prepare("
    INSERT INTO citas
    (
        id_usuario,
        id_servicio,
        id_disponibilidad,
        fecha,
        hora,
        estado
    )
    VALUES
    (
        :usuario,
        :servicio,
        :disponibilidad,
        :fecha,
        :hora,
        :estado
    )
");

// =========================
// UPDATE DISPONIBILIDAD
// =========================
$update = $pdo->prepare("
    UPDATE disponibilidad
    SET disponible = 0
    WHERE id_disponibilidad = :id
");

// =========================
// GENERAR CITAS
// =========================
foreach ($disponibilidades as $d) {

    $mes =
        (int) date(
            'n',
            strtotime($d['fecha'])
        );

    // =========================
    // PROBABILIDAD POR MES
    // =========================
    switch ($mes) {

        case 1:
            $probabilidad = 2;
            break;

        case 2:
            $probabilidad = 5;
            break;

        case 3:
            $probabilidad = 15;
            break;

        case 4:
            $probabilidad = 30;
            break;

        case 5:
            $probabilidad = 60;
            break;

        case 6:
            $probabilidad = 85;
            break;

        case 7:
            $probabilidad = 100;
            break;

        case 8:
            $probabilidad = 95;
            break;

        case 9:
            $probabilidad = 50;
            break;

        case 10:
            $probabilidad = 25;
            break;

        case 11:
            $probabilidad = 8;
            break;

        case 12:
            $probabilidad = 3;
            break;

        default:
            $probabilidad = 20;
    }

    // =========================
    // RANDOM
    // =========================
    if (rand(1,100) > $probabilidad) {
        continue;
    }

    // =========================
    // CREAR CITA
    // =========================
    $stmt->execute([

        ':usuario' =>
            $usuarios[array_rand($usuarios)],

        ':servicio' =>
            $servicios[array_rand($servicios)],

        ':disponibilidad' =>
            $d['id_disponibilidad'],

        ':fecha' =>
            $d['fecha'],

        ':hora' =>
            $d['hora_inicio'],

        ':estado' =>
            $estados[array_rand($estados)]
    ]);

    // =========================
    // BLOQUEAR DISPONIBILIDAD
    // =========================
    $update->execute([
        ':id' => $d['id_disponibilidad']
    ]);
}

echo "Citas generadas automáticamente";
?>