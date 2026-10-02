<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Tomar asistencia</title>

    <link rel="stylesheet"
          href="estilos.css">

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

        #resultado {
            text-align: center;
            font-size: 24px;
            margin: 20px;
        }

    </style>

</head>

<body>

<div class="contenedor">

    <div class="tarjeta">

        <h1>Tomar asistencia</h1>

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

        <div id="resultado"></div>

        <p style="text-align:center;">

            <a href="asistencia.php">
                Ver asistencia
            </a>

            |

            <a href="index.php">
                Estudiantes
            </a>

        </p>

    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js">
</script>

<script src="js/tomar_asistencia.js">
</script>

</body>

</html>