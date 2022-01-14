<?php

include "../includes/db.php";

// select motd from api
$motd = $mysqli->query("SELECT motd FROM cheat");
$motd = $motd->fetch_assoc();
$motd = $motd['motd'];

// select version from api
$version = $mysqli->query("SELECT version FROM cheat");
$version = $version->fetch_assoc();
$version = $version['version'];

// array of info
$response = array(
    'motd' => $motd,
    'version' => $version
);

echo json_encode($response);