<?php

require_once '../app/require.php';
require_once '../app/controllers/AdminController.php';

$user = new UserController();
$admin = new AdminController();

Session::init();


$username = Session::get("username");

$logsList = $admin->getLogsArray();

Util::adminCheck();
Util::head('Admin Panel');
Util::navbar();

if (isset($_POST["delLogs"])) {
    $sql = "DELETE FROM `logs`";
    $result = $mysqli->query($sql);
    if ($result) {
        Util::redirect('/admin/logs');
    }
}

?>

<div class="container mt-2">
	<div class="row">

		<?php Util::adminNavbar(); ?>

        <div class="col-12 mt-3">
			<div class="rounded p-3 mb-3">

				<form method="POST" action="<?php Util::display($_SERVER['PHP_SELF']); ?>">
								
					<button name="delLogs" type="submit" class="btn btn-outline-primary btn-sm">
						Delete logs
					</button>

				</form>

			</div>
		</div>

        <!-- Reseller logs -->
        <div class="col-12 mb-2">
			<table class="rounded table">

				<thead>
					<tr>
						<th scope="col">User</th>
						<th scope="col">Action</th>
                        <th scope="col">At</th>
					</tr>
				</thead>
				<tbody>

        <?php foreach ($logsList as $row) : ?>
        <tr>
            <td><?php Util::display($row->log_user); ?></td>
            <td><?php Util::display($row->log_action); ?></td>
            <td><?php Util::display($row->log_time); ?></td>
        </tr>
        <?php endforeach; ?>

        </tbody>

			</table>

		</div>
	</div>
</div>

<?php Util::footer(); ?>