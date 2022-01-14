<?php

include "../includes/db.php";

$motd = $mysqli->query("SELECT motd FROM cheat");
$motd = $motd->fetch_assoc();
$motd = $motd['motd'];

$version = $mysqli->query("SELECT version FROM cheat");
$version = $version->fetch_assoc();
$version = $version['version'];

$users = $mysqli->query("SELECT COUNT(*) FROM users");
$users = $users->fetch_assoc();
$users = $users['COUNT(*)'];

$scripts = $mysqli->query("SELECT COUNT(*) FROM scripts");
$scripts = $scripts->fetch_assoc();
$scripts = $scripts['COUNT(*)'];

$themes = $mysqli->query("SELECT COUNT(*) FROM themes");
$themes = $themes->fetch_assoc();
$themes = $themes['COUNT(*)'];

$license = $mysqli->query("SELECT COUNT(*) FROM license");
$license = $license->fetch_assoc();
$license = $license['COUNT(*)'];

echo json_encode(array(
    'motd' => $motd,
    'version' => $version,
    'users' => $users,
    'scripts' => $scripts,
    'themes' => $themes,
    'license' => $license
));



