<?php
require 'config.php';
$res = $conn->query('SELECT * FROM team_members');
while($row = $res->fetch_assoc()) {
    print_r($row);
}
?>
