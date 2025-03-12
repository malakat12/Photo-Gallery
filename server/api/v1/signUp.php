<?php
    require_once __DIR__ . '/../../models/User.php';

    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin:*"); 
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); 
    header("Access-Control-Allow-Headers: Content-Type"); 

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
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

?>