<div>
    <div class="container">
        <header>
            <img src="image/logo-no-background.png" class="logo" alt="">
            <div class="icon-cart">
                <div class="box">
                    <input type="text" placeholder="Search...">
                    <a href="#">
                        <i class="fas fa-search"></i>
                    </a>
                </div>
                <div class="icon">
                    <!-- Use wire:click to call the toggleView method -->
                    <img src="{{ $showCamera ? 'image/shelves.png' : 'image/camera.svg' }}" wire:click="toggleView" class="pass-icon" id="pass-icon">
                </div>
            </div>
        </header>

        <!-- Conditionally render the product list or camera based on $showCamera -->
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

        <div class="camera" style="display: {{ $showCamera ? 'block' : 'none' }};">
            <!-- Hide the video element -->
            <video id="video" class="vid" autoplay style="display: none;"></video>
            <!-- Display only the canvas element -->
            <canvas id="canvas" class="overlay"></canvas>
        </div>
        
    </div>

    <div class="cartTab">
        <h1>Shopping Cart</h1>
        <div class="listCart">
            @foreach ($cart as $item)
                @php
                    $product = collect($products)->firstWhere('id', $item['product_id']);
                    $subtotal = $product['price'] * $item['quantity'];
                @endphp
                <div class="item" data-id="{{ $item['product_id'] }}">
                    <div class="image">
                        <img src="{{ $product['image'] }}">
                    </div>
                    <div class="name">{{ $product['name'] }}</div>
                    <div class="price">${{ $product['price'] }}</div>
                    <div class="totalPrice">${{ $subtotal }}</div>
                    <div class="quantity">
                        <span wire:click="changeQuantityCart({{ $item['product_id'] }}, 'minus')" class="minus">-</span>
                        <input wire:input="updateCartQuantity({{ $item['product_id'] }}, $event.target.value)" class="num" type="number" value="{{ $item['quantity'] }}" min="1">
                        <span wire:click="changeQuantityCart({{ $item['product_id'] }}, 'plus')" class="plus">+</span>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="total">
            <p>Total: $<span id="totalAmount">{{ number_format($totalAmount, 2) }}</span></p>
        </div>
        <button class="checkout">Check Out</button>
    </div>

   
    <!-- Include TensorFlow.js -->
    @assets
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>
    @endassets
    @script
    <script>
        Livewire.on('cameraToggledOn', function () {
            init();
        });
// Constants
const MODEL_PATH = 'aimodel/model.json';
const INPUT_WIDTH = 1280;
const INPUT_HEIGHT = 1280;
const SCORE_THRESHOLD = 0.25;
const IOU_THRESHOLD = 0.45;
const MAX_DETECTIONS = 100;
const classNames = [
    'Aji-no-moto', 'Coca-Cola', 'Gery Saluut Coconut',
    'Indofood-Kecap-Manis', 'Indofood-Sambal-Pedas',
    'Indomie-Mi-Goreng', 'Lifebuoy-Total-10', 'Mentos-Mint',
    'Mimi-Kids-Cokelat', 'Popmie-Rasa-Ayam', 'Prochiz-Gold-Cheddar',
    'Sasa-Santan-Kelapa'
];

// DOM Elements
let video, canvas, ctx;

// TensorFlow Model
let model;

// Utility Functions
function log(message) {
    console.log(`[ObjectDetection] ${message}`);
}

function error(message) {
    console.error(`[ObjectDetection] ${message}`);
}

// Model Loading
async function loadModel() {
    log('Loading model...');
    try {
        model = await tf.loadGraphModel(MODEL_PATH);
        log('Model loaded successfully');
        log(`Model input shape: ${model.inputs[0].shape}`);
        log(`Model output shape: ${model.outputs[0].shape}`);
        return model;
    } catch (err) {
        error(`Failed to load model: ${err}`);
        throw err;
    }
}
// Object Detection
async function detectObjects() {
    if (!model) {
        error('Model not loaded');
        return null;
    }

    const inputTensor = tf.tidy(() => {
        return tf.browser.fromPixels(video)
            .resizeBilinear([INPUT_WIDTH, INPUT_HEIGHT])
            .expandDims(0)
            .toFloat()
            .div(255.0);
    });

    try {
        const results = await model.executeAsync(inputTensor);
        log(`Model output shape: ${results[0].shape}`);

        // Get the output tensors
        const outputTensor = results[0];

        // Get the scores, classes, and bounding boxes
        const scores = tf.tidy(() => {
            return outputTensor.slice([0, 0, 0], [1, -1, 1]);
        });
        const classes = tf.tidy(() => {
            return outputTensor.slice([0, 0, 1], [1, -1, 1]);
        });
        const boxes = tf.tidy(() => {
            return outputTensor.slice([0, 0, 2], [1, -1, 4]);
        });

        // Convert the tensors to arrays
        const scoresArray = await scores.array();
        const classesArray = await classes.array();
        const boxesArray = await boxes.array();

        // Create an array to store the detections
        const detections = [];

        // Loop over the scores and classes
        for (let i = 0; i < scoresArray[0].length; i++) {
            // Get the score and class
            const score = scoresArray[0][i];
            const classIndex = classesArray[0][i];

            // Get the bounding box
            const box = boxesArray[0][i];

            // Create a detection object
            const detection = {
                score: score,
                class: classNames[classIndex],
                bbox: box
            };

            // Add the detection to the array
            detections.push(detection);
        }

        // Return the detections
        return detections;
    } catch (err) {
        error(`Error during detection: ${err}`);
        return null;
    } finally {
        tf.dispose(inputTensor);
        results.forEach(tensor => tf.dispose(tensor));
    }
}
// Fungsi untuk mereshape output
function reshapeOutput(flatOutput) {
    const numClasses = classNames.length;
    const boxesPerCell = flatOutput.length / (5 + numClasses);
    log(`Boxes per cell: ${boxesPerCell}`);
    
    let reshapedOutput = [];
    for (let i = 0; i < flatOutput.length; i += (5 + numClasses)) {
        let box = flatOutput.slice(i, i + 5 + numClasses);
        reshapedOutput.push(box);
    }
    
    log(`Reshaped output: ${reshapedOutput.length} boxes, each with ${reshapedOutput[0].length} values`);
    return reshapedOutput;
}


// Rendering
function renderBoxes(detections) {
    if (!detections || detections.length === 0) {
        log('No detections to render');
        return;
    }

    ctx.clearRect(0, 0, canvas.width, canvas.height);

    detections.forEach(detection => {
        const [x, y, width, height] = detection.bbox;
        const score = detection.score;

        if (score > SCORE_THRESHOLD) {
            const x1 = x * canvas.width;
            const y1 = y * canvas.height;
            const w = width * canvas.width;
            const h = height * canvas.height;

            ctx.beginPath();
            ctx.rect(x1, y1, w, h);
            ctx.lineWidth = 2;
            ctx.strokeStyle = 'red';
            ctx.fillStyle = 'red';
            ctx.stroke();
            ctx.fillText(
                `${detection.class}: ${(score * 100).toFixed(1)}%`,
                x1,
                y1 > 10 ? y1 - 10 : 10
            );

            log(`Detected: ${detection.class}, Score: ${(score * 100).toFixed(1)}%, Box: (${x1.toFixed(2)}, ${y1.toFixed(2)}, ${w.toFixed(2)}, ${h.toFixed(2)})`);
        }
    });
}
// Main detection loop
async function detectionLoop() {
    const predictions = await detectObjects();
    if (predictions) {
        renderBoxes(predictions);
    }
    requestAnimationFrame(detectionLoop);
}

// Camera Setup
async function setupCamera() {
    video = document.getElementById('video');
    canvas = document.getElementById('canvas');
    ctx = canvas.getContext('2d');

    const stream = await navigator.mediaDevices.getUserMedia({ video: true });
    video.srcObject = stream;

    return new Promise((resolve) => {
        video.onloadedmetadata = () => {
            video.play();
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            resolve();
        };
    });
}

// Draw video frame
function drawVideoFrame() {
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
    requestAnimationFrame(drawVideoFrame);
}

// Initialize
async function init() {
    try {
        log('Initializing...');
        await setupCamera();
        await loadModel();
        log('Setup complete, starting detection loop');
        drawVideoFrame();
        detectionLoop();
    } catch (err) {
        error(`Initialization failed: ${err}`);
    }
}


// Handle camera toggle
document.getElementById('pass-icon').addEventListener('click', () => {
    const cameraDiv = document.querySelector('.camera');
    const productList = document.querySelector('.listProduct');
    if (cameraDiv.style.display === 'none') {
        cameraDiv.style.display = 'block';
        productList.style.display = 'none';
    } else {
        cameraDiv.style.display = 'none';
        productList.style.display = 'grid';
    }
});
    </script>
    @endscript
</div>
