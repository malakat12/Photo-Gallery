<?php
require_once __DIR__ . '/../connection/connection.php';
require ("PhotoSkeleton.php");

class Photo extends PhotoSkeleton{
    private $conn;

    public function __construct($conn,$id=null, $user_id, $title,$description,$tags, $url) {
        parent::__construct($id, $user_id, $title, $description, $tags, $url);
        $this->conn = $conn;
    }

    public function uploadPhoto($user_id, $title, $description, $tags, $url) {
        $sql = "INSERT INTO photos (user_id, title, description, tags, url) VALUES (?, ?, ?, ?,?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("issss", $user_id, $title, $description,$tags, $url);

        if ($stmt->execute()) {
            return ["success" => true, "message" => "Photo uploaded successfully!", "id" => $stmt->insert_id];
        } else {
            return ["success" => false, "message" => "Database error: " . $stmt->error];
        }
    }

    public function getPhotosByUserId($user_id) {
        $sql = "SELECT * FROM photos WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getPhotosById($id) {
        $sql = "SELECT * FROM photos WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }


    public function getPhotoByTags($tags){
        $sql = "SELECT * FROM photos WHERE tags LIKE ?";
        $stmt = $this->conn->prepare($sql);
        $oneTag = "%$tags%";
        $stmt->bind_param("s", $oneTag);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getPhotoByTitle($title){
        $sql = "SELECT * FROM photos WHERE title LIKE ?";
        $stmt = $this->conn->prepare($sql);
        $title = "%$title%";
        $stmt->bind_param("s", $title);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function updatePhoto($id, $title, $description, $tags, $url) {
        $sql = "UPDATE photos SET title = ?, description = ?, tags = ?, url = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssi", $title, $description, $tags, $url, $id);
    
        return $stmt->execute();
    }
    

    public function delete($id) {
        $query = "SELECT url FROM photos WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $photo = $result->fetch_assoc();
        $stmt->close();
        
        if (!$photo) {
            return ["success" => false, "message" => "Photo not found."];
        }
    
        $query = "DELETE FROM photos WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
    
        if ($stmt->execute()) {
            $filePath = __DIR__ . "/../../" . $photo['url']; // Get the file path
            if (file_exists($filePath)) {
                unlink($filePath); 
            }
            return ["success" => true, "message" => "Photo deleted successfully."];
        } else {
            return ["success" => false, "message" => "Database error: " . $stmt->error];
        }
    }
    
}
?>
