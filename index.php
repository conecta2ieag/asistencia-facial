<?php

require_once "conexion.php";

$sql = "SELECT * FROM estudiantes ORDER BY id DESC";
$resultado = $conexion->query($sql);

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Control de estudiantes</title>

    <link rel="stylesheet" href="estilos.css">

</head>

<body>

<div class="contenedor">

    <h1>Control de estudiantes</h1>

    <div class="tarjeta">

        <h2>Registrar estudiante</h2>

        <form action="guardar.php" method="POST">

            <label>Nombre completo</label>

            <input
                type="text"
                name="nombre"
                required
            >

            <label>Documento</label>

            <input
                type="text"
                name="documento"
                required
            >

            <label>Grado</label>

            <input
                type="text"
                name="grado"
                placeholder="Ejemplo: 11-02"
                required
            >

            <button type="submit">
                Guardar estudiante
            </button>

        </form>

    </div>


    <div class="tarjeta">

        <h2>Estudiantes registrados</h2>

        <table>

            <thead>

                <tr>

                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Documento</th>
                    <th>Grado</th>
                    <th>Fecha</th>
                    <th>Acciones</th>

                </tr>

            </thead>

            <tbody>

            <?php

            if ($resultado->num_rows > 0) {

                while ($estudiante = $resultado->fetch_assoc()) {

            ?>

                <tr>

                    <td>
                        <?php echo $estudiante["id"]; ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($estudiante["nombre"]); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($estudiante["documento"]); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($estudiante["grado"]); ?>
                    </td>

                    <td>
                        <?php echo $estudiante["fecha_registro"]; ?>
                    </td>

                    <td>

                        <a
                            class="editar"
                            href="editar.php?id=<?php echo $estudiante["id"]; ?>"
                        >
                            Editar
                        </a>

                        <a
                            class="eliminar"
                            href="eliminar.php?id=<?php echo $estudiante["id"]; ?>"
                            onclick="return confirm('¿Está seguro de eliminar este estudiante?');"
                        >
                            Eliminar
                        </a>

                    </td>

                </tr>

            <?php

                }

            } else {

            ?>

                <tr>

                    <td colspan="6">
                        No hay estudiantes registrados.
                    </td>

                </tr>

            <?php

            }

            ?>

            </tbody>

        </table>
        <h1>Control de estudiantes</h1>

        <p style="text-align:center;">
            <a href="reconocimiento.php">
                Abrir reconocimiento facial
            </a>
        </p>
    </div>

    <p style="text-align:center;">

    <a href="reconocimiento.php">
        Registrar rostro
    </a>

    |

    <a href="tomar_asistencia.php">
        Tomar asistencia
    </a>

    |

    <a href="asistencia.php">
        Ver asistencia
    </a>

</p>
</div>

</body>

</html>