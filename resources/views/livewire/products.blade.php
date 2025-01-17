<div>
    <div class="container" style="height: 100vh !important;">
        <header>
            <img src="image/logo-no-background.png" class="logo" alt="">
            <h3>Hi, {{ auth()->user()->name }}</h3>
            <div class="icon-cart">
                <div class="box">
                    <input type="text" placeholder="Search...">
                    <a href="#">
                        <i class="fas fa-search"></i>
                    </a>
                </div>
                <div class="icon">
                    <img src="{{ $showCamera ? 'image/shelves.png' : 'image/camera.svg' }}" wire:click="toggleView" class="pass-icon" id="pass-icon">
                </div>
            </div>
        </header>

        <div class="listProduct" style="display: {{ $showCamera ? 'none' : 'grid' }};">
            @foreach ($products as $product)
                <div class="item" data-id="{{ $product['id'] }}">
                    <img src="{{ $product['image'] }}" alt="">
                    <h2>{{ $product['name'] }}</h2>
                    <div class="price">${{ $product['price'] }}</div>
                    <button wire:click="addToCart({{ $product['id'] }})" class="addCart">Add To Cart</button>
                </div>
            @endforeach
        </div>

        <div class="camera" style="display: {{ $showCamera ? 'block' : 'none' }}; width:100%; height: 80vh !important">
            <video id="video" class="vid" wire:ignore autoplay style="display: none"></video>
            <canvas id="canvas" class="overlay" wire:ignore style="height: 100%"></canvas>
        </div>
        
    </div>

    <div class="cartTab">
        <h1>Shopping Cart</h1>
        <div class="listCart">
            @foreach ($cart as $productId => $item)
    @php
        $product = $products[$productId] ?? null; // Access product by its ID directly
        $subtotal = $product ? $product['price'] * (!is_numeric($item['quantity'])?1:$item['quantity']) : 0;
    @endphp
    @if ($product)
        <div class="item" data-id="{{ $productId }}">
            <div class="image">
                <img src="{{ $product['image'] }}">
            </div>
            <div class="name">{{ $product['name'] }}</div>
            <div class="price">${{ $product['price'] }}</div>
            <div class="totalPrice">${{ $subtotal }}</div>
            <div class="quantity">
                <span wire:click="changeQuantityCart({{ $productId }}, 'minus')" class="minus">-</span>
                <input wire:model.live="cart.{{ $productId }}.quantity" class="num" type="number" min="1" disabled>
                <span wire:click="changeQuantityCart({{ $productId }}, 'plus')" class="plus">+</span>
            </div>
        </div>
    @endif
@endforeach

        </div>
        <div class="total">
            <p>Total: $<span id="totalAmount">{{ number_format($totalAmount, 2) }}</span></p>
            <p>Wallet: ${{ number_format(auth()->user()->wallet,2) }}</p>
        </div>
        <a href="/pin">
            <button class="checkout">Check Out</button>
        </a>
    </div>

    <!-- Include TensorFlow.js -->
    <!-- Include TensorFlow.js and COCO-SSD -->
@assets
<script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>
<script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/coco-ssd"></script>
@endassets

@script
<script>
Livewire.on('cameraToggledOn', function () {
    init();
});
let model;
let video;
let canvas;
let ctx;
let lastSent = 0;  // Waktu terakhir pengiriman data
const cooldownTime = 1500;  // 1.5 detik
const confidenceThreshold = 0.6;  // Set minimal confidence level 60%

async function loadModel() {
    // Load the COCO-SSD model
    model = await cocoSsd.load();
}

async function init() {
    video = document.getElementById('video');
    canvas = document.getElementById('canvas');
    ctx = canvas.getContext('2d');
    await setupWebcam();
    await loadModel();
    detectFrame();
}

async function setupWebcam() {
    const stream = await navigator.mediaDevices.getUserMedia({
        video: true
    });
    video.srcObject = stream;
    video.onloadedmetadata = () => {
        video.play();
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
    };
}

function detectFrame() {
    // Set canvas width and height to match the video dimensions
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
    model.detect(video).then(predictions => {
        drawPredictions(predictions);
        checkAndSend(predictions);  // Cek dan kirim objek jika terdeteksi
        requestAnimationFrame(detectFrame);
    });
}

function drawPredictions(predictions) {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

    predictions.forEach(prediction => {
        const [x, y, width, height] = prediction.bbox;
        const text = `${prediction.class} (${(prediction.score * 100).toFixed(1)}%)`;

        // Draw bounding box
        ctx.beginPath();
        ctx.rect(x, y, width, height);
        ctx.lineWidth = 2;
        ctx.strokeStyle = 'red';
        ctx.fillStyle = 'red';
        ctx.stroke();

        // Draw label background
        ctx.fillStyle = 'red';
        ctx.fillRect(x, y > 10 ? y - 10 : 0, ctx.measureText(text).width + 4, 10);

        // Draw text
        ctx.fillStyle = 'white';
        ctx.fillText(text, x, y > 10 ? y - 5 : 10);
    });
}

// Cek prediksi dan kirim ke server jika terdeteksi
function checkAndSend(predictions) {
    const now = Date.now();

    // Hanya kirim jika cooldown sudah selesai
    if (now - lastSent >= cooldownTime) {
        predictions.forEach(prediction => {
            // Kirim objek yang confidence-nya di atas threshold
            if (prediction.score >= confidenceThreshold && prediction.class != "person") {
                // Livewire.emit('namaMetode', parameter1, parameter2);
                lastSent = now;  // Update waktu pengiriman terakhir
                // console.log("Bottle detected",prediction.score)
                Livewire.dispatch('objdetection',[prediction.class]);
            }
        });
    }
}
</script>
@endscript
</div>
