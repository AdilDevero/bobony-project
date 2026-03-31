<?php
require 'config.php';

$conn->query("ALTER TABLE staff CHANGE reset_token reset_token_hash VARCHAR(64) NULL");

echo "Database column renamed successfully.\n";
