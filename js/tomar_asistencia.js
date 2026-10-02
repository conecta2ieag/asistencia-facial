const video =
    document.getElementById("video");

const estado =
    document.getElementById("estado");

const resultado =
    document.getElementById("resultado");


let estudiantes = [];

let matcher = null;

let ultimoEstudiante = null;

let ultimoRegistro = 0;


// Cargar modelos
async function cargarModelos() {

    estado.innerText =
        "Cargando modelos faciales...";

    await faceapi.nets.tinyFaceDetector.loadFromUri(
        "./models"
    );

    await faceapi.nets.faceLandmark68TinyNet.loadFromUri(
        "./models"
    );

    await faceapi.nets.faceRecognitionNet.loadFromUri(
        "./models"
    );

}


// Obtener estudiantes desde PHP
async function cargarEstudiantes() {

    const respuesta =
        await fetch(
            "obtener_estudiantes.php"
        );

    estudiantes =
        await respuesta.json();


    const etiquetas = [];


    for (
        const estudiante
        of estudiantes
    ) {

        if (!estudiante.descriptor) {
            continue;
        }


        const descriptor =
            new Float32Array(
                JSON.parse(
                    estudiante.descriptor
                )
            );


        const descriptors = [
            descriptor
        ];


        etiquetas.push(
            new faceapi.LabeledFaceDescriptors(
                estudiante.id.toString(),
                descriptors
            )
        );

    }


    if (etiquetas.length === 0) {

        estado.innerText =
            "No hay rostros registrados.";

        return false;

    }


    matcher =
        new faceapi.FaceMatcher(
            etiquetas,
            0.55
        );


    return true;

}


// Activar cámara
async function iniciarCamara() {

    try {

        const stream =
            await navigator.mediaDevices
                .getUserMedia({
                    video: true,
                    audio: false
                });


        video.srcObject = stream;


    } catch (error) {

        console.error(error);

        estado.innerText =
            "No se pudo acceder a la cámara.";

    }

}


// Detectar y reconocer
video.addEventListener(
    "play",
    () => {

        const canvas =
            faceapi.createCanvasFromMedia(
                video
            );


        document
            .querySelector(".camara")
            .append(canvas);


        const displaySize = {

            width: video.width,

            height: video.height

        };


        faceapi.matchDimensions(
            canvas,
            displaySize
        );


        setInterval(
            async () => {

                if (!matcher) {
                    return;
                }


                const deteccion =
                    await faceapi
                        .detectSingleFace(
                            video,
                            new faceapi.TinyFaceDetectorOptions()
                        )
                        .withFaceLandmarks(true)
                        .withFaceDescriptor();


                canvas
                    .getContext("2d")
                    .clearRect(
                        0,
                        0,
                        canvas.width,
                        canvas.height
                    );


                if (!deteccion) {

                    estado.innerText =
                        "Buscando rostro...";

                    resultado.innerText = "";

                    return;

                }


                const redimensionada =
                    faceapi.resizeResults(
                        deteccion,
                        displaySize
                    );


                faceapi.draw.drawDetections(
                    canvas,
                    redimensionada
                );


                const mejorCoincidencia =
                    matcher.findBestMatch(
                        deteccion.descriptor
                    );


                if (
                    mejorCoincidencia.label ===
                    "unknown"
                ) {

                    estado.innerText =
                        "Rostro no registrado.";

                    resultado.innerText = "";

                    return;

                }


                const estudiante =
                    estudiantes.find(
                        e =>
                        e.id.toString() ===
                        mejorCoincidencia.label
                    );


                if (!estudiante) {
                    return;
                }


                estado.innerText =
                    "Rostro identificado";


                resultado.innerHTML =

                    "<strong>" +
                    estudiante.nombre +
                    "</strong><br>" +

                    "Grado: " +
                    estudiante.grado +
                    "<br>" +

                    "Coincidencia: " +
                    mejorCoincidencia.distance
                        .toFixed(3);


                const ahora =
                    Date.now();


                if (
                    ultimoEstudiante ===
                    estudiante.id &&
                    ahora - ultimoRegistro <
                    10000
                ) {

                    return;

                }


                ultimoEstudiante =
                    estudiante.id;

                ultimoRegistro =
                    ahora;


                registrarAsistencia(
                    estudiante.id
                );


            },
            500
        );

    }
);


// Registrar asistencia
async function registrarAsistencia(
    estudianteId
) {

    try {

        const respuesta =
            await fetch(
                "registrar_asistencia.php",
                {

                    method: "POST",

                    headers: {
                        "Content-Type":
                            "application/json"
                    },

                    body: JSON.stringify({

                        estudiante_id:
                            estudianteId

                    })

                }
            );


        const datos =
            await respuesta.json();


        if (datos.exito) {

            resultado.innerHTML +=
                "<br><strong>" +
                datos.mensaje +
                "</strong>" +
                "<br>" +
                datos.fecha +
                " " +
                datos.hora;

        } else {

            resultado.innerHTML +=
                "<br>" +
                datos.mensaje;

        }

    } catch (error) {

        console.error(error);

    }

}


// Inicio
async function iniciar() {

    try {

        await cargarModelos();

        const hayEstudiantes =
            await cargarEstudiantes();


        if (!hayEstudiantes) {
            return;
        }


        await iniciarCamara();


        estado.innerText =
            "Sistema listo. Coloque el rostro frente a la cámara.";

    } catch (error) {

        console.error(error);

        estado.innerText =
            "Error al iniciar el sistema.";

    }

}


iniciar();