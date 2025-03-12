<?php

require("../connection/connection.php");

$sql = "CREATE TABLE IF NOT EXISTS photo_tags(
            photo_id INT(11) NOT NULL,
            tag_id INT(11) NOT NULL,
            PRIMARY KEY (photo_id, tag_id),
            FOREIGN KEY (photo_id) REFERENCES photos(id) ON DELETE CASCADE,
            FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
            )";

if ($conn->query($sql)==TRUE){
    echo"Table 'photo_tags' successfully created";
} else{
    echo"error creating 'photo_tags' Table". $conn->error;
}

?>