const video = document.getElementById("video");
const estado = document.getElementById("estado");
const estudiante = document.getElementById("estudiante");
const boton = document.getElementById("registrar");

let rostroActual = null;


// Cargar modelos
async function cargarModelos() {

    estado.innerText =
        "Cargando modelos faciales...";

    await faceapi.nets.tinyFaceDetector.loadFromUri("./models");

    await faceapi.nets.faceLandmark68TinyNet.loadFromUri("./models");

    await faceapi.nets.faceRecognitionNet.loadFromUri("./models");

    estado.innerText =
        "Modelos cargados.";

}


// Activar cámara
async function iniciarCamara() {

    try {

        const stream =
            await navigator.mediaDevices.getUserMedia({
                video: true,
                audio: false
            });

        video.srcObject = stream;

        estado.innerText =
            "Cámara activa. Coloque el rostro frente a la cámara.";

    } catch (error) {

        console.error(error);

        estado.innerText =
            "No se pudo acceder a la cámara.";

    }

}


// Detectar rostro
video.addEventListener("play", () => {

    const canvas =
        faceapi.createCanvasFromMedia(video);

    document.querySelector(".camara")
        .append(canvas);

    const displaySize = {
        width: video.width,
        height: video.height
    };

    faceapi.matchDimensions(
        canvas,
        displaySize
    );


    setInterval(async () => {

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

            rostroActual = null;

            boton.disabled = true;

            estado.innerText =
                "No se detecta ningún rostro.";

            return;

        }


        rostroActual =
            deteccion.descriptor;

        boton.disabled = false;

        estado.innerText =
            "Rostro detectado. Puede registrarlo.";


        const resultado =
            faceapi.resizeResults(
                deteccion,
                displaySize
            );


        faceapi.draw.drawDetections(
            canvas,
            resultado
        );


    }, 500);

});


// Registrar rostro
boton.addEventListener("click", async () => {

    const idEstudiante =
        estudiante.value;


    if (!idEstudiante) {

        alert(
            "Seleccione un estudiante."
        );

        return;

    }


    if (!rostroActual) {

        alert(
            "Primero coloque un rostro frente a la cámara."
        );

        return;

    }


    estado.innerText =
        "Guardando descriptor facial...";


    const descriptor =
        Array.from(rostroActual);


    try {

        const respuesta =
            await fetch(
                "guardar_rostro.php",
                {
                    method: "POST",

                    headers: {
                        "Content-Type":
                            "application/json"
                    },

                    body: JSON.stringify({
                        estudiante_id:
                            idEstudiante,

                        descriptor:
                            descriptor
                    })
                }
            );


        const datos =
            await respuesta.json();


        if (datos.exito) {

            estado.innerText =
                "Rostro registrado correctamente.";

            alert(
                "Rostro registrado correctamente."
            );

        } else {

            estado.innerText =
                datos.mensaje;

        }


    } catch (error) {

        console.error(error);

        estado.innerText =
            "Error al comunicarse con el servidor.";

    }

});


// Inicio
async function iniciar() {

    try {

        await cargarModelos();

        await iniciarCamara();

    } catch (error) {

        console.error(error);

        estado.innerText =
            "Error al cargar los modelos.";

    }

}

iniciar();