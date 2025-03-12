<?php
require("../connection/connection.php");

class Photo {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function uploadPhoto($user_id, $title, $description, $tag, $url) {
        $sql = "INSERT INTO photos (user_id, title, description, tag, url) VALUES (?, ?, ?, ?,?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("issss", $user_id, $title, $description,$tag, $url);

        if ($stmt->execute()) {
            return ["success" => true, "message" => "Photo uploaded successfully!", "id" => $stmt->insert_id];
        } else {
            return ["success" => false, "message" => "Database error: " . $stmt->error];
        }
    }

    public function getAllPhotos() {
        $sql = "SELECT * FROM photos ORDER BY id DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }

    public function getPhotoById($id) {
        $sql = "SELECT * FROM photos WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
?>
