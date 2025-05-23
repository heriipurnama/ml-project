
# Prediksi Gambar Kucing atau Anjing

## Deskripsi
Proyek ini menyediakan aplikasi sederhana untuk melakukan prediksi apakah gambar yang diupload adalah gambar kucing atau anjing menggunakan model machine learning berbasis TensorFlow/Keras yang sudah dilatih.

## Struktur File
- `index.html` : Halaman web untuk upload gambar dan menampilkan hasil prediksi.
- `predict.php` : Endpoint PHP yang menerima upload gambar, menjalankan script Python untuk prediksi, dan mengembalikan hasil prediksi dalam format JSON.
- `predict.py` : Script Python untuk memuat model dan melakukan prediksi gambar.
- `model.h5` : File model machine learning yang sudah dilatih (binary classification).
- `train_model.py` : Script dummy untuk membuat dan menyimpan model `model.h5`.
- `uploads/` : Folder untuk menyimpan gambar yang diupload.
- `run_python.php` : Contoh script PHP untuk menjalankan prediksi langsung via CLI.

## Cara Menjalankan

### 1. Menjalankan via Endpoint Web (index.html)
- Pastikan kamu menjalankan server web yang mendukung PHP, misal menggunakan `php -S localhost:8000` di folder proyek.
- Buka browser dan akses `http://localhost:8000/index.html`.
- Upload gambar kucing atau anjing melalui form upload.
- Hasil prediksi akan muncul di halaman tersebut.

### 2. Menjalankan Prediksi Langsung via CLI PHP
- Pastikan Python 3 dan dependencies sudah terinstall (`tensorflow`, `numpy`, `keras`, dll).
- Pastikan `model.h5` sudah ada di folder proyek.
- Simpan gambar yang ingin diprediksi di folder `uploads/`.
- Jalankan perintah berikut di terminal:
  ```
  php run_python.php
  ```
- Output prediksi akan muncul di terminal.

## Instalasi Dependency Python
Instal semua paket Python yang dibutuhkan dengan perintah:
```
pip install -r requirements.txt
```
Isi file `requirements.txt` bisa seperti berikut:
```
tensorflow==2.19.0
numpy==2.1.3
h5py==3.13.0
Pillow==11.2.1
```

## Contoh predict.py

```python
import sys
import numpy as np
from tensorflow.keras.models import load_model
from tensorflow.keras.preprocessing import image

def main():
    if len(sys.argv) < 2:
        print("No image path provided", file=sys.stderr)
        sys.exit(1)

    img_path = sys.argv[1]
    model = load_model('model.h5')
    img = image.load_img(img_path, target_size=(64, 64))
    x = image.img_to_array(img) / 255.0
    x = np.expand_dims(x, axis=0)
    pred = model.predict(x)
    predicted_class = 1 if pred[0][0] >= 0.5 else 0
    print(predicted_class)

if __name__ == "__main__":
    main()
```

## Contoh predict.php

```php
<?php
header('Content-Type: application/json');

if (!isset($_FILES['image'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No image uploaded']);
    exit;
}

$upload_dir = __DIR__ . '/uploads/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

$file_tmp = $_FILES['image']['tmp_name'];
$file_name = basename($_FILES['image']['name']);
$target_file = $upload_dir . $file_name;

if (!move_uploaded_file($file_tmp, $target_file)) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to save uploaded image']);
    exit;
}

$command = escapeshellcmd("python3 predict.py " . escapeshellarg($target_file));
exec($command . " 2>&1", $output_lines, $return_var);

if ($return_var !== 0) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to run prediction script', 'details' => implode("\n", $output_lines)]);
    exit;
}

$output_raw = implode("\n", $output_lines);
$output_clean = preg_replace('/\e\[[\d;]*m/', '', $output_raw);
$output_clean = trim($output_clean);
preg_match('/(\d+)$/', $output_clean, $matches);
$result = $matches[1] ?? null;

if ($result === null) {
    http_response_code(500);
    echo json_encode(['error' => 'Invalid prediction output']);
    exit;
}

$label_map = [
    '0' => 'anjing',
    '1' => 'kucing'
];
$result_label = $label_map[$result] ?? 'unknown';

echo json_encode(['result' => $result_label]);
?>
```

---

Jika ada pertanyaan atau kendala, silakan tanyakan!

---

**Created by Heri Ipurnama**
