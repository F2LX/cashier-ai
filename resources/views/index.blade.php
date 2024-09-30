<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script> -->
    <script src="https://kit.fontawesome.com/c8524177bd.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/png" href="{{ asset('image/logo-no-background.png') }}"/>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}" />
    <title>Cashper</title>
  </head>
  <body>
    <div class="container">
      <div class="forms-container">
        <div class="signin-signup">
          <form action="#" class="sign-in-form">
            <img src="{{ asset('image/logo-no-background.png') }}" class="logo" alt="">
            <h2 class="title">Ready to Pay?</h2>
            <p>Just Show Your Smile!</p>
            <img src="{{ asset('image/ghost3.png') }}" class="ghost3" alt="">
            <a class="black but1" href="/pay">
              <span></span>
              <span></span>
              <span></span>
              <span></span>
              PAY NOW
            </a>
            <!-- <p class="social-text">Or Sign in with social platforms</p> -->
          </form>
          <form action="/register-post" id="captureForm" class="sign-up-form" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="imgDataUrl" id="capturedImage">
            <input type="file" id="hiddenFileInput" name="img" style="display:none;"> 
            <img src="{{ asset('image/logo-no-background.png') }}" class="logo2" alt="" id="sign-in-btn">
            <div></div>
            <video id="video" class="vid" autoplay></video>
            <h2 class="titlec">Capture to Register</h2>
            <div class="input-field">
              <i class="fas fa-user"></i>
              <input type="text" placeholder="Full Name" name="name" id="name" required />
              <p class="error-message" id="nameError"></p>
            </div>
            <div class="input-field input-pass">
              <i class="fas fa-envelope"></i>
              <input type="email" placeholder="Email" id="email" name="email" required />
              <p class="error-message" id="emailError"></p>
            </div>
            <div class="input-field">
              <i class="fas fa-lock"></i>
              <input type="password" placeholder="Password" id="pass" name="password" required/>
              <img src="{{ asset('image/pass-hide.png') }}"  onclick="clickPass()" class="pass-icon" id="pass-icon">
              <p class="error-message" id="passError"></p>
            </div>         
            <button type="button" class="mb black but1" id="capture" onclick="captureFormSubmit()">
              <span></span>
              <span></span>
              <span></span>
              <span></span>
              CAPTURE
            </button>
            <p class="social-text">Or Sign up with social platforms</p>
          </form>
        </div>
      </div>

      <div class="panels-container">
        <div class="panel left-panel">
          <div class="content">
            <h3>Face the Future of Payments—Register Now!</h3>
            <p>
              Unlock a faster, more secure way to pay with just a smile.
            </p>
            <a class="but1" id="sign-up-btn">
              <span></span>
              <span></span>
              <span></span>
              <span></span>
              REGISTER
            </a>
            
          </div>
          <img src="{{ asset('image/front.png') }}" class="image" alt="" />
        </div>
        <div class="panel right-panel">
          <div class="content">
            <h3 class="htri">Registered ?</h3>
            <p>
              Perfect! Time to Make Your Payment.
            </p>
            <a class="but1" href="/pay">
              <span></span>
              <span></span>
              <span></span>
              <span></span>
              PAY NOW
            </a>
          </div>
          <img src="{{ asset('image/ghost2.png') }}" class="image" alt="" />
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('error'))
    <script>
        Swal.fire({
            title: "Try again!",
            text: "{{ session('error') }}",
            icon: "error"
        });
    </script>
  @endif
    <script src="{{ asset('js/index.js') }}"></script>
  </body>
</html>
