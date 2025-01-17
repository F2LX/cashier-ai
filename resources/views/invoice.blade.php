<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script> -->
    <script src="https://kit.fontawesome.com/c8524177bd.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/png" href="{{ asset('image/logo-no-background.png') }}"/>
    <link rel="stylesheet" href="{{ asset('css/invoice.css') }}" />
    <title>Invoice</title>
  </head>
  <body> 
    <div class="container">
      <div class="forms-container">
        <div class="signin-signup">
          <form action="#" class="sign-in-form">
            <img src="image/ghost2.png" class="image" alt="" />
          </form>
          <form action="#" class="sign-up-form">
            <img src="image/logo-no-background.png" class="logo2" alt="" id="sign-in-btn">
            <div class="receipt">
              <div class="cust">
                <div class="user">
                  <h2>Customer</h2>
                  <h1>{{ auth()->user()->name }}</h1>
                </div>
                <div class="tgl">
                  <h2>Date</h2>
                  <h1>{{ date('d F Y') }}</h1>
                </div>
                <div class="num">
                  <h2>Invoice ID</h2>
                  <h1>CASHPER01</h1>
                </div>
              </div>
              <div class="judul">
                <div class="Nom">
                  <p>ID.</p>
                </div>
                <div class="Nam">
                  <p>Product</p>
                </div>
                <div class="Quant">
                  <p>Quantity</p>
                </div>
                <div class="pri">
                  <p>Price</p>
                </div>
                <div class="amou">
                  <p>Amount</p>
                </div>
              </div>
                <div class="atas">

                  @php
                      $total=0;
                  @endphp
                  @foreach ($products as $product)
                  <div class="barang">
                    <div class="no">
                      {{ $product['id'] }}
                    </div>
                      <div class="nama">
                        <p> {{ $product['product_name'] }}</p>
                      </div>
                      <div class="qty">
                        <p>{{ $product['quantity'] }}</p>
                      </div>
                      <div class="harga">
                        <p>{{ $product['price'] }}</p>
                      </div>
                      <div class="amount">
                        <p>{{ $product['price'] * $product['quantity'] }}</p>
                      </div>
                      @php
                        $total+=$product['price'] * $product['quantity'];
                      @endphp
                  </div>
                  @endforeach
                 
                </div>
                <div class="bawah">
                    <div class="kiri">
                      <div class="subtotal">
                        <h3>SUBTOTAL</h3>
                      </div>
                      <div class="tax">
                        <h3>TAX</h3>
                      </div>
                      <div class="total">
                        <h3>TOTAL</h3>
                      </div>
                    </div>
                    <div class="kanan">
                      <div class="sub">
                        <h3>{{ $total }}</h3>
                      </div>
                      <div class="tx">
                        <h3>{{ $total *10/100 }}</h3>
                      </div>
                      <div class="ttl">
                        <h3>{{ $total + ($total *10/100) }}</h3>
                      </div>
                      
                    </div>
                    
                </div>
            </div>
          </form>
        </div>
      </div>

      <div class="panels-container">
        
        <div class="panel left-panel">
           
          <div class="content">
            <img src="image/logo-white.png" class="logO" alt="">
            <h3>Invoice</h3>
            <p>
              Congrats, you already completed payment
            </p>
            <br>
            <a class="but1" id="sign-up-btn">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                See Receipt
              </a>
            
          </div>
          <!-- <img src="image/front.png" class="image" alt="" /> -->
        </div>
        <div class="panel right-panel">
          <div class="content">
            <h3 class="htri">Invoice</h3>
            <p>
              It's Time to Check All Your Stuff !! 
            </p>
            <a class="but1" href="">
              <span></span>
              <span></span>
              <span></span>
              <span></span>
              Print Receipt
            </a>
            <a class="but1" href="/reset">
              <span></span>
              <span></span>
              <span></span>
              <span></span>
              Main Page
            </a>
          </div>
          
        </div>
      </div>
    </div>
    <script src="{{ asset('js/invoice.js') }}"></script>
    <script src="{{ asset('js/confetti.js') }}"></script>
    <script src="{{ asset('js/confetti.min.js') }}"></script>
   
    <!-- Confetti  JS-->
    <script>

        // start

        const start = () => {
            setTimeout(function() {
                confetti.start()
            }, 1000); // 1000 is time that after 1 second start the confetti ( 1000 = 1 sec)
        };

        //  Stop

        const stop = () => {
            setTimeout(function() {
                confetti.stop()
            }, 5000); // 5000 is time that after 5 second stop the confetti ( 5000 = 5 sec)
        };

        start();
        stop();
        </script>
    
  </body>
</html>
