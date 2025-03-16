<?php
    require_once __DIR__ . '/../../models/Photo.php';


    class PhotoController{

        static function getAll() {
            global $conn;
        
            if (!isset($_GET['user_id'])) {
                echo json_encode(["error" => "Missing user ID"]);
                exit;
            }
        
            $user_id = intval($_GET['user_id']);
            $photo = new Photo($conn, null, null, null, null, null, null);
            $photos = $photo->getPhotosByUserId($user_id); 
        
            if ($photos) {
                echo json_encode($photos);
            } else {
                echo json_encode(["error" => "No photos found for this user"]);
            }
        }

        static function upload(){
            global $conn;
            $data = json_decode(file_get_contents("php://input"), true);

            file_put_contents("debug_update.txt", json_encode($data) . PHP_EOL, FILE_APPEND);

           
            if (!isset($data['user_id'], $data['title'], $data['description'], $data['tags'], $data['photo'])) {
                echo json_encode(['error' => 'Missing required fields']);
                exit;
            }
            $user_id = intval($data['user_id']);
            $title = trim($data['title']);
            $description = trim($data['description']);
            $tags = trim($data['tags']);
            $base64Image = $data['photo'];
            
            $uploadPic = __DIR__ . "/../uploads/";
            
            if (!is_dir($uploadPic)) {
                mkdir($uploadPic, 0777, true);
            }

            if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches)) {
                $imageType = $matches[1]; 
                $base64Image = substr($base64Image, strpos($base64Image, ',') + 1); 
                $base64Image = base64_decode($base64Image); 
                if ($base64Image === false) {
                    echo json_encode(['error' => 'Invalid Base64 image data']);
                    exit;
                }
            } else {
                echo json_encode(['error' => 'Invalid base64 image format']);
                exit;
            }

            $filename = time() . "_" . uniqid() . "." . $imageType;
            $filepath = $uploadPic . $filename;
            $fileUrl = "uploads/" . $filename;

            if (file_put_contents($filepath, $base64Image)) {
                $photo = new Photo($conn, null, $user_id, $title, $description, $tags, $fileUrl);
                $result = $photo->uploadPhoto($user_id, $title, $description, $tags, $fileUrl);

            if ($result) {
                echo json_encode(["success" => "Photo uploaded", "url" => $fileUrl]);
            } else {
                echo json_encode(["error" => "Database error while saving photo"]);
            }
            } else {
                echo json_encode(['error' => 'Failed to save the image']);
            }
            
        }
    

    static function update(){
        global $conn;
        $data = json_decode(file_get_contents("php://input"), true);

        file_put_contents("debug_update.txt", json_encode($data) . PHP_EOL, FILE_APPEND);
    
       
        $photo = new Photo($conn,null,null,null,null,null,null); 


        if (!isset($data['id'], $data['title'], $data['tags'], $data['description'], $data['photo'])) {
            echo json_encode(["error" => "Missing required fields"]);
            exit;
        }

        $id = intval($data["id"]);
        $title = htmlspecialchars(trim($data["title"]));
        $description = htmlspecialchars(trim($data["description"]));
        $tags = htmlspecialchars(trim($data["tags"]));
        $base64Image = $data["photo"];

        $existingPhoto = $photo->getPhotoById($id);
        if (!$existingPhoto) {
            echo json_encode(["error" => "Photo not found"]);
            exit;
        }

        $uploadDir = __DIR__ . "/../uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches)) {
            $imageType = strtolower($matches[1]);
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
    
            if (!in_array($imageType, $allowedExtensions)) {
                echo json_encode(["error" => "Unsupported image format"]);
                exit;
            }
    
            $base64Image = substr($base64Image, strpos($base64Image, ',') + 1);
            $base64Image = base64_decode($base64Image);
    
            if ($base64Image === false) {
                echo json_encode(["error" => "Invalid Base64 image data"]);
                exit;
            }
    
            $newFileName = time() . "_" . uniqid() . "." . $imageType;
            $newFilePath = $uploadDir . $newFileName;
            $newImageURL = "uploads/" . $newFileName;

            if (file_put_contents($newFilePath, $base64Image)) {
                $oldFilePath = $uploadDir . basename($existingPhoto["url"]);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            } else {
                echo json_encode(["error" => "Failed to save new image"]);
                exit;
            }
        } else {
            echo json_encode(["error" => "Invalid Base64 image format"]);
            exit;
        }
    
        $updateResult = $photo->updatePhoto($id, $title, $description, $tags, $newImageURL);
    
        if ($updateResult) {
            echo json_encode(["success" => "Photo updated successfully!", "url" => $newImageURL]);
        } else {
            echo json_encode(["error" => "Failed to update photo"]);
        }
    }

        static function deletePhoto() {
            global $conn;
            $data = json_decode(file_get_contents("php://input"), true);

            file_put_contents("debug_update.txt", json_encode($data) . PHP_EOL, FILE_APPEND);
   
            if (!isset($data['id'])) {
                echo json_encode(["error" => "Missing photo ID"]);
                exit;
            }
        
            $photo = new Photo($conn, null, null, null, null, null, null);
            $result = $photo->delete(intval($data['id']));
            
            echo json_encode($result);
        }
        
        
    }
?>