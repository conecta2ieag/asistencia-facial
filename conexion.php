<?php

$servidor = "sql113.infinityfree.com";
$usuario = "if0_43070726";
$password = "suVOo0L5NxDUD";
$base_datos = "asistencia_facial";

$conexion = new mysqli(
    $servidor,
    $usuario,
    $password,
    $base_datos
);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$conexion->set_charset("utf8mb4");

?>
