<?php
	session_start();
	include 'inc/checker.php';
	require('inc/config.php');
	include 'inc/appconfig.php';

	// Checking if user is already logged in.

	if($_SESSION['tlogin'] == true && $_SESSION['tuserid']){
		header("refresh:0;url=usercp.php");
		exit();
	}
	else{
		if(isset($_COOKIE['tlogin']) && json_decode($_COOKIE['tlogin'])->userid)
		{
			// Setting both session variables.
			$_SESSION['tuserid'] = json_decode($_COOKIE['tlogin'])->userid;
			$_SESSION['tlogin'] = true;

			header("refresh:0;url=./usercp.php");
			exit();
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $appname; ?> - Register</title>
	<?php 
		// Styles
		include 'inc/styles.php';

		// Pre-Requisites
		include 'inc/prereq.php';
	?>
</head>
<body>
	<?php
		include './header.php';
	?>
	<div id='loginpage'>
		<form id="loginform" action="registering.php">
			<h4>Register</h4>
			<br/>
			<input type="text" name="username" placeholder="Username" required />
			<br/><br/>
			<input type="email" name="email" placeholder="Email" required/>
			<br/><br/>
			<input type="password" placeholder="Password" name="password" required />
			<br/><br/>
			<button type="submit" class="btn btn-submit">Register</button>
		</form>
		<br/><br/>
		<div align="center">
			<a href="./index.php"><button class="btn homebutton">Home</button></a>
		</div>
	</div>
</body>
</html>