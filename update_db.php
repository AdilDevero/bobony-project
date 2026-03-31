<?php
require 'config.php';

// Disable warnings if the columns already exist
$conn->query("ALTER TABLE staff ADD COLUMN reset_token VARCHAR(64) NULL AFTER email");
$conn->query("ALTER TABLE staff ADD COLUMN reset_token_expires DATETIME NULL AFTER reset_token");

echo "Database updated successfully.\n";
