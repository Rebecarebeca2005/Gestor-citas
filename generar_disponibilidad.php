<?php
require_once __DIR__ . '/src/config/database.php';

$db = new Database();
$pdo = $db->connect();

// =========================
// FECHAS
// =========================
$inicio = new DateTime('2026-05-04');
$fin = new DateTime('2026-12-31');

// =========================
// HORARIOS EMPRESA
// =========================
$horas = [

    ['09:00:00', '10:00:00'],
    ['10:00:00', '11:00:00'],
    ['11:00:00', '12:00:00'],
    ['12:00:00', '13:00:00'],
    ['16:00:00', '17:00:00'],
    ['17:00:00', '18:00:00']
];

// =========================
// COMPROBAR SI YA EXISTE
// =========================
$check = $pdo->prepare("
    SELECT COUNT(*)
    FROM disponibilidad
    WHERE fecha = :fecha
    AND hora_inicio = :inicio
");

// =========================
// INSERT DISPONIBILIDAD
// =========================
$insert = $pdo->prepare("
    INSERT INTO disponibilidad
    (
        fecha,
        hora_inicio,
        hora_fin,
        disponible
    )
    VALUES
    (
        :fecha,
        :inicio,
        :fin,
        1
    )
");

// =========================
// GENERAR
// =========================
for (
    $dia = $inicio;
    $dia <= $fin;
    $dia->modify('+1 day')
) {

    $fecha = $dia->format('Y-m-d');

    foreach ($horas as $h) {

        // =========================
        // COMPROBAR DUPLICADOS
        // =========================
        $check->execute([

            ':fecha' => $fecha,

            ':inicio' => $h[0]
        ]);

        $existe =
            $check->fetchColumn();

        // si ya existe NO insertar
        if ($existe > 0) {
            continue;
        }

        // =========================
        // INSERTAR
        // =========================
        $insert->execute([

            ':fecha' => $fecha,

            ':inicio' => $h[0],

            ':fin' => $h[1]
        ]);
    }
}

echo "Disponibilidad generada correctamente";
?>