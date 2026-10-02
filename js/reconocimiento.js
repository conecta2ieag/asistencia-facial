const video = document.getElementById("video");
const estado = document.getElementById("estado");

async function cargarModelos() {

    estado.innerText = "Cargando modelos faciales...";

    await faceapi.nets.tinyFaceDetector.loadFromUri("./models");

    await faceapi.nets.faceLandmark68TinyNet.loadFromUri("./models");

    estado.innerText = "Modelos cargados. Iniciando cámara...";

}

async function iniciarCamara() {

    try {

        const stream = await navigator.mediaDevices.getUserMedia({
            video: true,
            audio: false
        });

        video.srcObject = stream;

        estado.innerText = "Cámara activa. Buscando rostro...";

    } catch (error) {

        console.error(error);

        estado.innerText =
            "No fue posible acceder a la cámara.";

    }

}

video.addEventListener("play", () => {

    const canvas = faceapi.createCanvasFromMedia(video);

    document.querySelector(".camara").append(canvas);

    const displaySize = {
        width: video.width,
        height: video.height
    };

    faceapi.matchDimensions(canvas, displaySize);

    setInterval(async () => {

        const detecciones =
            await faceapi.detectAllFaces(
                video,
                new faceapi.TinyFaceDetectorOptions()
            );

        const redimensionadas =
            faceapi.resizeResults(
                detecciones,
                displaySize
            );

        canvas
            .getContext("2d")
            .clearRect(
                0,
                0,
                canvas.width,
                canvas.height
            );

        faceapi.draw.drawDetections(
            canvas,
            redimensionadas
        );

        if (detecciones.length > 0) {

            estado.innerText =
                "Rostro detectado";

        } else {

            estado.innerText =
                "Buscando rostro...";

        }

    }, 300);

});

async function iniciar() {

    try {

        await cargarModelos();

        await iniciarCamara();

    } catch (error) {

        console.error(error);

        estado.innerText =
            "Error al cargar el sistema facial.";

    }

}

iniciar();