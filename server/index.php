<?php 
    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: http://localhost:5173");    
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    if ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
        http_response_code(200);
        exit();
    }

    $base_dir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
    $request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $request = str_replace('/api/v1/', '/', $request);

    if (strpos($request, $base_dir) === 0) {
        $request = substr($request, strlen($base_dir));
    }
    if ($request == '') {
        $request = '/';
    }

    $apis = [
        '/login'         => ['controller' => 'UserController', 'method' => 'login'],
        '/signUp'         => ['controller' => 'UserController', 'method' => 'signUp'],
        '/upload'    => ['controller' => 'PhotoController', 'method' => 'upload'],
        '/getAll'         => ['controller' => 'PhotoController', 'method' => 'getAll'],
        '/delete'         => ['controller' => 'PhotoController', 'method' => 'deletePhoto'],
        '/update'         => ['controller' => 'PhotoController', 'method' => 'update']
    ];

    if (isset($apis[$request])) {
        $controllerName = $apis[$request]['controller'];
        $method = $apis[$request]['method'];
        require_once "api/v1/{$controllerName}.php";
        
        $controller = new $controllerName();
        if (method_exists($controller, $method)) {
            header("Access-Control-Allow-Origin: http://localhost:5173");    
            header("Access-Control-Allow-Credentials: true");
            header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
            header("Access-Control-Allow-Headers: Content-Type, Authorization");
            $controller->$method();
        } else {
            echo "Error: Method {$method} not found in {$controllerName}.";
        }
    } else {
        echo json_encode(["error" => "404 Not Found"]);
    }

?>