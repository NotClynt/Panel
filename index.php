<?php

require_once 'app/require.php';
require_once 'app/controllers/CheatController.php';

include 'includes/db.php';

$user = new UserController();
$cheat = new CheatController();

Session::init();

if (!Session::isLogged()) {
    Util::redirect('/login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["updatePassword"])) {
        $error = $user->updateUserPass($_POST);
    }

    if (isset($_POST["activateSub"])) {
        $error = $user->activateSub($_POST);
    }
}

if (isset($_POST['reset_discord'])) {
    $sql = "UPDATE users SET dcid = '' WHERE uid = '$uid'";
    $result = mysqli_query($mysqli, $sql);
    Util::redirect('/');
    mysqli_query($mysqli, $sql);
}


if (isset($_POST['reset_hwid'])) {
    $time = time();
    $sql = "SELECT * FROM users WHERE uid = '$uid'";
    $result = mysqli_query($mysqli, $sql);
    $row = mysqli_fetch_assoc($result);
    $last_reset = $row['last_reset'];
    if ($time - $last_reset < 172800) {
        echo '<div class="alert alert-danger" role="alert">
        <strong>Error!</strong>';
        $time = 172800 - ($time - $last_reset);
        $days = floor($time / 86400);
        $hours = floor(($time % 86400) / 3600);
        $minutes = floor(($time % 3600) / 60);
        $seconds = floor($time % 60);
        echo 'You can only reset your hwid once every 48 hours.
        You have ' . $days . ' days, ' . $hours . ' hours, ' . $minutes . ' minutes, and ' . $seconds . ' seconds left.';
        echo '</div>';
    } else {
        $sql = "UPDATE users SET hwid = NULL WHERE uid = '$uid'";
        $result = mysqli_query($mysqli, $sql);
        $sql2 = "UPDATE users SET last_reset = '$time' WHERE uid = '$uid'";
        $result = mysqli_query($mysqli, $sql);
        Util::redirect('/');
        mysqli_query($mysqli, $sql);
        echo '<div class="alert alert-success" role="alert">
        <strong>Success!</strong> Your HWID has been reset.
        </div>';
    }
}

$uid = Session::get("uid");
$admin = Session::get("admin");
$username = Session::get("username");
$sub = $user->getSubStatus();

Util::banCheck();

$hwid = $user->getUserHWID($uid);
$dcid = $user->getUserDCID($uid);
$UserInvList = $user->getUnusedInvites($username);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel - Dashboard</title>

    <link href="dashboard/css/dashboard/dashboard.css" rel="stylesheet">

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <link rel="stylesheet" href="dashboard/css/snackbar.css" />
    <script src=".dashboard/js/snackbar.js"></script>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link rel="shortcut icon" type="image/x-icon" href="https://media.discordapp.net/attachments/919533264119677018/927264353504362496/PicsArt_01-02-07.16.51.png">
</head>

<body>

    <section id="dashboard" class="initial">
        <a href="">
            <img src="https://media.discordapp.net/attachments/919533264119677018/927264353504362496/PicsArt_01-02-07.16.51.png" alt="" style="width: 3.5rem;">
        </a>
        <div class="container h-100">
            <div class="d-flex h-100 justify-content-center align-items-center">
                <div class="row eq-height justify-content-center">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-12">
                                <div class="profile box">
                                    <div class="logout">
                                        <a href="logout"><i class="fas fa-sign-out-alt"></i></a>
                                    </div>
                                    <div class="row justify-content-center text-center">
                                        <div class="col-12">
                                            <div class="content">
                                                <div class="profile-picture">
                                                    <form style="position: relative;" id="form-picture" method="POST" enctype="multipart/form-data">
                                                        <label for="profile-picture-input">
                                                            <img src="https://media.discordapp.net/attachments/919533264119677018/927264353504362496/PicsArt_01-02-07.16.51.png" alt="" class="img-fluid">
                                                            <div class="overlay-picture">
                                                                <i class="fas fa-camera"></i>
                                                            </div>
                                                            <input style="display:none;" name="image" id="profile-picture-input" type="file" accept=".jpg,.jpeg,.png" />
                                                        </label>
                                                        <i onclick="remove_profile_picture()" style="position: absolute; color: rgba(209, 102, 102); z-index: 999999; cursor: pointer;" title="Remove profile picture" class="fas fa-trash-alt"></i>
                                                    </form>
                                                </div>
                                                <span class="username"><?php Util::display($username) ?></span>
                                                <span class="tag"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="personal-info box">
                                    <div class="title">
                                        <h2>Informations</h2>
                                    </div>
                                    <hr>
                                    <div class="info">
                                        <div class="row mb-2 content">
                                            <div class="col-md-4">
                                                <span>Username</span>
                                            </div>
                                            <div class="col-md-8 text-right">
                                                <span><?php Util::display($username) ?></span>
                                            </div>
                                        </div>
                                        <div class="row my-2 content">
                                            <div class="col-md-4">
                                                <span>Status</span>
                                            </div>
                                            <div class="col-md-8 text-right">
                                                <span><?php Util::display($cheat->getCheatData()->status); ?></span>
                                            </div>
                                        </div>
                                        <div class="row mt-2 content">
                                            <div class="col-md-4">
                                                <span>Password</span>
                                                <span onclick="switchCarousel(3)">Change</span>
                                            </div>
                                            <div class="col-md-8 text-right">
                                                <span>•••••••••••</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="hwid-info box px-m">
                                    <div class="row align-items-center">
                                        <div class="col-md-4">
                                            <div class="title">
                                                <span>HWID</span>
                                                <span></span>
                                            </div>
                                        </div>
                                        <div class="col-md-8 text-right">
                                            <div class="info">
                                                <?php
                                                if ($hwid != '') { ?>
                                                    <form method="POST" action="">
                                                        <button class="button primary outline rounded-circle low-padding low-weight medium-size" name="reset_hwid" type="submit" value="submit"><i class="fas fa-microchip"></i> Reset HWID</button>
                                                    </form>
                                                <?php } else { ?>
                                                    <span>NAN</span>

                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="temp-disabled box px-m">
                                    <div class="row align-items-center">
                                        <div class="col-6">
                                            <div class="title">
                                                <span>DISCORD</span>
                                                <?php if ($dcid == '') { ?>
                                                    <span>Unlinked</span> <?php } else { ?> <span style="color:var(--primary)">Linked</span> <?php } ?>
                                            </div>
                                        </div>
                                        <div class="col-6 text-right">
                                            <?php if ($dcid == '') { ?>
                                                <a href="https://discord.com/api/oauth2/authorize?client_id=931555054392078357&redirect_uri=https%3A%2F%2Fyourdomain.com%2Fpanel%2Fapi%2Fdiscod.php&response_type=code&scope=identify%20guilds.join">
                                                    <button class="button primary outline rounded-circle low-padding low-weight medium-size"><i class="fab fa-discord"></i> Link Discord</button>
                                                </a>

                                            <?php } else { ?>
                                                <form method="POST" action="">
                                                    <button class="button primary outline rounded-circle low-padding low-weight medium-size" name="reset_discord" type="submit" value="submit"><i class="fab fa-discord"></i> Unlink Discord</button>
                                                </form>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 ">
                        <div class="box-right">
                            <div id="mainCarousel" class="carousel slide" data-ride="carousel" data-interval="false">
                                <div class="carousel-inner">

                                    <div class="carousel-item active">
                                        <div class="product box mb-3">
                                            <div class="row">
                                                <div class="col-4">
                                                    <h2><?php Util::display(SITE_NAME); ?> <span style="color: var(--primary); font-weight:700; font-size: .9rem;">V<?php Util::display($cheat->getCheatData()->version); ?></span></h2>
                                                </div>
                                                <div class="col-8 text-right">
                                                    <a href="download.php" class="button primary outline rounded-circle low-padding low-weight medium-size">Download</a>
                                                </div>
                                            </div>
                                            <div class="box d-flex justify-content-center mb-0">
                                                <span><?php Util::display($cheat->getCheatData()->motd); ?></span>
                                            </div>
                                        </div>
                                        <div class="messages box mb-3">
                                            <div class="row">
                                                <div class="col-6">
                                                    <h2 class="mb-0">Invites</h2>
                                                </div>
                                                <div class="col-6 text-right">
                                                    <a onclick="switchCarousel(1)" href="#0" class="button primary outline rounded-circle low-padding low-weight medium-size">View</a>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <div class="changelogs box mb-2">
                                            <div class="row">
                                                <div class="col-6">
                                                    <h2 class="mb-0">Changelogs</h2>
                                                </div>
                                                <div class="col-6 text-right">
                                                    <a onclick="switchCarousel(2)" href="#0" class="button primary outline rounded-circle low-padding low-weight medium-size">View</a>
                                                </div>
                                            </div>
                                        </div> -->
                                        <div class="messages box mb-3">
                                            <div class="row">
                                                <div class="col-6">
                                                    <h2 class="mb-0">Subscription</h2>
                                                </div>
                                                <div class="col-6 text-right">
                                                    <a onclick="switchCarousel(4)" href="#0" class="button primary outline rounded-circle low-padding low-weight medium-size">View</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <?php if (Session::isAdmin() == true) : ?>
                                                <div class="col-lg-6">
                                                    <div class="cape box">
                                                        <div class="row">
                                                            <div class="col-6 d-flex align-items-center">
                                                                <div>
                                                                    <h2 class="mb-2" style="position: relative; left:.25rem;">Admin</h2>
                                                                    <a href="admin/" class="button primary outline rounded-circle low-padding low-weight medium-size" style="font-size: .6rem; padding: 0.45rem 2.0rem">Go</a>
                                                                </div>
                                                            </div>
                                                            <div class="col-6 d-flex justify-content-end">
                                                                <img src="https://cdn.discordapp.com/attachments/919533264119677018/927264353504362496/PicsArt_01-02-07.16.51.png" alt="" class="img-fluid" width="65.5rem">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (Session::isReseller() == true) : ?>
                                                <div class="col-lg-6">
                                                    <div class="cape box">
                                                        <div class="row">
                                                            <div class="col-6 d-flex align-items-center">
                                                                <div>
                                                                    <h2 class="mb-2" style="position: relative; left:.25rem;">Reseller</h2>
                                                                    <a href="reseller/" class="button primary outline rounded-circle low-padding low-weight medium-size" style="font-size: .6rem; padding: 0.45rem 2.0rem">Go</a>
                                                                </div>
                                                            </div>
                                                            <div class="col-6 d-flex justify-content-end">
                                                                <img src="https://cdn.discordapp.com/attachments/919533264119677018/927264353504362496/PicsArt_01-02-07.16.51.png" alt="" class="img-fluid" width="65.5rem">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <div class="col-lg-6">
                                                <div class="cape box">
                                                    <div class="row">
                                                        <div class="col-6 d-flex align-items-center">
                                                            <div>
                                                                <h2 class="mb-2" style="position: relative; left:.25rem;">Users</h2>
                                                                <span><?php Util::display($user->getUserCount()); ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-6 d-flex justify-content-end">
                                                            <img src="dashboard/assets/img/capes/1.png" alt="" class="img-fluid" width="45rem">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="cape box">
                                                    <div class="row">
                                                        <div class="col-6 d-flex align-items-center">
                                                            <div>
                                                                <h2 class="mb-2" style="position: relative; left:.25rem;">Last user</h2>
                                                                <span><?php Util::display($user->getNewUser()); ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-6 d-flex justify-content-end">
                                                            <img src="dashboard/assets/img/capes/1.png" alt="" class="img-fluid" width="45rem">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="discount-box" onclick="copy_code('SLAP20')">
                                            <p class="mb-0">20% OFF COUPON: VIRTY20<span style="font-weight: 400; font-size: .8rem">Offer ends 02/15/22</span></p>
                                            <p id="copy-text-p" class="mb-0">Click to copy the coupon code</p>
                                        </div>
                                    </div>


                                    <div class="carousel-item">
                                        <div class="title d-flex align-items-center">
                                            <i onclick="switchCarousel(0)" class="fas fa-chevron-left"></i>
                                            <h2>Invites</h2>
                                        </div>
                                        <div class="changelogs box mb-3">
                                            <div class="title">
                                                <div class="row">
                                                    <div class="col-md-7">
                                                        <div class="d-flex align-items-center">
                                                            <div class="user">
                                                                <h3 class="mb-0">Here are your Invite codes</h3>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="box">
                                                <ul style="padding-left: 0; margin-bottom: 0;">
                                                    <?php foreach ($UserInvList as $row) : ?>
                                                        <li style="list-style-type: none; margin-left: 0; margin-top: .4rem; margin-bottom:.4rem"><?php Util::display($row->code); ?></li>
                                                        <li style="list-style-type: none; margin-left: 0; margin-top: .4rem; margin-bottom:.4rem">
                                                        <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="carousel-item">
                                        <div class="title d-flex align-items-center">
                                            <i onclick="switchCarousel(0)" class="fas fa-chevron-left"></i>
                                            <h2>Changelogs</h2>
                                        </div>
                                        <div class="changelogs box mb-3">
                                            <div class="title">
                                                <div class="row">
                                                    <div class="col-md-7">
                                                        <div class="d-flex align-items-center">
                                                            <div class="user">
                                                                <h3 class="mb-0">Virty Panel</h3>
                                                                <span>• Beta v0.6<span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5 d-flex justify-content-end ">
                                                        <span class="date">09/01/2022</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="box">
                                                <ul style="padding-left: 0; margin-bottom: 0;">
                                                    <li style="list-style-type: none; margin-left: 0; margin-top: .4rem; margin-bottom:.4rem"><span class=added>added</span> New Design</li>
                                                    <li style="list-style-type: none; margin-left: 0; margin-top: .4rem; margin-bottom:.4rem"><span class=added>added</span> More Security</li>
                                                    <li style="list-style-type: none; margin-left: 0; margin-top: .4rem; margin-bottom:.4rem"><span class=fixed>fixed</span> API</li>
                                                    <li style="list-style-type: none; margin-left: 0; margin-top: .4rem; margin-bottom:.4rem"><span class=fixed>fixed</span> HWID Reset</li>
                                                    <li style="list-style-type: none; margin-left: 0; margin-top: .4rem; margin-bottom:.4rem"><span class=fixed>fixed</span> Password change</li>
                                                    <li style="list-style-type: none; margin-left: 0; margin-top: .4rem; margin-bottom:.4rem"><span class=fixed>fixed</span> Discord link</li>
                                                    <li style="list-style-type: none; margin-left: 0; margin-top: .4rem; margin-bottom:.4rem">
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="changelogs box mb-3">
                                            <div class="title">
                                                <div class="row">
                                                    <div class="col-md-7">
                                                        <div class="d-flex align-items-center">
                                                            <div class="user">
                                                                <h3 class="mb-0">Virty Selfbot</h3>
                                                                <span>• v3<span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5 d-flex justify-content-end ">
                                                        <span class="date">08/01/2022</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="box">
                                                <ul style="padding-left: 0; margin-bottom: 0;">
                                                    <li style="list-style-type: none; margin-left: 0; margin-top: .4rem; margin-bottom:.4rem"><span class=added>added</span> Cloud Themes</li>
                                                    <li style="list-style-type: none; margin-left: 0; margin-top: .4rem; margin-bottom:.4rem"><span class=added>added</span> Cloud Scripts</li>
                                                    <li style="list-style-type: none; margin-left: 0; margin-top: .4rem; margin-bottom:.4rem"><span class=added>added</span> Moderation Commands </li>
                                                    <li style="list-style-type: none; margin-left: 0; margin-top: .4rem; margin-bottom:.4rem"><span class=added>added</span> NSFW Commands</li>
                                                    <li style="list-style-type: none; margin-left: 0; margin-top: .4rem; margin-bottom:.4rem"><span class=added>added</span> Func Commands</li>
                                                    <li style="list-style-type: none; margin-left: 0; margin-top: .4rem; margin-bottom:.4rem"><span class=added>added</span> Radi Commands</li>
                                                    <li style="list-style-type: none; margin-left: 0; margin-top: .4rem; margin-bottom:.4rem"><span class=added>added</span> Hack Commands</li>
                                                    <li style="list-style-type: none; margin-left: 0; margin-top: .4rem; margin-bottom:.4rem"><span class=added>added</span> Spam Commands</li>
                                                    <li style="list-style-type: none; margin-left: 0; margin-top: .4rem; margin-bottom:.4rem"><span class=added>added</span> Text commands</li>
                                                    <li style="list-style-type: none; margin-left: 0; margin-top: .4rem; margin-bottom:.4rem"><span class=fixed>fixed</span> API</li>
                                                    <li style="list-style-type: none; margin-left: 0; margin-top: .4rem; margin-bottom:.4rem"><span class=fixed>fixed</span> Colors</li>
                                                    <li style="list-style-type: none; margin-left: 0; margin-top: .4rem; margin-bottom:.4rem"><span class=fixed>fixed</span> Notifications</li>
                                                    <li style="list-style-type: none; margin-left: 0; margin-top: .4rem; margin-bottom:.4rem"><span class=fixed>fixed</span> Updater</li>
                                                    <li style="list-style-type: none; margin-left: 0; margin-top: .4rem; margin-bottom:.4rem">
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="changelogs box mb-3">
                                            <div class="title">
                                                <div class="row">
                                                    <div class="col-md-7">
                                                        <div class="d-flex align-items-center">
                                                            <div class="user">
                                                                <h3 class="mb-0">Release</h3>
                                                                <span>• 1 <span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5 d-flex justify-content-end ">
                                                        <span class="date">01/01/2022</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="box">
                                                <ul style="padding-left: 0; margin-bottom: 0;">
                                                    <li style="list-style-type: none; margin-left: 0; margin-top: .4rem; margin-bottom:.4rem"><span class=added>added</span> Initial release</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="carousel-item">
                                        <div class="title d-flex align-items-center">
                                            <i onclick="switchCarousel(0)" class="fas fa-chevron-left"></i>
                                            <h2>Change Password</h2>
                                        </div>
                                        <div class="box mb-3">
                                            <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                                                <div class="form-input-icon mb-3">
                                                    <i class="fas fa-lock"></i>
                                                    <input class="auth-input" type="password" placeholder="Current password" name="currentPassword" autocomplete="off" required minlength="8" pattern="^(?!^\s.*$)(?!^.*\s$)[ -~]+$" id="current-password">
                                                </div>
                                                <div class="form-input-icon mb-3">
                                                    <i class="fas fa-lock"></i>
                                                    <input class="auth-input" type="password" placeholder="New password" name="newPassword" autocomplete="off" required minlength="8" pattern="^(?!^\s.*$)(?!^.*\s$)[ -~]+$" id="new-password">
                                                </div>
                                                <div class="form-input-icon mb-3">
                                                    <i class="fas fa-lock"></i>
                                                    <input class="auth-input" type="password" placeholder="Confirm password" name="confirmPassword" autocomplete="off" required minlength="8" pattern="^(?!^\s.*$)(?!^.*\s$)[ -~]+$" id="new-password">
                                                </div>
                                                <button class="button primary d-block mt-3 w-100" name="activateSub" type="submit" value="submit">Change</button>
                                        </div>
                                        </form>
                                    </div>

                                    <div class="carousel-item">
                                        <div class="title d-flex align-items-center">
                                            <i onclick="switchCarousel(0)" class="fas fa-chevron-left"></i>
                                            <h2>Subscription</h2>
                                        </div>
                                        <div class="changelogs box mb-3">
                                            <div class="title">
                                                <div class="row">
                                                    <div class="col-md-7">
                                                        <div class="d-flex align-items-center">
                                                            <div class="user">
                                                                <h3 class="mb-0">Subscription</h3>
                                                                <span>
                                                                    <?php
                                                                    if ($sub > 0) {
                                                                        Util::display($sub . ' days');
                                                                    } else {
                                                                        Util::display('0 days');
                                                                    } ?>
                                                                    <span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="box mb-3">
                                                        <?php if ($sub <= 0) : ?>
                                                            <form method="POST" action="<?php Util::display($_SERVER['PHP_SELF']); ?>">
                                                                <div class="form-input-icon mb-3">
                                                                    <i class="fas fa-key"></i>
                                                                    <input class="auth-input" type="password" placeholder="Subscription Code" name="subCode" autocomplete="off" required minlength="8" pattern="^(?!^\s.*$)(?!^.*\s$)[ -~]+$" id="new-password">
                                                                </div>
                                                                <button class="button primary d-block mt-3 w-100" name="activateSub" type="submit" value="submit">Submit</button>
                                                    </div>
                                                    </form>
                                                </div>
                                            <?php endif; ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
    </section>
    <div class="overlay-top-right"></div>
    <div class="overlay-bottom-right"></div>
    <div class="overlay-bottom-left"></div>
    <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
</body>

<script src="https://unpkg.com/@popperjs/core@2" integrity="sha384-q2kxQ16AaE6UbzuKqyBE9/u/KzioAlnx2maXQHiDX9d4/zp8Ok3f+M7DPm+Ib6IU" crossorigin="anonymous"></script>
<script src="dashboard/js/bootstrap.js"></script>

<script src="dashboard/js/core.js"></script>

<script>
    AOS.init({
        disable: 'mobile',
        once: true,
    });
</script>
<script>
    function copy_code(code) {
        navigator.clipboard.writeText(code);
        notify("Coupon code copied to clipboard", 1);
        $("#copy-text-p").text("Coupon code copied");
    }
    $(".discount-box").mouseleave(function() {
        setTimeout(
            function() {
                $("#copy-text-p").text("Click to copy the coupon code");
            }, 150);
    });
</script>

<script>
    $(document).ready(function() {
        $('#mainCarousel').carousel({
            pause: true,
            interval: false
        });
        var need_load = 0;
        if (need_load == 1) {
            setTimeout(function() {
                $("#loading").addClass("active");
                $("#dashboard").addClass("loading");
            }, 200);
            setTimeout(function() {
                $("#loading").removeClass("active");
                $("#loading").addClass("final");
                $("#dashboard").removeClass("loading");
                $("#dashboard").removeClass("initial");
                setTimeout(function() {
                    $("#loading").addClass("d-none");
                }, 100);
            }, 3000);
        } else {
            $("#dashboard").removeClass("initial");
        }
    });

    function switchCarousel(page) {
        $('.carousel').carousel(page);
        if (is_mobile() && page == 4) {
            window.scrollTo(0, document.body.scrollHeight);
        }
    }
</script>

</html>
