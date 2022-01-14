<?php

// Path: panel\api\auth.php

include "../includes/db.php";


$auser = $_GET['user'];
$apass = $_GET['pass'];
$ahwid = $_GET['hwid'];

$result = $mysqli->query("SELECT * FROM users WHERE username = '$auser'");
if ($result->num_rows == 0) {
	echo "User not found";
	exit();
}
$result = $mysqli->query("SELECT * FROM users WHERE username = '$auser' AND banned = 1");
if ($result->num_rows == 1) {
	echo "User banned";
	exit();
}
$result = $mysqli->query("SELECT * FROM users WHERE username = '$auser'");
$row = $result->fetch_assoc();
$hash = $row['password'];
if (!password_verify($apass, $hash)) {
	echo "Wrong password";
	exit();
}

// if password is correct, check if hwid is correct and if hwid is empty update it
$result = $mysqli->query("SELECT * FROM users WHERE username = '$auser'");
$row = $result->fetch_assoc();
$hwid = $row['hwid'];
if ($hwid == '') {
	$mysqli->query("UPDATE users SET hwid = '$ahwid' WHERE username = '$auser'");
} else if ($hwid != $ahwid) {
	echo "Wrong hwid";
	exit();
}

echo "success";
?>