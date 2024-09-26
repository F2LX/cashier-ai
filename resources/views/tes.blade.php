<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YOLOv8 Object Detection</title>
</head>
<body>

<h1>Object Detection</h1>
<form id="uploadForm" enctype="multipart/form-data">
    <input type="file" name="image" id="imageInput">
    <button type="submit">Upload</button>
</form>

<div id="result"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#uploadForm').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData();
        formData.append('image', $('#imageInput')[0].files[0]);

        $.ajax({
            url: 'http://127.0.0.1:5000/detect',  // Flask API URL
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#result').html('<h2>Detections</h2>');
                response.forEach(function(detection) {
                    $('#result').append('<p>Class: ' + detection.class + ' | Confidence: ' + detection.confidence + '</p>');
                });
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
</script>

</body>
</html>
