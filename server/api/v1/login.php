<?php
    require_once __DIR__ . '/../../models/User.php';

    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *"); 
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); 
    header("Access-Control-Allow-Headers: Content-Type"); 
    
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        exit(0); 
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
        echo json_encode(['error' => 'Invalid credentials']);
        exit;
    }
    
    $password_data = $user->getPassword();
    
    if (!$user || !password_verify($password, $password_data)) {
        echo json_encode(['error' => 'Invalid credentials']);
        exit;
    }

    $full_name=$user->getName();
    $logged_email= $user->getEmail();
    echo json_encode([
        'success' => 'Login successful',
        'user' => [
            'full_name' => $full_name,
            'email' =>$logged_email
        ]
    ]);
?>
