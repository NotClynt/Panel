<?php
include 'app/require.php';



$user = new UserController();

Session::init();

if (Session::isLogged()) {
    Util::redirect('/');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $error = $user->registerUser($_POST);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<style>
        body,
        html {
            background: rgb(24, 24, 34) !important;
            background: linear-gradient(135deg, rgba(24, 24, 44, 1) 0%, rgba(24, 26, 32, 1) 15%, rgba(24, 25, 36, 1) 75%, rgba(24, 24, 44, 1) 100%);
        }
    </style>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Slap - Register</title>

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
<section id="login">
<a href="">
<img src="https://cdn.discordapp.com/attachments/919533264119677018/927264353504362496/PicsArt_01-02-07.16.51.png" alt="" style="width: 3.5rem;">
</a>
<div class="container h-100">
<div class="row justify-content-center align-items-center h-100">
<div class="col-md-10 col-lg-5">
<div class="login-box" data-aos="fade-up" data-aos-duration="1500">
<h2>Sign up</h2>
<p>Create a new account here.</p>

<?php if (isset($error)) : ?>
			<div class="alert alert-primary" role="alert">
				<?php Util::display($error); ?>
			</div>
		<?php endif; ?>

<form method="POST" id="register-form" action="<?php Util::display($_SERVER['PHP_SELF']); ?>">
<div class="form-input-icon mb-3 mt-4">
<i class="fas fa-user"></i>
<input class="auth-input" type="text" placeholder="Username" name="username" autocomplete="off" minlength="3" required>
</div>
<div class="form-input-icon mb-3">
<i class="fas fa-envelope active"></i>
<input type="password" class="auth-input" placeholder="Password" name="password" minlength="4" autocomplete="off" required>
</div>
<div class="form-input-icon mb-3">
<i class="fas fa-lock"></i>
<input type="password" class="auth-input" placeholder="Confirm password" name="confirmPassword" autocomplete="off" minlength="4" required>
</div>
<div class="form-input-icon">
<i class="fas fa-lock"></i>
<input type="text" class="auth-input" placeholder="invite Code" name="invCode" autocomplete="off" required>
</div>
<div class="mt-3 progress-bg">
<div id="progress-password"></div>
</div>
<input type="hidden" id="g-captcha-response" name="g-captcha-response" />
<button class="button primary d-block mt-3 w-100" id="submit" type="submit" value="submit">Register</button>
</form>

</div>
<p class="text-center bottom-text" data-aos="fade-up" data-aos-duration="2000">Already have an account? <br> <a href="login"><strong>Login</strong></a></p>
</div>
</div>
</div>
<div class="g-recaptcha captcha-bottom" data-sitekey="6Lflxv8dAAAAALpcU3GGxDD_m8NnT4x7hu2qy8VG" data-badge="inline" data-size="invisible" data-callback="setResponse">
</div>
<div class="overlay-top-right"></div>
<div class="overlay-bottom-right"></div>
<div class="overlay-bottom-left"></div>
</section>
</body>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js" integrity="sha384-q2kxQ16AaE6UbzuKqyBE9/u/KzioAlnx2maXQHiDX9d4/zp8Ok3f+M7DPm+Ib6IU" crossorigin="anonymous"></script>
<script src="dashboard/js/bootstrap.js"></script>

<script src="dashboard/js/core.js"></script>

<script src="http://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script> 

<script>
    AOS.init({
        disable: 'mobile',
        once: true,
    });
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
