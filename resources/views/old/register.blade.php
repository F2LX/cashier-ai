<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CASHPER</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        #video {
            width: 100%;
            height: auto;
            border: 1px solid #ddd;
            transform: scaleX(-1); /* Flip horizontally */
        }
    </style>
  </head>
  <body>
    <div class="container mt-4">
        <h1 class="text-center mb-4">Webcam Capture</h1>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <video id="video" autoplay></video>
                <form id="captureForm" action="/register-post" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="imgDataUrl" id="capturedImage">
                    <input type="file" id="hiddenFileInput" name="img" style="display:none;">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name:</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="text-center mt-3">
                        <button type="button" class="btn btn-primary" onclick="capture()">Register</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
        const video = document.getElementById('video');

        // Get access to the camera
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                video.srcObject = stream;
            })
            .catch(err => {
                console.error("Error accessing the camera: ", err);
            });

        function capture() {
            const canvas = document.createElement('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            const dataUrl = canvas.toDataURL('image/jpeg');

            // Convert data URL to Blob
            const byteString = atob(dataUrl.split(',')[1]);
            const mimeString = dataUrl.split(',')[0].split(':')[1].split(';')[0];
            const ab = new ArrayBuffer(byteString.length);
            const ia = new Uint8Array(ab);
            for (let i = 0; i < byteString.length; i++) {
                ia[i] = byteString.charCodeAt(i);
            }
            const blob = new Blob([ab], { type: mimeString });

            // Create a File object
            const file = new File([blob], 'captured-image.jpeg', { type: mimeString });

            // Create a DataTransfer object and set the file
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);

            // Get the hidden file input element
            const hiddenFileInput = document.getElementById('hiddenFileInput');
            hiddenFileInput.files = dataTransfer.files;

            // Submit the form
            document.getElementById('captureForm').submit();
        }
    </script>
  </body>
</html>