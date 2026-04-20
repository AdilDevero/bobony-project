<?php
require 'config.php';
$sql = "CREATE TABLE IF NOT EXISTS team_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    role VARCHAR(100) NOT NULL DEFAULT 'Admin RP',
    image VARCHAR(255) NULL,
    link1 VARCHAR(255) NULL,
    link2 VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

if ($conn->query($sql) === TRUE) {
    echo "Table team_members created successfully.";
} else {
    echo "Error creating table: " . $conn->error;
}
?>
