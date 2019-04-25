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
	<title><?php echo $appname; ?> - Change Password</title>
	<?php include './inc/styles.php'; ?>
</head>
<body>
	<?php
		include './header.php';
	?>
	<div class="pad" id="loginpage">
		<form action="passupdater.php" id="loginform" method="POST">
			<h5>Update Password</h5>
			<input type="password" name="pass1" required placeholder="Current Password">
			<br/>
			<input type="password" name="pass2" required placeholder="New Password">
			<br/>
			<button type="submit" class="btn btn-primary">Change</button>
			&nbsp;
			<a href='./usercp.php'><span class="btn homebutton">Back</span></a>
		</form>
	</div>
</body>
</html>