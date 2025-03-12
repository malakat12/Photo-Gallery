<?php
require("../../models/Photo.php");

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: POST, OPTIONS"); 
header("Access-Control-Allow-Headers: Content-Type");

$photo = new Photo($conn,null,null,null,null,null,null); 

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["error" => "Invalid request method"]);
    exit;
}

if (!isset($_POST['id'], $_POST['title'], $_POST['tags'], $_POST['description'])) {
    echo json_encode(["error" => "Missing required fields"]);
    exit;
}

$id = intval($_POST["id"]);
$title = htmlspecialchars(trim($_POST["title"]));
$description = htmlspecialchars(trim($_POST["description"]));
$tags = htmlspecialchars(trim($_POST["tags"]));

$existingPhoto = $photo->getPhotoById($id);
if (!$existingPhoto) {
    echo json_encode(["error" => "Photo not found"]);
    exit;
}

$uploadDir = __DIR__ . "/../uploads/";
$newImageURL = $existingPhoto["url"]; 

if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] === UPLOAD_ERR_OK) {
    $allowedTypes = ["image/jpeg", "image/png"];
    $fileType = mime_content_type($_FILES["photo"]["tmp_name"]);

    if (!in_array($fileType, $allowedTypes)) {
        echo json_encode(["error" => "Invalid file type. Only JPG and PNG allowed."]);
        exit;
    }

    $oldFilePath = $uploadDir . basename($existingPhoto["url"]);
    if (file_exists($oldFilePath)) {
        unlink($oldFilePath);
    }

    $extension = pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION);
    $newFileName = uniqid() . "." . $extension;
    $newFilePath = $uploadDir . $newFileName;

    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $newFilePath)) {
        $newImageURL = "uploads/" . $newFileName;
    } else {
        echo json_encode(["error" => "Failed to upload new image"]);
        exit;
    }
}

$updateResult = $photo->updatePhoto($id, $title, $description, $tags, $newImageURL);

if ($updateResult) {
    echo json_encode(["success" => "Photo updated successfully!", "url" => $newImageURL]);
} else {
    echo json_encode(["error" => "Failed to update photo"]);
}
?>
