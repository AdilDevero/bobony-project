<?php
require 'config.php';

$sql = "CREATE TABLE IF NOT EXISTS reglements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(255) NOT NULL DEFAULT 'General Rules',
    rule_text TEXT NOT NULL,
    ban_time VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

if ($conn->query($sql) === TRUE) {
    echo "Table reglements created successfully\n";
} else {
    echo "Error creating table: " . $conn->error . "\n";
}
?>
