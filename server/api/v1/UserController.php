<?php

    require_once __DIR__ . '/../../models/User.php';
    header("Access-Control-Allow-Origin: http://localhost:5173"); // Explicitly allow your frontend origin


    class UserController{

        static function signUp(){
            global $conn;
            if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
                echo "no entry";
                exit(0);
            }

            $data = json_decode(file_get_contents("php://input"), true);

            if (!isset($data['full_name'], $data['email'], $data['password'])) {
                echo json_encode(['error' => 'Missing required fields']);
                exit;
            }

            $full_name = trim($data['full_name']);

            $email = trim($data['email']);
            
            $password = password_hash($data['password'], PASSWORD_DEFAULT);

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['error' => 'Invalid email format']);
                exit;
            }

            if (User::findByEmail($conn, $email)) {
                echo json_encode(['error' => 'Email Exists']);
                exit;
            }

            $user = new User($conn, null, $full_name, $email, $password);

            if ($user->create()) {
                echo json_encode(['success' => 'User registered successfully']);
            } else {
                echo json_encode(['error' => 'Registration failed']);
            }
        }


        static function login()
        {
            global $conn;
            if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
                http_response_code(200);
                exit();
            }
            $data = json_decode(file_get_contents("php://input"), true);

            if (!isset($data['email'], $data['password'])) {
                echo json_encode(['error' => 'Missing email or password']);
                exit;
            }
        
            $email = trim($data['email']);
            
            $password = $data['password'];


            $user = User::findByEmail($conn, $email);
            
            if (!$user) {
                echo json_encode(['error' => 'No user']);
                exit;
            }
            
            if (!password_verify($password, $user->getPassword())) {
                echo json_encode(['error' => 'Invalid credentials']);
                exit;
            }

            $full_name=$user->getName();
            $logged_email= $user->getEmail();
            echo json_encode([
                'success' => 'Login successful',
                'user' => [
                    'id' => $user->getId(),
                    'full_name' => $full_name,
                    'email' =>$logged_email
                ]
            ]);
        }
    }






?>