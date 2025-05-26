package main

import (
	"bytes"
	"fmt"
	"io"
	"mime/multipart"
	"net/http"
	"os"
	"path/filepath"

	"github.com/gin-contrib/cors"
	"github.com/gin-gonic/gin"
)

func main() {
	r := gin.Default()

	// CORS
	r.Use(cors.Default())

	// Serve static files
	r.StaticFile("/", "./index.html")
	r.StaticFile("/index.html", "./index.html")

	// Ensure uploads folder exists
	uploadDir := "./uploads"
	if _, err := os.Stat(uploadDir); os.IsNotExist(err) {
		os.MkdirAll(uploadDir, os.ModePerm)
		fmt.Println("[INFO] Folder uploads dibuat.")
	}

	r.POST("/upload", func(c *gin.Context) {
		file, err := c.FormFile("image")
		if err != nil {
			c.JSON(http.StatusBadRequest, gin.H{"error": "File tidak ditemukan"})
			return
		}

		filepath := filepath.Join(uploadDir, file.Filename)
		if err := c.SaveUploadedFile(file, filepath); err != nil {
			c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal menyimpan file"})
			return
		}

		fmt.Println("[INFO] File diupload:", filepath)

		// Prepare form data for sending to Python API
		body := &bytes.Buffer{}
		writer := multipart.NewWriter(body)
		part, err := writer.CreateFormFile("image", file.Filename)
		if err != nil {
			c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal membuat form data"})
			return
		}

		f, err := os.Open(filepath)
		if err != nil {
			c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal membuka file"})
			return
		}
		defer f.Close()

		io.Copy(part, f)
		writer.Close()

		// Kirim ke Python prediction API
		resp, err := http.Post("http://127.0.0.1:5000/predict", writer.FormDataContentType(), body)
		if err != nil {
			c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal menghubungi API prediksi"})
			os.Remove(filepath)
			return
		}
		defer resp.Body.Close()

		responseData, err := io.ReadAll(resp.Body)
		if err != nil {
			c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal membaca response dari API"})
			os.Remove(filepath)
			return
		}

		// Hapus file setelah selesai
		os.Remove(filepath)

		// Kirim response dari API Python langsung ke klien
		c.Data(resp.StatusCode, "application/json", responseData)
	})

	r.Run(":3000")
}
