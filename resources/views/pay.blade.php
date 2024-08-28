<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Face Payment</title>
    <script src="https://kit.fontawesome.com/c8524177bd.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/png" href="{{ asset('image/logo-no-background.png ')}}"/>
    <link rel="stylesheet" href="{{ asset('css/pay.css') }}" />
</head>
<body>
    <img src="{{ asset('image/logo-no-background.png') }}" class="logo" alt="">
    <i class="fa-solid fa-arrow-up fa-2xl arrow bounce2" style="color: #11a3b0;"></i>
    <h1 class="title">Smile to the Camera</h1>
    <form id="captureForm" action="/pay-post" method="post" enctype="multipart/form-data" style="display: flex; flex-direction:column; align-items:center;">
        @csrf
        <video id="video" class="vidd" autoplay></video>
        <input type="hidden" name="imgDataUrl" id="capturedImage">
        <input type="file" id="hiddenFileInput" name="img" style="display:none;">
        <button class="black but1" onclick="capture()" style="border: 0; width: fit-content; background: transparent">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            CAPTURE
        </button>
    </form>
    <script src="{{ asset('js/pay.js') }}"></script>
</body>
</html>