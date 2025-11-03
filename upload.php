<?php
require_once 'db.php';

$response = ['success' => false];
$userId = 1; // usuario actual

if (!isset($_FILES['photo']) || !isset($_POST['name'])) {
  $response['message'] = 'Faltan datos.';
  echo json_encode($response);
  exit;
}

$name = $_POST['name'];
$file = $_FILES['photo'];

$targetDir = "uploads/";
if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);

$fileName = uniqid() . "_" . basename($file["name"]);
$targetPath = $targetDir . $fileName;

$allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
if (!in_array($file['type'], $allowedTypes)) {
  $response['message'] = 'Solo se permiten imágenes.';
  echo json_encode($response);
  exit;
}

if (move_uploaded_file($file["tmp_name"], $targetPath)) {
  $stmt = $pdo->prepare("INSERT INTO photos (name, location, user_id) VALUES (?, ?, ?)");
  $stmt->execute([$name, $targetPath, $userId]);

  $response['success'] = true;
  $response['path'] = $targetPath;
} else {
  $response['message'] = 'Error al mover el archivo.';
}

echo json_encode($response);