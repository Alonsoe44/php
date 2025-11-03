<?php
require_once 'db.php';

// ID del usuario actual (puedes sustituirlo por $_SESSION['user_id'] si usas login)
$userId = 1;

$stmt = $pdo->prepare("SELECT id, name, location FROM photos WHERE user_id = ?");
$stmt->execute([$userId]);
$photos = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($photos);