<?php

require("../connection/connection.php");

$sql = ("CREATE TABLE IF NOT EXISTS tags(
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL)");

if ($conn->query($sql)==TRUE){
    echo"Table 'tags' successfully created";
} else{
    echo"error creating 'tags' Table". $conn->error;
}

?>