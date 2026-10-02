<?php

require_once "conexion.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: index.php");
    exit;

}

$id = intval($_POST["id"]);

$nombre = trim($_POST["nombre"]);
$documento = trim($_POST["documento"]);
$grado = trim($_POST["grado"]);

$sql = "UPDATE estudiantes
        SET nombre = ?,
            documento = ?,
            grado = ?
        WHERE id = ?";

$stmt = $conexion->prepare($sql);

$stmt->bind_param(
    "sssi",
    $nombre,
    $documento,
    $grado,
    $id
);

if ($stmt->execute()) {

    header("Location: index.php");

} else {

    if ($conexion->errno == 1062) {

        echo "El documento ya pertenece a otro estudiante.";

    } else {

        echo "Error al actualizar: "
             . $conexion->error;

    }

}

$stmt->close();
$conexion->close();

?>