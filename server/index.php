<?php 
    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *"); 
    header("Access-Control-Allow-Methods: POST, OPTIONS"); 
    header("Access-Control-Allow-Headers: Content-Type");

    // Define your base directory 
    $base_dir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
    $request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $request = str_replace('/api/v1/', '/', $request);

    // Remove the base directory from the request if present
    if (strpos($request, $base_dir) === 0) {
        $request = substr($request, strlen($base_dir));
    }
    // Ensure the request is at least '/'
    if ($request == '') {
        $request = '/';
    }

    $apis = [
        '/login'         => ['controller' => 'UserController', 'method' => 'login'],
        '/signUp'         => ['controller' => 'UserController', 'method' => 'signUp'],
        '/upload'    => ['controller' => 'PhotoController', 'method' => 'upload'],
        '/update'         => ['controller' => 'PhotoController', 'method' => 'update']

    ];

    if (isset($apis[$request])) {
        $controllerName = $apis[$request]['controller'];
        $method = $apis[$request]['method'];
        require_once "api/v1/{$controllerName}.php";
        
        $controller = new $controllerName();
        if (method_exists($controller, $method)) {
            $controller->$method();
        } else {
            echo "Error: Method {$method} not found in {$controllerName}.";
        }
    } else {
        echo "404 Not Found!";
    }

?>