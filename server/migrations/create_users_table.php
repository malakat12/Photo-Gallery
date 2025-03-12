<?php

require("../connection/connection.php");

$sql = ("CREATE TABLE IF NOT EXISTS users(
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            full_name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL)");

if ($conn->query($sql)==TRUE){
    echo"Table 'users' successfully created";
} else{
    echo"error creating 'users' Table". $conn->error;
}

?>