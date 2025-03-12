<?php
    require_once __DIR__ . '/../connection/connection.php';

    class User {

        private $conn;

        public function __construct($conn, $full_name, $email,$password) {
            $this->conn = $conn; 
            $this->full_name = $full_name;
            $this->email=$email;
            $this->password=$password;
        }

        public function create()
        {
            $query = "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($query);
    
            if (!$stmt) {
                error_log("Prepare failed: " . $this->conn->error);
                return false;
            }

            $stmt->bind_param('sss', $this->full_name, $this->email, $this->password);

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
                return new self($conn, $userData['full_name'], $userData['email'], $userData['password']);
            }

            return null;
        }

    
        public function update($id)
        {
            $query = "UPDATE users SET full_name = ?, email = ?, password = ? WHERE id = ?";
            $stmt = $this->conn->prepare($query);
    

            $stmt->bind_param('sssi', $this->full_name, $this->email, $this->password, $id);    
            return $stmt->execute();
        }
    
        public function delete($id)
        {
            $query = "DELETE FROM users WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $id);
    
            return $stmt->execute();
        }



    }



?>