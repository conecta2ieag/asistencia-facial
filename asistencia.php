<?php

require_once "conexion.php";

$sql = "SELECT
            a.id,
            e.nombre,
            e.documento,
            e.grado,
            a.fecha,
            a.hora,
            a.resultado

        FROM asistencia a

        INNER JOIN estudiantes e
        ON a.estudiante_id = e.id

        ORDER BY a.fecha DESC, a.hora DESC";

$resultado = $conexion->query($sql);

?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Asistencia</title>

    <link rel="stylesheet"
          href="estilos.css">

</head>

<body>

<div class="contenedor">

    <div class="tarjeta">

        <h1>Control de asistencia</h1>

        <p style="text-align:center;">

            <a href="reconocimiento.php">
                Tomar asistencia
            </a>

            |
            
            <a href="index.php">
                Estudiantes
            </a>

        </p>

    </div>


    <div class="tarjeta">

        <h2>Registros de asistencia</h2>

        <table>

            <thead>

                <tr>

                    <th>ID</th>

                    <th>Estudiante</th>

                    <th>Documento</th>

                    <th>Grado</th>

                    <th>Fecha</th>

                    <th>Hora</th>

                    <th>Estado</th>

                </tr>

            </thead>

            <tbody>

            <?php

            if ($resultado->num_rows > 0) {

                while (
                    $fila =
                    $resultado->fetch_assoc()
                ) {

            ?>

                <tr>

                    <td>
                        <?php
                        echo $fila["id"];
                        ?>
                    </td>

                    <td>
                        <?php
                        echo htmlspecialchars(
                            $fila["nombre"]
                        );
                        ?>
                    </td>

                    <td>
                        <?php
                        echo htmlspecialchars(
                            $fila["documento"]
                        );
                        ?>
                    </td>

                    <td>
                        <?php
                        echo htmlspecialchars(
                            $fila["grado"]
                        );
                        ?>
                    </td>

                    <td>
                        <?php
                        echo $fila["fecha"];
                        ?>
                    </td>

                    <td>
                        <?php
                        echo $fila["hora"];
                        ?>
                    </td>

                    <td>
                        <?php
                        echo $fila["resultado"];
                        ?>
                    </td>

                </tr>

            <?php

                }

            } else {

            ?>

                <tr>

                    <td colspan="7">
                        No existen registros.
                    </td>

                </tr>

            <?php

            }

            ?>

            </tbody>

        </table>

    </div>

</div>

</body>

</html>