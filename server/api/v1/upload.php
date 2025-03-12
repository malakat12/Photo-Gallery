<?php
require("../../models/Photo.php");

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: POST, OPTIONS"); 
header("Access-Control-Allow-Headers: Content-Type");

if (!isset($_POST['user_id'], $_POST['title'], $_POST['description'], $_POST['tags'], $_FILES['photo'])) {
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}
$user_id = intval($_POST['user_id']);
$title = trim($_POST['title']);
$description = trim($_POST['description']);
$tags = trim($_POST['tags']);
$file = $_FILES['photo'];

$allowedFileTypes = ['image/jpeg', 'image/png', 'image/jpg'];
$uploadPic = __DIR__ . "/../uploads/";

if (!in_array($file['type'], $allowedFileTypes)) {
    echo json_encode(['error' => 'Invalid file type. Only JPG, PNG allowed.']);
    exit;
}

if ($file['size'] > 5 * 1024 * 1024) {
    echo json_encode(['error' => ' size > 5MB']);
    exit;
}

$filename = time() . "_" . basename($file['name']);
$filepath = $uploadPic . $filename;
$fileUrl = "uploads/" . $filename;

if (!is_dir(__DIR__ . "/../uploads/")) {
    mkdir(__DIR__ . "/../uploads/", 0777, true);
}
if (move_uploaded_file($file['tmp_name'], $filepath)) {
    $photo = new Photo($conn, $id=null, $user_id, $title, $description, $tags, $fileUrl);
    $result = $photo->uploadPhoto($user_id, $title, $description, $tags, $fileUrl);
    echo json_encode($result);
} else {
    echo json_encode(['error' => 'Failed to upload file']);
}

?>