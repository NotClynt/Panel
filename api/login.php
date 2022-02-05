<?php

include "../includes/db.php";

$sql = "SELECT apikey FROM cheat";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$apikey = $row["apikey"];


$username = base64_decode($_GET['user']);

$password = base64_decode($_GET['pass']);

$hwid = base64_decode($_GET['hwid']);

$key = base64_decode($_GET['key']);

if (empty($username) || empty($password) || empty($hwid) || empty($key)) {
    $response = array('status' => 'failed', 'error' => 'Missing arguments');
} else {
    if ($apikey === $key) {
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        if (!$row) {
            $response = array('status' => 'failed', 'error' => 'Invalid username');
        } else {
            $hashedPassword = $row["password"];

            if (password_verify($password, $hashedPassword)) {
                $result = $mysqli->query("SELECT * FROM users WHERE username = '$auser'");
                $row = $result->fetch_assoc();
                $sub = $row["sub"];
                if ($sub < date("Y-m-d")) {
                    $response = array('status' => 'failed', 'error' => 'Your subscription has expired');
                } else {
                    if ($row["hwid"] === null) {
                        $sql = "UPDATE users SET hwid = '$hwid' WHERE username = '$username'";
                        $result = $conn->query($sql);
                    }

                    $response = array(
                        'status' => 'success',
                        'uid' => $row["uid"],
                        'username' => $row["username"],
                        'hwid' => $row["hwid"],
                        'admin' => $row["admin"],
                        'sub' => $row["sub"],
                        'banned' => $row["banned"],
                        'createdAt' => $row["createdAt"]
                    );
                }
            } else {
                $response = array('status' => 'failed', 'error' => 'Invalid password');
            }
        }
    }
}
