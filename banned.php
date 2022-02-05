<?php

require_once 'app/require.php';


include 'includes/db.php';

$user = new UserController();

Session::init();

if (!Session::isLogged()) {
    Util::redirect('/login');
}
if (!Session::isBanned()) {
    Util::redirect('/index');
}

$username = Session::get("username");

Util::banCheck();
Util::head($username);

?>

<main class="container mt-2">

	<div class="row">

		<!--Banned message-->
		<div class="col-12 mt-3 mb-2">
			<div class="alert alert-primary" role="alert">
			You were banned for <?php echo $ban_reason ?>
			</div>
		</div>

	</div>

</main>
<?php Util::footer(); ?>