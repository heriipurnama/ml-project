const express = require('express');
const multer = require('multer');
const fs = require('fs');
const path = require('path');
const axios = require('axios');
const FormData = require('form-data');
const cors = require('cors');

const app = express();
const port = 3000;

app.use(cors());

// Setup direktori upload
const uploadDir = path.join(__dirname, 'uploads');
if (!fs.existsSync(uploadDir)) {
    fs.mkdirSync(uploadDir, { recursive: true });
    console.log(`[INFO] Upload folder dibuat di: ${uploadDir}`);
}

// Setup multer
const storage = multer.diskStorage({
    destination: (req, file, cb) => {
        cb(null, uploadDir);
    },
    filename: (req, file, cb) => {
        cb(null, file.originalname);
    }
});
const upload = multer({ storage });

// Serve file statis
app.use(express.static(path.join(__dirname)));

app.post('/upload', upload.single('image'), async (req, res) => {
    const filePath = req.file?.path;
    console.log('[INFO] Menerima request POST /upload');

    if (!filePath) {
        console.log('[ERROR] Tidak ada file yang di-upload');
        return res.status(400).json({ error: 'File tidak ditemukan' });
    }

    console.log(`[INFO] File diupload: ${filePath}`);

    try {
        const form = new FormData();
        form.append('image', fs.createReadStream(filePath), {
            filename: req.file.originalname,
            contentType: req.file.mimetype
        });

        console.log(`[INFO] Mengirim gambar ke http://127.0.0.1:5000/predict...`);

        const response = await axios.post('http://127.0.0.1:5000/predict', form, {
            headers: form.getHeaders()
        });

        console.log('[INFO] Response dari server Python:', response.data);

        fs.unlinkSync(filePath);
        console.log('[INFO] File dihapus setelah diproses.');

        res.json(response.data);
    } catch (error) {
        console.error('[ERROR] Gagal memproses prediksi:', error.message);

        if (fs.existsSync(filePath)) {
            fs.unlinkSync(filePath);
            console.log('[INFO] File dihapus karena terjadi error.');
        }

        res.status(500).json({
            error: 'Prediction API failed',
            details: error.response?.data || error.message
        });
    }
});

app.listen(port, () => {
    console.log(`Server listening on http://localhost:${port}`);
});
