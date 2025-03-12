<?php
require_once __DIR__ . '/../../models/User.php';

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: POST, OPTIONS"); 
header("Access-Control-Allow-Headers: Content-Type, Authorization"); 


if (!isset($_FILES['profile_image']) || $_FILES['profile_image']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['error' => 'No file uploaded or upload error']);
    exit;
}

$allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
$fileType = $_FILES['profile_image']['type'];

if (!in_array($fileType, $allowedTypes)) {
    echo json_encode(['error' => 'Invalid file type. Only JPG, PNG, and GIF allowed.']);
    exit;
}

$maxFileSize = 2 * 1024 * 1024; 
if ($_FILES['profile_image']['size'] > $maxFileSize) {
    echo json_encode(['error' => 'Size > 2MB']);
    exit;
}

$user_id = $_POST['user_id'] ?? null;

if (!$user_id || !User::findById($conn, $user_id)) {
    echo json_encode(['error' => 'Invalid user ID']);
    exit;
}

$uploadDir = __DIR__ . '/../../uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$extension = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
$fileName = "user_{$user_id}_" . time() . ".$extension";
$filePath = $uploadDir . $fileName;

if (!move_uploaded_file($_FILES['profile_image']['tmp_name'], $filePath)) {
    echo json_encode(['error' => 'Failed to save file']);
    exit;
}

$imageUrl = "uploads/$fileName"; 
if (User::updateProfileImage($conn, $user_id, $imageUrl)) {
    echo json_encode(['success' => 'Profile image uploaded successfully', 'image_url' => $imageUrl]);
} else {
    echo json_encode(['error' => 'Database update failed']);
}
?>
