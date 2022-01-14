<?php

require_once '../app/require.php';
require_once '../app/controllers/AdminController.php';

$user = new UserController;
$admin = new AdminController;

include '../includes/db.php';

Session::init();

$username = Session::get("username");

$userList = $admin->getUserArray();

Util::adminCheck();
Util::head('Admin Panel');
Util::navbar();

// if post request 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	if (isset($_POST["resetHWID"])) { 
		$rowUID = $_POST['resetHWID'];
		$admin->resetHWID($rowUID); 
		$sql = "INSERT INTO `logs` (`log_user`, `log_action`, `log_time`) VALUES ('$username', 'Reset HWID from user with id $rowUID', NOW())";

		$webhook = ADMIN_WEBHOOK;
		$embed = array(
			"title" => "HWID Reset",
			"description" => "$username has reset the HWID for user with id $rowUID",
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

		$result = $mysqli->query($sql);
	}

	if (isset($_POST["setBanned"])) { 
		$rowUID = $_POST['setBanned'];
		$admin->setBanned($rowUID); 
		$sql = "INSERT INTO `logs` (`log_user`, `log_action`, `log_time`) VALUES ('$username', 'Ban/unban user with id $rowUID', NOW())";

		$webhook = ADMIN_WEBHOOK;
		$embed = array(
			"title" => "Ban/Unban",
			"description" => "$username has banned/unbanned user with id $rowUID",
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

		$result = $mysqli->query($sql);
	}

	if (isset($_POST["setAdmin"])) { 
		$rowUID = $_POST['setAdmin'];
		$admin->setAdmin($rowUID); 
		$sql = "INSERT INTO `logs` (`log_user`, `log_action`, `log_time`) VALUES ('$username', 'Set admin / nonadmin user with id $rowUID', NOW())";

		$webhook = ADMIN_WEBHOOK;
		$embed = array(
			"title" => "Admin/Non-Admin",
			"description" => "$username has set user with id $rowUID as admin",
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

		$result = $mysqli->query($sql);
	}

	header("location: users");

}

?>


<div class="container mt-2">
	<div class="row">

		<?php Util::adminNavbar(); ?>

		<div class="col-12 mt-3 mb-2">
			<table class="rounded table">


				<thead>
					<tr>

						<th scope="col" class="text-center">UID</th>

						<th scope="col">Username</th>

						<th scope="col" class="text-center">Admin</th>

						<th scope="col" class="text-center">Banned</th>

						<th scope="col">Actions</th>

					</tr>
				</thead>


				<tbody>

					<!--Loop for number of rows-->
					<?php foreach ($userList as $row) : ?>
						<tr>

							<th scope="row" class="text-center"><?php Util::display($row->uid); ?></th>

							<td><?php Util::display($row->username); ?></td>

							<td class="text-center">
								<?php if ($row->admin == 1) : ?>
									<i class="fas fa-check-circle"></i>
								<?php else : ?>
									<i class="fas fa-times-circle"></i>
								<?php endif; ?>
							</td>

							<td class="text-center">
								<?php if ($row->banned == 1) : ?>
									<i class="fas fa-check-circle"></i>
								<?php else : ?>
									<i class="fas fa-times-circle"></i>
								<?php endif; ?>
							</td>

							<td>
								<form method="POST" action="<?php Util::display($_SERVER['PHP_SELF']); ?>">
								
									<button value="<?php Util::display($row->uid); ?>" name="resetHWID"  title="Reset HWID" data-toggle="tooltip" data-placement="top" class="btn btn-sm text-white" type="submit">
										<i class="fas fa-microchip"></i>
									</button>

									<button value="<?php Util::display($row->uid); ?>" name="setBanned"  title="Ban/unban user" data-toggle="tooltip" data-placement="top" class="btn btn-sm text-white" type="submit">
										<i class="fas fa-user-slash"></i>
									</button>

									<button value="<?php Util::display($row->uid); ?>" name="setAdmin"  title="Set admin/non admin" data-toggle="tooltip" data-placement="top" class="btn btn-sm text-white" type="submit">
										<i class="fas fa-crown"></i>
									</button>

								</form>
							</td>

						</tr>
					<?php endforeach; ?>

				</tbody>

			</table>
		</div>
	</div>

</div>
<?php Util::footer(); ?>

<script>
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();   
});
</script>