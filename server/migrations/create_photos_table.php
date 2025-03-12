<?php

require("../connection/connection.php");

$sql = "CREATE TABLE IF NOT EXISTS photos (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            title TEXT NOT NULL,
            description TEXT,
            url VARCHAR(255) NOT NULL,
            CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )"; 
if ($conn->query($sql) === TRUE) {
    echo "Table 'photos' successfully created";
} else {
    echo "Error creating 'photos' Table: " . $conn->error;
}

?>
