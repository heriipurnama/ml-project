# predict_api.py
from flask import Flask, request, jsonify
import numpy as np
from tensorflow.keras.models import load_model
from tensorflow.keras.preprocessing import image
import os
from werkzeug.utils import secure_filename

app = Flask(__name__)
model = load_model('../model.h5')

@app.route('/predict', methods=['POST'])
def predict():
    if 'image' not in request.files:
        return jsonify({'error': 'No image uploaded'}), 400

    img_file = request.files['image']
    filename = secure_filename(img_file.filename)
    filepath = os.path.join('/tmp', filename)
    img_file.save(filepath)

    try:
        img = image.load_img(filepath, target_size=(64, 64))
        x = image.img_to_array(img) / 255.0
        x = np.expand_dims(x, axis=0)

        pred = model.predict(x)
        predicted_class = 1 if pred[0][0] >= 0.5 else 0
        label_map = {0: 'anjing', 1: 'kucing'}
        return jsonify({'result': label_map[predicted_class]})
    except Exception as e:
        return jsonify({'error': str(e)}), 500
    finally:
        if os.path.exists(filepath):
            os.remove(filepath)

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
