<?php
session_start();
require './installchecker.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Install Trimmer</title>

	<?php 
		include '../inc/prereq.php';
		include '../inc/installstyles.php';
	?>
</head>
<body>
	<main class="installmain container-fluid">

		<form action="installer.php" id='installform' method="POST">
			<h1>Installer Trimmer</h1>
			
			<span class="alignleft">
				<h4>Database Details</h4>
			</span>
			<div class="alignleft">
				(Only MySQL Improved Databases Allowed).
			</div>
			<br>
			<fieldset>
				<input type="text" class="form-control" name="dbhost" placeholder="DB Host" required>
				<br/>
				<input type="text" class="form-control" name="dbuser" placeholder="DB Username" required>
				<br/>
				<input type="password" class="form-control" name="dbpass" placeholder="DB Password">
				<br/>
				<input type="text" name="dbname" required placeholder="DB Name" class="form-control" required>
				</fieldset>
			<br>

			<span class="alignleft">
				<h4>App and Admin Details</h4>
			</span>
			<br>
			<fieldset>
				<input type="text" name="appname" class="form-control" required placeholder="App Name">
				<br/>
				<input type="text" name="subs" class="form-control" placeholder="Subs (Default : trim_)">
				<br/>
				<input type="text" name="adminusername" class="form-control" required placeholder="Admin Username">
				<br/>
				<input type="password" name="adminpass" class="form-control" required placeholder="Admin Password">
				<br/>
				<input type="email" name="adminemail" class="form-control" required placeholder="Admin Email">
				<br/>
			</fieldset>
			<button class="btn waves-effect waves-light" type="submit" name="action">Install
			</button>
		</form>
	</main>
</body>
</html>