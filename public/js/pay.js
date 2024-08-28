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