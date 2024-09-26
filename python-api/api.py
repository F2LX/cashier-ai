from flask import Flask, request, jsonify, send_file
from flask_cors import CORS
from ultralytics import YOLO
import cv2
import numpy as np
import io

app = Flask(__name__)
CORS(app, resources={r"/detect": {"origins": "http://127.0.0.1:8000/products"}})

# CORS(app)  # Enable CORS for the Flask app

# Load YOLO model
model = YOLO('best.pt')

@app.route('/detect', methods=['POST'])
def detect_objects():
    if 'image' not in request.files:
        return jsonify({"error": "No image provided"}), 400

    # Get the image from the request
    image_file = request.files['image'].read()
    np_img = np.frombuffer(image_file, np.uint8)
    img = cv2.imdecode(np_img, cv2.IMREAD_COLOR)

    # Perform YOLO inference
    results = model(img)

    # Draw boxes on the frame
    for result in results:
        boxes = result.boxes
        for box in boxes:
            x1, y1, x2, y2 = map(int, box.xyxy[0])
            confidence = box.conf[0]
            class_id = int(box.cls[0])
            class_name = model.names[class_id]
            label = f"{class_name} ({confidence:.2f})"
            cv2.rectangle(img, (x1, y1), (x2, y2), (0, 255, 0), 2)
            cv2.putText(img, label, (x1, y1 - 10), cv2.FONT_HERSHEY_SIMPLEX, 0.5, (0, 255, 0), 2)

    # Convert processed image to bytes
    _, img_encoded = cv2.imencode('.jpg', img)
    img_bytes = img_encoded.tobytes()
    return send_file(io.BytesIO(img_bytes), mimetype='image/jpeg')

@app.route('/')
def home():
    return "Flask server is running"

@app.route('/test', methods=['GET'])
def test():
    return "Test route works!"


if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)
