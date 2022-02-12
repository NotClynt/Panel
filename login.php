<?php
include 'app/require.php';


$user = new UserController();

Session::init();

if (Session::isLogged()) {
    Util::redirect('/');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $error = $user->loginUser($_POST);
}


?>
<html lang="en">
<head>
<style>
        body,
        html {
            background: rgb(24, 24, 28) !important;
        }
    </style>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Virty - Login</title>

<link href="dashboard/css/bootstrap.min.css" rel="stylesheet">
<link href="dashboard/css/config.css" rel="stylesheet">
<link href="dashboard/css/global.css" rel="stylesheet">
<link href="dashboard/css/responsive.css" rel="stylesheet">
<link href="dashboard/css/snackbar.css" rel="stylesheet">
<link href="dashboard/css/auth/custom.css" rel="stylesheet">

<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<link rel="stylesheet" href="dashboard/css/snackbar.css" />
<script src="dashboard/js/snackbar.js"></script>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<link rel="shortcut icon" type="image/x-icon" href="https://cdn.discordapp.com/attachments/919533264119677018/927264353504362496/PicsArt_01-02-07.16.51.png">
</head>

<body>
<div class="container-fluid">
<div class="row">
<div class="col-md-12">
<div class="login-box">
<div class="login-logo">
</div>
<div class="login-box-body">
<section id="login">
<a href="">
<img src="https://cdn.discordapp.com/attachments/919533264119677018/927264353504362496/PicsArt_01-02-07.16.51.png" alt="" style="width: 3.5rem;">
</a>
<div class="container h-100">
<div class="row justify-content-center align-items-center h-100">
<div class="col-md-10 col-lg-5">
<div class="login-box" data-aos="fade-up" data-aos-duration="1500">
<h2>Sign in</h2>
<p>Login to Virty.</p>

<?php if (isset($error)) : ?>
				<div class="alert alert-primary" role="alert">
					<?php Util::display($error); ?>
				</div>
			<?php endif; ?>

<form method="POST" id="login-form" action="<?php Util::display($_SERVER['PHP_SELF']); ?>">
<div class="form-input-icon mb-3 mt-4">
<i class="fas fa-user"></i>
<input class="auth-input" type="text" placeholder="Username" name="username" autocomplete="off" minlength="3" required>
</div>
<div class="form-input-icon mb-3">
<i class="fas fa-lock"></i>
<input class="auth-input" type="password" placeholder="Password" name="password" autocomplete="off" id="password">
</div>
<input type="hidden" id="g-captcha-response" name="g-captcha-response" />
<button class="button primary d-block mt-3 w-100" id="submit" type="submit">Login</button>
</form>

</div>
<p class="text-center bottom-text" data-aos="fade-up" data-aos-duration="2000">Don't have an account? <br> <a href="register"><strong>Sign up</strong></a></p>
</div>
</div>
</div>
<div class="g-recaptcha captcha-bottom" data-sitekey="6Lflxv8dAAAAALpcU3GGxDD_m8NnT4x7hu2qy8VG" data-badge="inline" data-size="invisible" data-callback="setResponse">
</div>
</div>
<div class="overlay-top-right"></div>
<div class="overlay-bottom-right"></div>
<div class="overlay-bottom-left"></div>
</section>
</body>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js" integrity="sha384-q2kxQ16AaE6UbzuKqyBE9/u/KzioAlnx2maXQHiDX9d4/zp8Ok3f+M7DPm+Ib6IU" crossorigin="anonymous"></script>
<script src="dashboard/js/bootstrap.js"></script>

<script src="dashboard/js/core.js"></script>

<script>
    AOS.init({
        disable: 'mobile',
        once: true,
    });
</script>
<script>
    function openModal() {
        $("#modal-login").fadeIn(300);
        $("#modal-login").removeClass("d-none");
        $("#modal-login input").val("");
    }

    function closeModal() {
        $("#modal-login").fadeOut(300);
    }

    function checkForClose() {
        if (!($("#modal-login>.modal-box").is(':hover'))) {
            closeModal();
        }
    }
</script>
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback" async defer></script>
<script>
    var onloadCallback = function() {
        grecaptcha.execute();
    };

    function setResponse($response) {
        document.getElementById('g-captcha-response').value = $response;
    }
</script>
</html>
