<?php

require_once '../app/require.php';


$users = $mysqli->query("SELECT COUNT(*) FROM users");
$users = $users->fetch_assoc();
$users = $users['COUNT(*)'];

echo $users;
