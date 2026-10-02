<?php

require_once "conexion.php";

if (!isset($_GET["id"])) {

    header("Location: index.php");
    exit;

}

$id = intval($_GET["id"]);

$sql = "DELETE FROM estudiantes WHERE id = ?";

$stmt = $conexion->prepare($sql);

$stmt->bind_param("i", $id);

if ($stmt->execute()) {

    header("Location: index.php");

} else {

    echo "Error al eliminar el estudiante.";

}

$stmt->close();
$conexion->close();

?>