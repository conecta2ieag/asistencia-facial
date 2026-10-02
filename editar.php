<?php

require_once "conexion.php";

if (!isset($_GET["id"])) {

    header("Location: index.php");
    exit;

}

$id = intval($_GET["id"]);

$sql = "SELECT * FROM estudiantes WHERE id = ?";

$stmt = $conexion->prepare($sql);

$stmt->bind_param("i", $id);

$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows == 0) {

    echo "Estudiante no encontrado.";
    exit;

}

$estudiante = $resultado->fetch_assoc();

?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Editar estudiante</title>

    <link rel="stylesheet" href="estilos.css">

</head>

<body>

<div class="contenedor">

    <div class="tarjeta">

        <h1>Editar estudiante</h1>

        <form action="actualizar.php" method="POST">

            <input
                type="hidden"
                name="id"
                value="<?php echo $estudiante["id"]; ?>"
            >

            <label>Nombre completo</label>

            <input
                type="text"
                name="nombre"
                value="<?php echo htmlspecialchars($estudiante["nombre"]); ?>"
                required
            >

            <label>Documento</label>

            <input
                type="text"
                name="documento"
                value="<?php echo htmlspecialchars($estudiante["documento"]); ?>"
                required
            >

            <label>Grado</label>

            <input
                type="text"
                name="grado"
                value="<?php echo htmlspecialchars($estudiante["grado"]); ?>"
                required
            >

            <button type="submit">
                Actualizar
            </button>

        </form>

        <br>

        <a href="index.php">
            Volver al listado
        </a>

    </div>

</div>

</body>

</html>