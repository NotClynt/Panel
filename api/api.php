<?php

include "../includes/db.php";

case 'login':

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

                        if ($row["hwid"] === NULL) {

                            $sql = "UPDATE users SET hwid = '$hwid' WHERE username = '$username'";
                            $result = $conn->query($sql);

                        }


                        $sql = "INSERT INTO logs (log_user, log_action, log_time) VALUES ('$username', 'Logged in trough api', NOW())";

                        $response = array(
                            'status' => 'success',
                            'uid' => $row["uid"],
                            'username' => $row["username"],
                            'hwid' => $row["hwid"],
                            'admin' => $row["admin"]
                        );

                    }

                } else {
                    $response = array('status' => 'failed', 'error' => 'Invalid password');
                }

            }

        } else {
            $response = array('status' => 'failed', 'error' => 'Invalid API key');
        }

    }

    break;

case 'gen-sub-code':

    $sql = "SELECT apikey FROM cheat";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $apikey = $row["apikey"];

    $username = base64_decode($_GET['user']);
    $password = base64_decode($_GET['pass']);
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

            }

            else {

                $hashedPassword = $row["password"];

                if (password_verify($password, $hashedPassword)) {

                if ($row["admin"] == 1) {

                    $code = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);

                    $sql = "INSERT INTO subscription (code, createdBy, createdAt) VALUES ('$code', '$username', NOW())";
                    $result = $conn->query($sql);

                    $sql = "INSERT INTO logs (log_user, log_action, log_time) VALUES ('$username', 'Created code $code', NOW())";

                    $response = array('status' => 'success', 'code' => $code);

                }
                else {
                    $response = array('status' => 'failed', 'error' => 'You are not an admin');
                }

                } else {
                    $response = array('status' => 'failed', 'error' => 'Invalid password');
                }

            }

    }
    else {
        $response = array('status' => 'failed', 'error' => 'Invalid API key');
    }

    }

    break;

case 'gen-inv-code':

    $sql = "SELECT apikey FROM cheat";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $apikey = $row["apikey"];

    $username = base64_decode($_GET['user']);
    $password = base64_decode($_GET['pass']);
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

            }

            else {

                $hashedPassword = $row["password"];

                if (password_verify($password, $hashedPassword)) {

                if ($row["admin"] == 1) {

                    $code = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);

                    $sql = "INSERT INTO license (code, createdBy, createdAt) VALUES ('$code', '$username', NOW())";
                    $result = $conn->query($sql);

                    $sql = "INSERT INTO logs (log_user, log_action, log_time) VALUES ('$username', 'Created code $code', NOW())";

                    $response = array('status' => 'success', 'code' => $code);

                }
                else {
                    $response = array('status' => 'failed', 'error' => 'You are not an admin');
                }

                } else {
                    $response = array('status' => 'failed', 'error' => 'Invalid password');
                }

            }

    }
    else {
        $response = array('status' => 'failed', 'error' => 'Invalid API key');
    }

    }

    break;


case 'ban-user':

    $sql = "SELECT apikey FROM cheat";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $apikey = $row["apikey"];

    $username = base64_decode($_GET['user']);
    $password = base64_decode($_GET['pass']);
    $usertoban = base64_decode($_GET['usertoban']);
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

            }

            else {

                $hashedPassword = $row["password"];

                if (password_verify($password, $hashedPassword)) {

                if ($row["admin"] == 1) {

                    $sql = "SELECT * FROM users WHERE username = '$usertoban'";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();

                    if ($row) {

                        $sql = "SELECT * FROM ban WHERE username = '$usertoban'";
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();

                        if (!$row) {

                            $sql = "UPDATE users SET banned = 1 WHERE username = '$usertoban'";
                            $result = $conn->query($sql);

                            $sql = "INSERT INTO logs (log_user, log_action, log_time) VALUES ('$username', 'Banned $usertoban', NOW())";

                            $response = array('status' => 'success', 'banned' => $usertoban);

                        }
                        else {
                            $response = array('status' => 'failed', 'error' => 'User is already banned');
                        }

                    }
                    else {
                        $response = array('status' => 'failed', 'error' => 'User does not exist');
                    }

                }
                else {
                    $response = array('status' => 'failed', 'error' => 'You are not an admin');
                }

                } else {
                    $response = array('status' => 'failed', 'error' => 'Invalid password');
                }

            }

    }
    else {
        $response = array('status' => 'failed', 'error' => 'Invalid API key');
    }

    }

    break;

case 'unban-user':

    $sql = "SELECT apikey FROM cheat";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $apikey = $row["apikey"];

    $username = base64_decode($_GET['user']);
    $password = base64_decode($_GET['pass']);
    $usertounban = base64_decode($_GET['usertounban']);
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

            }

            else {

                $hashedPassword = $row["password"];

                if (password_verify($password, $hashedPassword)) {

                if ($row["admin"] == 1) {

                    $sql = "SELECT * FROM users WHERE username = '$usertounban'";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();

                    if ($row) {

                        $sql = "SELECT * FROM ban WHERE username = '$usertounban'";
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();

                        if ($row) {

                            $sql = "UPDATE users SET banned = 0 WHERE username = '$usertounban'";
                            $result = $conn->query($sql);

                            $sql = "INSERT INTO logs (log_user, log_action, log_time) VALUES ('$username', 'Unbanned $usertounban', NOW())";

                            $response = array('status' => 'success', 'unbanned' => $usertounban);

                        }
                        else {
                            $response = array('status' => 'failed', 'error' => 'User is not banned');
                        }

                    }
                    else {
                        $response = array('status' => 'failed', 'error' => 'User does not exist');
                    }

                }
                else {
                    $response = array('status' => 'failed', 'error' => 'You are not an admin');
                }

                } else {
                    $response = array('status' => 'failed', 'error' => 'Invalid password');
                }

            }

    }
    else {
        $response = array('status' => 'failed', 'error' => 'Invalid API key');
    }

    }

    break;

case 'reset-user-hwid':
    
        $sql = "SELECT apikey FROM cheat";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $apikey = $row["apikey"];
    
        $username = base64_decode($_GET['user']);
        $password = base64_decode($_GET['pass']);
        $usertoreset = base64_decode($_GET['usertoreset']);
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
    
                }
    
                else {
    
                    $hashedPassword = $row["password"];
    
                    if (password_verify($password, $hashedPassword)) {
    
                    if ($row["admin"] == 1) {
    
                        $sql = "SELECT * FROM users WHERE username = '$user'";
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();
    
                        if ($row) {
    
                            $sql = "UPDATE users SET hwid = '' WHERE username = '$usertoreset'";
                            $result = $conn->query($sql);
    
                            $sql = "INSERT INTO logs (log_user, log_action, log_time) VALUES ('$username', 'Reset $usertoreset\'s HWID', NOW())";
    
                            $response = array('status' => 'success', 'reset' => $usertoreset);
    
                        }
                        else {
                            $response = array('status' => 'failed', 'error' => 'User does not exist');
                        }
    
                    }
                    else {
                        $response = array('status' => 'failed', 'error' => 'You are not an admin');
                    }
                
                } else {
                    $response = array('status' => 'failed', 'error' => 'Invalid password');
                }

            }
            else {
                $response = array('status' => 'failed', 'error' => 'Invalid API key');
            }

        }

    break;


case 'user-count':

    $sql = "SELECT COUNT(*) FROM users";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $users = $row["COUNT(*)"];

    $response = array('status' => 'success', 'users' => $users);

    break;

case 'ban-cout':
    
        $sql = "SELECT COUNT(*) FROM ban";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $bans = $row["COUNT(*)"];
    
        $response = array('status' => 'success', 'bans' => $bans);
    
        break;

case 'user-list':

    $sql = "SELECT * FROM users";
    $result = $conn->query($sql);

    $users = array();

    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    $response = array('status' => 'success', 'users' => $users);

    break;

case 'last-user':
    
        $sql = "SELECT * FROM users ORDER BY id DESC LIMIT 1";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
    
        $response = array('status' => 'success', 'user' => $row);
    
        break;

case 'motd':

    $sql = "SELECT motd FROM cheat";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $motd = $row["motd"];

    $response = array('status' => 'success', 'motd' => $motd);

    break;

case 'version':

    $sql = "SELECT version FROM cheat";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $version = $row["version"];

    $response = array('status' => 'success', 'version' => $version);

    break;


function xss_clean($data)
{
    return strip_tags($data);
}