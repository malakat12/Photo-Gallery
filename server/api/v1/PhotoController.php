<?php
    require_once __DIR__ . '/../../models/Photo.php';


    class PhotoController{
        static function upload(){
            global $conn;

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
            
        }
    

    static function update(){
        global $conn;

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
        }
    }
?>