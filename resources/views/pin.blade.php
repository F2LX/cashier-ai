<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Enter Pin</title>
    <link rel="stylesheet" href="{{ asset("css/pin.css") }}" />
    <link rel="icon" type="image/png" href="image/logo-no-background.png"/>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/c8524177bd.js" crossorigin="anonymous"></script>
    
  </head>
  <body>
    <img src="image/logo-white.png" class="logo" alt="">
    <div class="container">
      <header>
        <i class="bx bxs-check-shield"></i>
      </header>
      <h4>Enter PIN</h4>
      <form action="/validate-pin" method="POST" id="pin-form">
        <div class="input-field">
          @csrf
          <input type="password" maxlength="1" name="pin1" />
          <input type="password" maxlength="1" name="pin2" disabled />
          <input type="password" maxlength="1" name="pin3" disabled />
          <input type="password" maxlength="1" name="pin4" disabled />
          <input type="password" maxlength="1" name="pin5" disabled />
          <input type="password" maxlength="1" name="pin6" disabled />
        </div>
        <div class="pin-pad">
          <!-- PIN Pad will be generated here -->
        </div>
        <div class="info-pad">
          <p>1</p>
          <p>2</p>
          <p>3</p>
          <p>q</p>
          <p>w</p>
          <p>e</p>
          <p>a</p>
          <p>s</p>
          <p>d</p>
          <p>z</p>
          <p>x</p>
          <p>c</p>
        </div>
        <button id="verifyButton">Submit</button>
      </form>
    </div>
    <script src="{{ asset("js/pin.js") }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('error'))
    <script>
    verifyButton.disabled = false;
        Swal.fire({
            title: "Try again!",
            text: "{{ session('error') }}",
            icon: "error"
        });
    </script>
  @endif
  </body>
</html>
