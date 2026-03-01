<?php
// Password Hash Generator - Run once to generate correct hashes

require 'config.php';

// Define sample credentials
$credentials = [
    ['username' => 'admin', 'password' => 'admin123', 'role' => 'admin', 'email' => 'admin@bobony.com'],
    ['username' => 'moderator1', 'password' => 'mod123', 'role' => 'moderator', 'email' => 'mod1@bobony.com'],
    ['username' => 'staff1', 'password' => 'staff123', 'role' => 'staff', 'email' => 'staff1@bobony.com']
];

echo "<h2>Generated Password Hashes:</h2>";
echo "<table border='1' cellpadding='10'>";
echo "<tr><th>Username</th><th>Password</th><th>Bcrypt Hash</th><th>Role</th></tr>";

foreach ($credentials as $cred) {
    $hash = password_hash($cred['password'], PASSWORD_BCRYPT);
    echo "<tr>";
    echo "<td>" . htmlspecialchars($cred['username']) . "</td>";
    echo "<td>" . htmlspecialchars($cred['password']) . "</td>";
    echo "<td><small>" . htmlspecialchars($hash) . "</small></td>";
    echo "<td>" . htmlspecialchars($cred['role']) . "</td>";
    echo "</tr>";
    
    // Update the database with correct hash
    $escaped_username = $conn->real_escape_string($cred['username']);
    $escaped_hash = $conn->real_escape_string($hash);
    $escaped_email = $conn->real_escape_string($cred['email']);
    $escaped_role = $conn->real_escape_string($cred['role']);
    
    $update_sql = "UPDATE staff SET password = '$escaped_hash', email = '$escaped_email' WHERE username = '$escaped_username'";
    
    if ($conn->query($update_sql)) {
        echo "<tr><td colspan='4' style='background-color: #90EE90;'><strong>{$cred['username']}</strong> - Password updated successfully!</td></tr>";
    } else {
        echo "<tr><td colspan='4' style='background-color: #FFB6C6;'><strong>{$cred['username']}</strong> - Error: " . $conn->error . "</td></tr>";
    }
}

echo "</table>";

echo "<h3>Test Credentials:</h3>";
echo "<ul>";
foreach ($credentials as $cred) {
    echo "<li><strong>Username:</strong> " . htmlspecialchars($cred['username']) . " | <strong>Password:</strong> " . htmlspecialchars($cred['password']) . "</li>";
}
echo "</ul>";

echo "<p><a href='login.php'>← Back to Login</a></p>";
?>
