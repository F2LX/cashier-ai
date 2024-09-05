<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="icon" type="image/png" href="{{ asset('image/logo-no-background.png') }}"/>
    <link rel="stylesheet" href="{{ asset('css/products.css') }}">
    <script src="https://kit.fontawesome.com/c8524177bd.js" crossorigin="anonymous"></script>
</head>
<body class="showCart">
    @livewire('products');
</body>
</html>