<!--Admin navigation-->

<!-- Check if admin // This is not important really.-->
<?php if (Session::isAdmin()) : ?>
	<div class="col-12 mt-3 mb-2">
		<div class="rounded p-3">
			<a href='index' class="btn btn-outline-primary btn-sm">Home</a>
			<a href='users' class="btn btn-outline-primary btn-sm">Users</a>
			<a href='invites' class="btn btn-outline-primary btn-sm">Invites</a>
			<a href='logs' class="btn btn-outline-primary btn-sm">Logs</a>
			<a href='sub.php' class="btn btn-outline-primary btn-sm">Sub codes</a>
			<a href='cheat' class="btn btn-outline-primary btn-sm">cheat</a>
		</div>
	</div>
<?php endif; ?>