<?php

require_once '../app/require.php';

include '../includes/db.php';

require_once '../app/controllers/ResellerController.php';


$user = new UserController();

Session::init();

$username = Session::get("username");

Util::head('Reseller Panel');

$invList = $reseller->getResellerSubCodeArray($username);
$balance = $reseller->getBalance($username);

if (isset($_POST["genCode"])) {
    $reseller->getResellerSubCodeGen($username);

    $webhook = RESELLER_WEBHOOK;
    $embed = array(
            "title" => "New Invite Code",
            "description" => "A new invite code has been generated by reseller $username",
            "color" => 0x00ff00
        );
    $data = array(
            "embeds" => array($embed)
        );
    $options = array(
            'http' => array(
                'header'  => "Content-type: application/json\r\n",
                'method'  => 'POST',
                'content' => json_encode($data)
            )
        );
    // send webhook
    $context  = stream_context_create($options);
    $result = file_get_contents($webhook, false, $context);
}


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
		<div class="col-12 mt-3">
			<div class="rounded p-3 mb-3">
				<form method="POST" action="">
								
					<button name="genCode" type="submit" class="btn btn-outline-primary btn-sm">
						Gen Code
					</button>
				</form>

				<?php
                if ($balance != 0) {
                    echo '<h5>Remaining licenses: ' . $balance . '</h5>';
                } else {
                    echo '<h5>Your balance is empty</h5>';
                }
                ?>

			</div>
		</div>

		<div class="col-12 mb-2">
			<table class="rounded table">

				<thead>
					<tr>
						<th scope="col">Code</th>
						<th scope="col">Created By</th>
					</tr>
				</thead>
				<tbody>
                <?php foreach ($subList as $row) : ?>
                    <tr>
                        <td><?php Util::display($row->code); ?></td>
                        <td><?php Util::display($row->createdBy); ?></td>
                    </tr>
                <?php endforeach; ?>
				</tbody>

			</table>

		</div>
	</div>

</div>

<?php //Util::footer();?>