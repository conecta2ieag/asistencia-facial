<?php

header("Content-Type: application/json");

require_once "conexion.php";


$datos = json_decode(
    file_get_contents("php://input"),
    true
);


if (!$datos) {

    echo json_encode([
        "exito" => false,
        "mensaje" => "Datos inválidos."
    ]);

    exit;
}


if (
    !isset($datos["estudiante_id"]) ||
    !isset($datos["descriptor"])
) {

    echo json_encode([
        "exito" => false,
        "mensaje" => "Faltan datos."
    ]);

    exit;
}


$estudiante_id =
    intval($datos["estudiante_id"]);


$descriptor =
    $datos["descriptor"];


if (!is_array($descriptor)) {

    echo json_encode([
        "exito" => false,
        "mensaje" => "Descriptor inválido."
    ]);

    exit;
}


if (count($descriptor) !== 128) {

    echo json_encode([
        "exito" => false,
        "mensaje" =>
            "El descriptor debe contener 128 valores."
    ]);

    exit;
}


$descriptor_json =
    json_encode($descriptor);


$sql = "UPDATE estudiantes
        SET descriptor = ?
        WHERE id = ?";


$stmt =
    $conexion->prepare($sql);


$stmt->bind_param(
    "si",
    $descriptor_json,
    $estudiante_id
);


if ($stmt->execute()) {

    echo json_encode([
        "exito" => true,
        "mensaje" =>
            "Rostro registrado correctamente."
    ]);

} else {

    echo json_encode([
        "exito" => false,
        "mensaje" =>
            "No se pudo guardar el rostro."
    ]);

}


$stmt->close();

$conexion->close();

?>