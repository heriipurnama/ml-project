import sys
import numpy as np
from tensorflow.keras.models import load_model
from tensorflow.keras.preprocessing import image

def main():
    if len(sys.argv) < 2:
        print("No image path provided", file=sys.stderr)
        sys.exit(1)

    img_path = sys.argv[1]

    # Load model
    model = load_model('model.h5')

    # Load dan preprocess gambar sesuai input model (64x64)
    img = image.load_img(img_path, target_size=(64, 64))
    x = image.img_to_array(img)
    x = x / 255.0  # normalisasi pixel
    x = np.expand_dims(x, axis=0)  # bentuk batch (1, 64, 64, 3)

    # Prediksi
    pred = model.predict(x)

    # Output model sigmoid -> probabilitas kelas 1
    predicted_class = 1 if pred[0][0] >= 0.5 else 0

    print(predicted_class)

if __name__ == "__main__":
    main()
