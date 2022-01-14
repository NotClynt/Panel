<?php

require_once '../app/require.php';

include '../includes/db.php';


$user = new UserController;

Session::init();

$username = Session::get("username");

Util::head('Reseller Panel');

function randomCode($int) {

	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$randomString = 'Virty-';
	
	for ($i = 0; $i < $int; $i++) {
		$randomString .= $characters[rand(0, strlen($characters) - 1)];
	}
	
	return $randomString;
}

$sql = "SELECT balance FROM users WHERE username = '$username'";
$result = $mysqli->query($sql);
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$balance = $row["balance"];
	}
}


if (isset($_POST["genCode"])) {
	$sql = "SELECT `balance` FROM `users` WHERE `username` = '$username'";
	$result = $mysqli->query($sql);
	$row = $result->fetch_assoc();
	$balance = $row["balance"];
	if ($balance >= 1) {
		$code = randomCode(8);
		$sql = "INSERT INTO `license` (`code`, `createdBy`) VALUES ('$code', '$username')";
		$result = $mysqli->query($sql);
		$sql = "UPDATE `users` SET `balance` = `balance` - 1 WHERE `username` = '$username'";
		$result = $mysqli->query($sql);
		$sql = "INSERT INTO `logs` (`log_user`, `log_action`, `log_time`) VALUES ('$username', 'Generated license code: $code', NOW())";
		$result = $mysqli->query($sql);

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

		echo '<script>window.location.href = "codes.php";</script>';
	} else {
		echo '<div class="alert alert-danger" role="alert">You don\'t have enough licenses to generate!</div>';
	}
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

                    <?php
                    $sql = "SELECT * FROM users WHERE username = '$username'";
                    $result = $mysqli->query($sql);
                    // if user is reseller then show reseller navbar else redirect to home
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            if ($row["reseller"] == 1) {
                                echo '<li class="nav-item">
                        <a class="nav-link" href="'.SUB_DIR.'/reseller/index">Home</a>
                   </li>
                   <li class="nav-item">
                       <a class="nav-link" href="'.SUB_DIR.'/reseller/codes">License</a>
                   </li>
                   <li class="nav-item">
                       <a class="nav-link" href="'.SUB_DIR.'/index">Back</a>
                   </li>';
                            }
                        }
                    } else {
                        echo '<script>window.location.href = "index.php";</script>';
                    } ?>

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

	<?php
	$sql = "SELECT * FROM users WHERE username = '$username'";
	$result = $mysqli->query($sql);
	// if user is reseller then show reseller navbar else redirect to home
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			if ($row["reseller"] == 1) {
				echo '<div class="col-12 mt-3 mb-2">
					<div class="rounded p-3">
						<a href="index.php" class="btn btn-outline-primary btn-sm">Home</a>
						<a href="codes.php" class="btn btn-outline-primary btn-sm">License</a>
					</div>
				</div>';
			}
		}
	} else {
		echo '<script>window.location.href = "index.php";</script>';
	} ?>

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

					<?php 
                    // print only codes that are generated by the reseller
					$sql = "SELECT * FROM license WHERE createdBy = '$username'";
					$result = $mysqli->query($sql);
					if ($result->num_rows > 0) {
						while($row = $result->fetch_assoc()) {
							echo '<tr>
								<td>'.$row["code"].'</td>
								<td>'.$row["createdBy"].'</td>
							</tr>';
						}
					} else {
						echo '<tr>
							<td colspan="2">No codes generated yet!</td>
						</tr>';
					}
					?>
                    
				</tbody>

			</table>

		</div>
	</div>

</div>

<?php //Util::footer(); ?>