<?php
require_once __DIR__ . '/src/config/database.php';

$db = new Database();
$pdo = $db->connect();

$inicio = new DateTime('2026-05-04');
$fin = new DateTime('2026-12-31');

// Horario fijo de la empresa
$horas = [
    ['09:00:00', '10:00:00'],
    ['10:00:00', '11:00:00'],
    ['11:00:00', '12:00:00'],
    ['12:00:00', '13:00:00'],
    ['16:00:00', '17:00:00'],
    ['17:00:00', '18:00:00']
];

$stmt = $pdo->prepare("
    INSERT INTO disponibilidad (fecha, hora_inicio, hora_fin, disponible)
    VALUES (:fecha, :inicio, :fin, 1)
");

for ($dia = $inicio; $dia <= $fin; $dia->modify('+1 day')) {

    $fecha = $dia->format('Y-m-d');

    foreach ($horas as $h) {

        $stmt->execute([
            ':fecha' => $fecha,
            ':inicio' => $h[0],
            ':fin' => $h[1]
        ]);
    }
}

echo "✔ Disponibilidad generada correctamente";