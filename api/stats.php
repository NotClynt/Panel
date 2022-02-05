<?php

include "../includes/db.php";


$users = $mysqli->query("SELECT COUNT(*) FROM users");
$users = $users->fetch_assoc();
$users = $users['COUNT(*)'];

echo $users;
