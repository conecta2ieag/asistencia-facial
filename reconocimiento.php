<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Registrar rostro</title>

    <link rel="stylesheet" href="estilos.css">

    <style>

        .camara {
            position: relative;
            width: 640px;
            max-width: 100%;
            margin: 20px auto;
        }

        video {
            width: 100%;
            display: block;
        }

        canvas {
            position: absolute;
            top: 0;
            left: 0;
        }

        #estado {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin: 20px;
        }

        .formulario {
            max-width: 600px;
            margin: auto;
        }

    </style>

</head>

<body>

<div class="contenedor">

    <div class="tarjeta">

        <h1>Registrar rostro</h1>

        <div class="formulario">

            <label>Estudiante</label>

            <select id="estudiante">

                <option value="">
                    Seleccione un estudiante
                </option>

                <?php

                require_once "conexion.php";

                $sql = "SELECT id, nombre, documento, grado
                        FROM estudiantes
                        ORDER BY nombre";

                $resultado = $conexion->query($sql);

                while ($fila = $resultado->fetch_assoc()) {

                ?>

                    <option value="<?php echo $fila['id']; ?>">

                        <?php

                        echo htmlspecialchars(
                            $fila['nombre']
                        );

                        echo " - ";

                        echo htmlspecialchars(
                            $fila['grado']
                        );

                        ?>

                    </option>

                <?php

                }

                ?>

            </select>

        </div>

        <div class="camara">

            <video
                id="video"
                width="640"
                height="480"
                autoplay
                muted
                playsinline>
            </video>

        </div>

        <div id="estado">

            Cargando sistema facial...

        </div>

        <div style="text-align:center;">

            <button
                id="registrar"
                type="button"
                disabled>

                Registrar rostro

            </button>

        </div>

        <p style="text-align:center;">

            <a href="index.php">
                Volver al listado
            </a>

        </p>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>

<script src="js/registrar_rostro.js"></script>

</body>

</html>