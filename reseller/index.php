<?php

require_once '../app/require.php';

require_once '../app/controllers/ResellerController.php';

$user = new UserController();
$reseller = new ResellerController();

Session::init();

$username = Session::get("username");



Util::resellerCheck();
Util::head('Reseller Panel');


?>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">

    <div class="container">

        <a class="navbar-brand" href="<?php echo SITE_URL ?>"><?php Util::display(SITE_NAME); ?></a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav ml-auto">

                <!-- Check if logged in -->
                <?php if (Session::isLogged() == true) : ?>


                    <li class="nav-item">
                        <a class="nav-link" href="<?= SUB_DIR ?>/index">Home</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= SUB_DIR ?>/profile">Profile</a>
                    </li>

                    <!-- Check if admin -->
                    <?php if (Session::isAdmin() == true) : ?>

                        <li class="nav-item">
                            <a class="nav-link" href="<?= SUB_DIR ?>/admin">Admin</a>
                        </li>

                    <?php endif; ?>

                    <?php if (Session::isReseller() == true) : ?>
                    <li class="nav-item">
                            <a class="nav-link" href="<?= SUB_DIR ?>/reseller/index">Home</a>
                       </li>
                       <li class="nav-item">
                           <a class="nav-link" href="<?= SUB_DIR ?>/reseller/codes">License</a>
                       </li>
                       <li class="nav-item">
                           <a class="nav-link" href="<?= SUB_DIR ?>/panel/">Exit</a>
                       </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= SUB_DIR ?>/download.php">Download</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= SUB_DIR ?>/logout">Logout</a>
                    </li>

                <?php else : ?>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= SUB_DIR ?>/login">Login</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= SUB_DIR ?>/register">Register</a>
                    </li>

                <?php endif; ?>

            </ul>

        </div>

    </div>

</nav>
    <div class="main-panel">

<div class="container mt-2">
	<div class="row">
		<!--Total Users-->
		<div class="col-xl-3 col-sm-6 col-xs-12 mt-3">
			<div class="card">
				<div class=" card-body row">
					<div class="col-6 text-center">
						<h3><i class="fas fa-users fa-2x"></i></h3>
					</div>
					<div class="col-6">
						<h3 class="text-center"><?php Util::display($user->getUserCount()); ?></h3>
						<span class="small text-muted text-uppercase">total users</span>
					</div>
				</div>
			</div>
		</div>


		
		<!--Newest registered user-->
		<div class="col-xl-3 col-sm-6 col-xs-12 mt-3">
			<div class="card">
				<div class=" card-body row">
					<div class="col-6 text-center">
						<h3><i class="fas fa-user fa-2x"></i></h3>
					</div>
					<div class="col-6">
						<h3 class="text-center text-truncate"><?php Util::display($user->getNewUser()); ?></h3>
						<span class="small text-muted text-uppercase">latest user</span>
					</div>
				</div>
			</div>
		</div>


		
		<!--Total banned users-->
		<div class="col-xl-3 col-sm-6 col-xs-12 mt-3">
			<div class="card">
				<div class=" card-body row">
					<div class="col-6 text-center">
						<h3><i class="fas fa-user-slash fa-2x"></i></h3>
					</div>
					<div class="col-6">
						<h3 class="text-center"><?php Util::display($user->getBannedUserCount()); ?></h3>
						<span class="small text-muted text-uppercase">banned users</span>
					</div>
				</div>
			</div>
		</div>


		
		<!--Total active sub users-->
		<div class="col-xl-3 col-sm-6 col-xs-12 mt-3">
			<div class="card">
				<div class=" card-body row">
					<div class="col-6 text-center">
						<h3><i class="fas fa-user-clock fa-2x"></i></h3>
					</div>
					<div class="col-6">
						<h3 class="text-center"><?php Util::display($user->getActiveUserCount()); ?></h3>
						<span class="small text-muted text-uppercase">active sub</span>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>

<?php Util::footer(); ?>