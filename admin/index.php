<?php

require_once '../app/require.php';
require_once '../app/controllers/AdminController.php';

include '../includes/db.php';

$user = new UserController;
$admin = new AdminController;

Session::init();

$username = Session::get("username");

Util::adminCheck();
Util::head('Admin Panel');
Util::navbar();



?>

<div class="container mt-2">
	<div class="row">

		<?php Util::adminNavbar(); ?>


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
						<span class="small text-muted text-uppercase">banns</span>
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

    <div class="col-12 mt-3">
        <div class="rounded p-3 mb-3">
        <!-- Send Announcement -->
        <form method="POST" action="">
            <div class="form-row mt-1">
                <div class="col">
                    <input type="text" class="form-control form-control-sm" placeholder="Announce" name="Announce" required>
                </div>

                <div class="col">
                    <button class="btn btn-outline-primary btn-sm" name="send_announce" type="submit" value="submit">Send</button>
                </div>
            </div>

        </form>

        <!-- Send Live Message to all clients -->
        <form method="POST" action="">
            <div class="form-row mt-1">
                <div class="col">
                    <input type="text" class="form-control form-control-sm" placeholder="Live Message" name="LiveMessage" required>
                </div>

                <div class="col">
                    <button class="btn btn-outline-primary btn-sm" name="send_live_message" type="submit" value="submit">Send</button>
                </div>
            </div>
        </div>

        <!-- Center message -->
        <h1 class="text-center">Utils</h1>
        <div class="col-12 mt-3">
            <div class="rounded p-3 mb-3">
                    <form method="POST" action="">
                        <div class="form-row mt-1">
                            <div class="col">
                                <input type="text" class="form-control form-control-sm" placeholder="Message to all"  id="fakeban_message" name="LiveMessage" required>
                            </div>
                            <div class="col">
                                <button class="btn btn-outline-primary btn-sm" onclick="transmitMessage()">Send</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
    </div>
	</div>
</div>

<script>
    // Create a new WebSocket.
    var socket  = new WebSocket('ws://localhost:8080');

    // Define the
    var message = document.getElementById('fakeban_message');

    function transmitMessage() {
        socket.send( message.value );
    }

    socket.onmessage = function(e) {
        alert( e.data );
    }
</script>

<?php Util::footer(); ?>