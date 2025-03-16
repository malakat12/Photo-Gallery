<?php
    require_once __DIR__ . '/../connection/connection.php';
    require("UserSkeleton.php");

    class User extends UserSkeleton{

        private $conn;

        public function __construct($conn, $id=null, $full_name, $email,$password) {
            parent::__construct($id, $full_name, $email, $password);
            $this->conn = $conn; 
        }

        
        public function create()
        {
            $query = "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($query);

            $full_name = $this->getName();
            $email = $this->getEmail();
            $password = $this->getPassword();

            $stmt->bind_param('sss', $full_name, $email, $password);

            if (!$stmt->execute()) {
                error_log("Execute failed: " . $stmt->error);
                return false;
            }
        
            return true;
        }

        public static function findByEmail($conn, $email)
        {
            $query = "SELECT * FROM users WHERE email = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $userData = $result->fetch_assoc();
            if ($userData) {
                return new self($conn,$userData['id'], $userData['full_name'], $userData['email'], $userData['password']);
            }

            return null;
        }

    
        public function update()
        {
            $query = "UPDATE users SET full_name = ?, email = ?, password = ? WHERE id = ?";
            $stmt = $this->conn->prepare($query);
    
            $full_name = $this->getName();
            $email = $this->getEmail();
            $password = $this->getPassword();
            $id=$this->getId();
            $stmt->bind_param('sssi', $full_name, $email, $password, $id);    
            return $stmt->execute();
        }
    
        public function delete()
        {
            $query = "DELETE FROM users WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $id=$this->getId();
            $stmt->bind_param('i', $id);
    
            return $stmt->execute();
        }



    }



?>