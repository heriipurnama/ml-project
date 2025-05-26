<?php
$upload_dir = __DIR__ . '/uploads/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

$file_tmp = $_FILES['image']['tmp_name'];
$file_name = basename($_FILES['image']['name']);
$target_file = $upload_dir . $file_name;
move_uploaded_file($file_tmp, $target_file);

// Kirim ke endpoint Python
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1:5000/predict');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$cfile = new CURLFile($target_file, mime_content_type($target_file), $file_name);
$data = ['image' => $cfile];
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Hapus file
unlink($target_file);

if ($httpcode !== 200) {
    http_response_code(500);
    echo json_encode(['error' => 'Prediction API failed', 'details' => $response]);
    exit;
}

echo $response;
?>
