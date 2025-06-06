import sys
import numpy as np
from tensorflow.keras.models import load_model
from tensorflow.keras.preprocessing import image

def main():
    img_path = sys.argv[1]
    model = load_model('model.h5')
    img = image.load_img(img_path, target_size=(64, 64))
    x = image.img_to_array(img) / 255.0
    x = np.expand_dims(x, axis=0)
    pred = model.predict(x)
    predicted_class = 1 if pred[0][0] >= 0.5 else 0
    print(predicted_class)  # Hanya print angka prediksi saja

if __name__ == "__main__":
    main()
