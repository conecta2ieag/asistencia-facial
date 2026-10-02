<?php

require_once "conexion.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: index.php");
    exit;

}

$nombre = trim($_POST["nombre"]);
$documento = trim($_POST["documento"]);
$grado = trim($_POST["grado"]);

$sql = "INSERT INTO estudiantes
        (nombre, documento, grado)
        VALUES (?, ?, ?)";

$stmt = $conexion->prepare($sql);

$stmt->bind_param(
    "sss",
    $nombre,
    $documento,
    $grado
);

if ($stmt->execute()) {

    header("Location: index.php");

} else {

    if ($conexion->errno == 1062) {

        echo "El documento ya está registrado.";

    } else {

        echo "Error al guardar: "
             . $conexion->error;

    }

}

$stmt->close();
$conexion->close();

?>