<?php
	session_start();
	include 'inc/checker.php';
	require('inc/config.php');

	// Checking if user is already logged in.

	if((!$_SESSION['tlogin'] == true || !$_SESSION['tuserid']) && (!isset($_COOKIE['tlogin']))){
		header("refresh:0;url=./login.php");
		exit();
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $appname; ?> - Change Email</title>
	<?php include './inc/styles.php'; ?>
</head>
<body>
	<?php
		include './header.php';
	?>
	<div class="pad" id="loginpage">
		<form action="emailupdater.php" id="loginform" method="POST">
			<h5>Update Email</h5>
			<input type="email" name="email" required placeholder="New Email">
			<br/>
			<button type="submit" class="btn btn-primary">Change</button>
		</form>
	</div>
</body>
</html>