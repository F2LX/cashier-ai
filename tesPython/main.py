from flask import Flask, request, jsonify
import cv2
import numpy as np
import torch
from ultralytics import YOLO

app = Flask(__name__)

# Load the YOLOv8 model
model = YOLO('best.pt')

# Function to draw bounding boxes and labels
def draw_boxes(frame, results):
    for result in results:
        boxes = result.boxes
        for box in boxes:
            x1, y1, x2, y2 = map(int, box.xyxy[0])
            confidence = box.conf[0]
            class_id = int(box.cls[0])
            class_name = model.names[class_id]

            cv2.rectangle(frame, (x1, y1), (x2, y2), (0, 255, 0), 2)
            label = f"{class_name} ({confidence:.2f})"
            cv2.putText(frame, label, (x1, y1 - 10), cv2.FONT_HERSHEY_SIMPLEX, 0.5, (0, 255, 0), 2)

@app.route('/detect', methods=['POST'])
def detect():
    # Read the image from the request
    file = request.files['file'].read()
    np_img = np.frombuffer(file, np.uint8)
    frame = cv2.imdecode(np_img, cv2.IMREAD_COLOR)
    
    # Run YOLOv8 model inference
    results = model(frame)
    
    # Draw the bounding boxes
    draw_boxes(frame, results)
    
    # Convert the frame to JPEG
    _, img_encoded = cv2.imencode('.jpg', frame)
    response = img_encoded.tobytes()
    
    return response, 200, {'Content-Type': 'image/jpeg'}

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
