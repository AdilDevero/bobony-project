<?php
require 'config.php';

$sql = "ALTER TABLE staff 
        ADD COLUMN reset_token_hash VARCHAR(64) NULL NULL AFTER email,
        ADD COLUMN reset_token_expires DATETIME NULL NULL AFTER reset_token_hash;";

if ($conn->query($sql) === TRUE) {
    echo "Successfully added 'reset_token_hash' and 'reset_token_expires' columns to staff table.\n";
} else {
    echo "Error updating table: " . $conn->error . "\n";
}
?>
