# 🐶🐱 Image Classification App - Cat vs Dog

A simple web application that allows users to upload an image of a cat or a dog, sends it to a Node.js backend, and forwards it to a Python Flask API for prediction. The result is shown on the webpage.


## 🛠️ Tech Stack

- **Frontend**: HTML + JavaScript (vanilla)
- **Backend**: Node.js + Express + Multer + Axios
- **Prediction API**: Python + Flask

## 📂 Folder Structure

```

project-folder/
│
├── server.js               # Node.js backend (handles upload)
├── index.html              # Frontend form
├── uploads/                # Auto-created for temporary storage
├── predict_api/             # Python Flask prediction API
├── package.json
└── README.md

````

---

## 🚀 How to Run the Project

### 1️⃣ Clone Repository

```bash
git clone https://github.com/heriipurnama/ml-project
cd ml-project/slice_endPoint_js
````

---

### 2️⃣ Run Python Flask API

Install Flask (if not yet installed):

```bash
cd python-api
pip install flask
python app.py
```

The Flask API will run on: `http://127.0.0.1:5000`

---

### 3️⃣ Run Node.js Backend

Install dependencies and start server:

```bash
cd ..
npm install
node server.js
```

Node.js server will run on: `http://localhost:3000`

---

### 4️⃣ Open Frontend

Open `index.html` in your browser manually or use Live Server (if using VSCode).

---

## 🌐 Usage

1. Upload an image of a cat or dog.
2. Click "Prediksi".
3. Wait for the result.
4. The predicted label will be shown under "Hasil Prediksi".

---

## ✅ Example Result

* `cat01.jpg` → `Kucing`
* `dog02.jpg` → `Anjing`

(The result is based on filename in this demo — replace with real ML model logic.)

---

## 🧹 Cleanup

Uploaded files are automatically deleted after being sent to the Python API.

---

## 📦 Dependencies

### Node.js

* express
* multer
* axios
* form-data
* cors

### Python

* flask
* werkzeug

---

## 📌 Notes

* Make sure both Node.js and Python servers are running in parallel.
* Adjust CORS and security settings if deploying to production.

---

## 📃 License

MIT License - Feel free to use and modify.
