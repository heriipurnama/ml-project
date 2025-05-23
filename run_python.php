<?php
$image_path = "uploads/anjing.jpg";

// Jalankan Python script dengan suppress warning TensorFlow (TF_CPP_MIN_LOG_LEVEL=3)
$command = "TF_CPP_MIN_LOG_LEVEL=3 python3 predict_2.py " . escapeshellarg($image_path) . " 2>&1";
$output = shell_exec($command);
$output = trim($output);

// Ambil angka prediksi terakhir dari output
preg_match_all('/\d+/', $output, $matches_all);
$matches = $matches_all[0];
$output_number = end($matches);

$labels = [
    '0' => 'kucing',
    '1' => 'anjing'
];

$hasil_prediksi = isset($labels[$output_number]) ? $labels[$output_number] : "tidak diketahui";

echo "Prediksi: " . $hasil_prediksi;
?>
