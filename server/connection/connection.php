<?php

$host = "localhost";
$user = "root";
$password = "";
$db_name = "photo-gallery";

$conn = new mysqli($host, $user, $password, $db_name);

if ($conn->connect_error){
    http_response_code(400);
    echo "Connection Error";
}


?>