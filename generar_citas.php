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
// INSERT
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
// GENERAR CITAS
// =========================
foreach ($disponibilidades as $d) {

    $mes =
        (int) date(
            'n',
            strtotime($d['fecha'])
        );

    // =========================
    // CANTIDAD SEGÚN MES
    // =========================

    switch ($mes) {

    // casi vacío
    case 1:
        $probabilidad = 2;
        break;

    case 2:
        $probabilidad = 5;
        break;

    // bajo
    case 3:
        $probabilidad = 15;
        break;

    case 4:
        $probabilidad = 30;
        break;

    // empieza fuerte
    case 5:
        $probabilidad = 60;
        break;

    // MUY fuerte
    case 6:
        $probabilidad = 85;
        break;

    // verano explotado
    case 7:
        $probabilidad = 100;
        break;

    case 8:
        $probabilidad = 95;
        break;

    // baja
    case 9:
        $probabilidad = 50;
        break;

    case 10:
        $probabilidad = 25;
        break;

    // muy bajo
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
}

echo "Citas generadas automáticamente 🚀";
?>