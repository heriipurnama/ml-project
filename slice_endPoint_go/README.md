# Image Upload & Prediction App (Kucing vs Anjing)

Aplikasi sederhana untuk mengunggah gambar dan memprediksi apakah itu **kucing** atau **anjing** menggunakan model machine learning. Backend dibuat dengan Go (`gin-gonic`), dan AI model diproses menggunakan Python (Flask).

## 🧩 Fitur

- Upload gambar via halaman HTML.
- Endpoint RESTful `/upload` dengan Go (Gin).
- Mengirim gambar ke Flask API (`/predict`) untuk prediksi.
- Hasil prediksi ditampilkan di halaman web.
- Gambar akan dihapus otomatis setelah diproses.

---

## 🛠️ Teknologi

- Go + Gin Gonic (API backend)
- HTML + JS (Frontend)
- Python + Flask + TensorFlow (AI prediction model)

---

## 📂 Struktur Direktori

```

.
├── index.html              # Halaman web upload & hasil prediksi
├── main.go                 # Server utama menggunakan Gin
├── uploads/                # Folder sementara untuk menyimpan gambar
├── predict_api.py          # Python Flask API (ML model inference)
└── model.h5                # Model machine learning (harus disediakan)

````

---

## 🚀 Cara Menjalankan

### 1. Clone Repository

```bash
git clone https://github.com/heriipurnama/ml-project
cd ml-project/slice_endPoint_go
````

### 2. Jalankan Backend Go

```bash
go mod init github.com/heriipurnama/slice_endPoint_go
go mod tidy
go run main.go
```

Server Go berjalan di: `http://localhost:3000`

### 3. Jalankan Flask API (Python)

```bash
pip install -r requirements.txt
python predict_api.py
```

Flask API berjalan di: `http://localhost:5000`

> **Note**: Pastikan file `model.h5` tersedia di lokasi yang tepat.

---

## 🌐 Akses Halaman Web

Buka di browser:

```
http://localhost:3000/
```

Pilih gambar kucing/anjing dan klik **Prediksi**. Hasil akan ditampilkan di bawahnya.

---

## 🧽 Cleanup Otomatis

Gambar yang diunggah akan otomatis dihapus setelah diproses untuk menghindari penumpukan file.

---

## 📝 Lisensi

MIT License - Feel free to use and modify.
