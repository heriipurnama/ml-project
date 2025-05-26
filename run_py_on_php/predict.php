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

// Bersihkan ANSI escape codes
$output_clean = preg_replace('/\e\[[\d;]*m/', '', $output_raw);
$output_clean = trim($output_clean);

// Ambil angka prediksi terakhir
preg_match('/(\d+)$/', $output_clean, $matches);
$result = $matches[1] ?? null;

if ($result === null) {
    http_response_code(500);
    echo json_encode(['error' => 'Invalid prediction output']);
    exit;
}

// Mapping angka ke label
$label_map = [
    '0' => 'anjing',
    '1' => 'kucing'
];
$result_label = $label_map[$result] ?? 'unknown';

echo json_encode(['result' => $result_label]);
