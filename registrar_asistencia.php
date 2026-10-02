<?php

header("Content-Type: application/json");

require_once "conexion.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    echo json_encode([
        "exito" => false,
        "mensaje" => "Método no permitido."
    ]);

    exit;
}

$datos = json_decode(
    file_get_contents("php://input"),
    true
);

if (!$datos || !isset($datos["estudiante_id"])) {

    echo json_encode([
        "exito" => false,
        "mensaje" => "No se recibió el estudiante."
    ]);

    exit;
}

$estudiante_id = intval(
    $datos["estudiante_id"]
);

$fecha = date("Y-m-d");
$hora = date("H:i:s");

$resultado = "Presente";

$sql = "INSERT INTO asistencia
        (estudiante_id, fecha, hora, resultado)
        VALUES (?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
        hora = VALUES(hora),
        resultado = VALUES(resultado)";

$stmt = $conexion->prepare($sql);

$stmt->bind_param(
    "isss",
    $estudiante_id,
    $fecha,
    $hora,
    $resultado
);

if ($stmt->execute()) {

    echo json_encode([
        "exito" => true,
        "mensaje" => "Asistencia registrada.",
        "fecha" => $fecha,
        "hora" => $hora
    ]);

} else {

    echo json_encode([
        "exito" => false,
        "mensaje" => "No se pudo registrar la asistencia."
    ]);
}

$stmt->close();
$conexion->close();

?>