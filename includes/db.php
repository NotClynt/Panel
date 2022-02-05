<?php

$mysqli = mysqli_connect("HOST", "DATABASE USER", "DATABASE PASSWORD", "DATABASE NAME");
if ($mysqli->connect_error) {
    die('Connect Error: ' . $mysqli->connect_errno . ': ' . $mysqli->connect_error);
}
