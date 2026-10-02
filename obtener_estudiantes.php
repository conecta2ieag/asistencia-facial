<?php

header("Content-Type: application/json");

require_once "conexion.php";

$sql = "SELECT
            id,
            nombre,
            documento,
            grado,
            descriptor
        FROM estudiantes
        WHERE descriptor IS NOT NULL
        AND descriptor <> ''";

$resultado =
    $conexion->query($sql);

$estudiantes = [];

while (
    $fila =
    $resultado->fetch_assoc()
) {

    $estudiantes[] = $fila;

}

echo json_encode(
    $estudiantes,
    JSON_UNESCAPED_UNICODE
);

$conexion->close();

?>