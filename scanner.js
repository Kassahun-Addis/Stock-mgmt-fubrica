
// Initialize QuaggaJS scanner
Quagga.init({
    inputStream: {
        name: "Live",
        type: "LiveStream",
        target: document.querySelector('#scanner-container'),
        constraints: {
            width: 640,
            height: 480,
            facingMode: "environment" // or "user" for front camera
        }
    },
    locator: {
        patchSize: "medium",
        halfSample: true
    },
    numOfWorkers: navigator.hardwareConcurrency || 2,
    decoder: {
        readers: ["code_128_reader", "ean_reader", "upc_reader", "code_39_reader", "code_39_vin_reader", "codabar_reader", "i2of5_reader", "2of5_reader", "code_93_reader"]
    },
    locate: true
}, function (err) {
    if (err) {
        console.error(err);
        return;
    }
    Quagga.start();
});

// Event listener for successful barcode scan
Quagga.onDetected(function (data) {
    var code = data.codeResult.code;
    // Send AJAX request to PHP backend
    $.ajax({
        url: 'validateProduct.php',
        method: 'POST',
        data: { code: code },
        success: function (response) {
            // Handle response from PHP backend
            console.log(response);
            // You can parse the response and display product information accordingly
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error(textStatus, errorThrown);
        }
    });
});

// QR code scanner
const videoElement = document.createElement("video");
navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
    .then(function (stream) {
        videoElement.srcObject = stream;
        videoElement.setAttribute("playsinline", true); // required to tell iOS safari we don't want fullscreen
        videoElement.play();
        requestAnimationFrame(tick);
    });

function tick() {
    if (videoElement.readyState === videoElement.HAVE_ENOUGH_DATA) {
        const canvasElement = document.createElement("canvas");
        canvasElement.width = videoElement.videoWidth;
        canvasElement.height = videoElement.videoHeight;
        const canvasContext = canvasElement.getContext("2d");
        canvasContext.drawImage(videoElement, 0, 0, canvasElement.width, canvasElement.height);
        const imageData = canvasContext.getImageData(0, 0, canvasElement.width, canvasElement.height);
        const code = jsQR(imageData.data, imageData.width, imageData.height, {
            inversionAttempts: "dontInvert",
        });
        if (code) {
            // Send AJAX request to PHP backend
            $.ajax({
                url: 'validateProduct.php',
                method: 'POST',
                data: { code: code.data },
                success: function (response) {
                    // Handle response from PHP backend
                    console.log(response);
                    // You can parse the response and display product information accordingly
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error(textStatus, errorThrown);
                }
            });
        }
    }
    requestAnimationFrame(tick);
}
