<?php


include "../includes/db.php";

require_once '../app/require.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('max_execution_time', 300);

error_reporting(E_ALL);

define('OAUTH2_CLIENT_ID', '931555054392078357'); // add your discord client id
define('OAUTH2_CLIENT_SECRET', 'W7jQFzKTOkq4TRz4_R3Cs76LqbS3vqIC'); // add your discord client secret

$authorizeURL = 'https://discord.com/api/oauth2/authorize';
$tokenURL = 'https://discord.com/api/oauth2/token';
$apiURLBase = 'https://discord.com/api/users/@me';

$user = new UserController();

Session::init();

$uid = Session::get("uid");
$username = Session::get("username");
$admin = Session::get("admin");

if (!isset($_SESSION["login"])) {
    die("You are not logged in. Please <a href='https://domain/panel/login.php'>login</a>.");
}

if (get('code')) {
    $token = apiRequest($tokenURL, array(
    "grant_type" => "authorization_code",
    'client_id' => OAUTH2_CLIENT_ID,
    'client_secret' => OAUTH2_CLIENT_SECRET,
    'redirect_uri' => 'https://domain/panel/api/discord.php',
    'code' => get('code')
  ));
    $logout_token = $token->access_token;
    $_SESSION['access_token'] = $token->access_token;


    header('Location: ' . $_SERVER['PHP_SELF']);
}

if (session('access_token')) {
    $user = apiRequest($apiURLBase);

    $headers = array(
            'Content-Type: application/json',
            'Authorization: Bot OTMxNTU1MDU0MzkyMDc4MzU3.YeGIQA.rh6DpWF9hladlFpccrl59Zj-N4E' // add your bot token here
        );
    $data = array("access_token" => session('access_token'));
    $data_string = json_encode($data);

    $url = "https://discord.com/api/guilds/919531932054872065/members/". $user->id; // replace 919531932054872065 with your guild id
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
    curl_exec($ch);
    curl_close($ch);

    if ($_SESSION["login"]) {
        $stmt = $mysqli->prepare("UPDATE users SET dcid = ? WHERE username = ?");
        $stmt->bind_param("is", $user->id, $username);
        $stmt->execute();
        $stmt->close();

        $role = "919533220641513483";
    } elseif ($_SESSION["admin"]) {
        $stmt = $mysqli->prepare("UPDATE users SET dcid = ? WHERE username = ?");
        $stmt->bind_param("is", $user->id, $username);
        $stmt->execute();
        $stmt->close();

        $role = "919533212030623774";
    }
    // else if ($_SESSION["reseller"]) {
    //     $stmt = $mysqli->prepare("UPDATE users SET dcid = ? WHERE username = ?");
    //     $stmt->bind_param("is", $user->id, $username);
    //     $stmt->execute();
    //     $stmt->close();

    //     $role = "919533220570210354";
    // }



    $url = "https://discord.com/api/guilds/919531932054872065/members/". $user->id. "/roles/{$role}"; // replace 919531932054872065 with your guild id
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
    curl_exec($ch);
    curl_close($ch);
} else {
    die("Not logged into Discord!");
}




// if(get('action') == 'logout') {
//   // This should logout you
//   logout($revokeURL, array(
//     'token' => session('access_token'),
//     'token_type_hint' => 'access_token',
//     'client_id' => OAUTH2_CLIENT_ID,
//     'client_secret' => OAUTH2_CLIENT_SECRET,
//   ));
//   unset($_SESSION['access_token']);
//   header('Location: ' . $_SERVER['PHP_SELF']);
//   die();
// }

if (get('action') == 'logout') {
    $url = "https://discord.com/api/guilds/919531932054872065/members/". $user->id;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Authorization: Bot OTMxNTU1MDU0MzkyMDc4MzU3.YeGIQA.rh6DpWF9hladlFpccrl59Zj-N4E' // add your bot token here
  ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
    curl_exec($ch);
    curl_close($ch);
    unset($_SESSION['access_token']);
    header('Location: ' . $_SERVER['PHP_SELF']);
    die();
}

function apiRequest($url, $post=false, $headers=array())
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);


    if ($post) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
    }

    $headers[] = 'Accept: application/json';

    if (session('access_token')) {
        $headers[] = 'Authorization: Bearer ' . session('access_token');
    }

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    return json_decode($response);
}

function logout($url, $data=array())
{
    $ch = curl_init($url);
    curl_setopt_array($ch, array(
      CURLOPT_POST => true,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
      CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded'),
      CURLOPT_POSTFIELDS => http_build_query($data),
  ));
    $response = curl_exec($ch);
    return json_decode($response);
}

function get($key, $default=null)
{
    return array_key_exists($key, $_GET) ? $_GET[$key] : $default;
}

function session($key, $default=null)
{
    return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;
}


echo '<html>
<head>
  <title>Discord Link Complete</title>
  <link rel="icon" type="image/png" href="https://virty.xyz/img/logo.png">
  <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,700,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css" integrity="sha384-Bfad6CLCknfcloXFOyFnlgtENryhrpZCe29RTifKEixXQZ38WheV+i/6YWSzkz3V" crossorigin="anonymous">
  <link rel="stylesheet" href="https://virty.xyz/panel/bootstrap/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://virty.xyz/panel/assets/css/custom.css" />
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css" integrity="sha384-Bfad6CLCknfcloXFOyFnlgtENryhrpZCe29RTifKEixXQZ38WheV+i/6YWSzkz3V" crossorigin="anonymous">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://virty.xyz/panel/assets/css/main.css" />
  <link rel="stylesheet" href="https://virty.xyz/panel/assets/css/scroll.css" />
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <link href="https://virty.xyz/panel/assets/css/material-dashboard.css?v=2.1.0" rel="stylesheet" />
  <link href="https://virty.xyz/panel/assets/demo/demo.css" rel="stylesheet" />
</head>
  <style>
    body {
      text-align: center;
      padding: 40px 0;
      background: #EBF0F5;
    }
      h1 {
        color: #88B04B;
        font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
        font-weight: 900;
        font-size: 40px;
        margin-bottom: 10px;
      }
      p {
        color: #404F5E;
        font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
        font-size:20px;
        margin: 0;
      }
    i {
      color: #9ABC66;
      font-size: 100px;
      line-height: 200px;
      margin-left:-15px;
    }
    img {
      border-radius: 50%;
    }
    .card {
      background: white;
      padding: 60px;
      border-radius: 4px;
      box-shadow: 0 2px 3px #C8D0D8;
      display: inline-block;
      margin: 0 auto;
    }
  </style>
  <body>
    <div class="card">
    <div style="border-radius: 50%; width:200px; margin:0 auto;">
              <img src="https://cdn.discordapp.com/avatars/' . $user->id . '/' . $user->avatar .'.webp?size=128" alt=" " class="avatar-VxgULZ" aria-hidden="true">
    </div>
      <h1>Success</h1> 
      <p>Sucessfully verified ' . $user->username . '#' . $user->discriminator; echo '</br> You have been added to the Discord Server and your role was given.</br></br><a href="https://virty.xyz/panel/">
        <button class="btn btn-primary btn-block">Go Back</button></p>
    </div>
  </body>
</html>';
