<?php
	
	# Registration Page

	session_start();
	require('./inc/checker.php');
	require('./inc/config.php');
	require('./inc/salt.php');

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
	<title><?php echo $appname; ?> - Logging In ...</title>

	<?php
		include 'inc/styles.php';
		include 'inc/prereq.php';
	?>
</head>
<body class="container-fluid pad">
	<?php
		include 'header.php';
		
		// Checking the form inputs.

		$username = $db->escape($_POST['username']);

		$email = $db->escape($_POST['email']);

		$password = $db->escape($_POST['password']);

		$validator1 = $db->query("SELECT * FROM ".$sub."users WHERE username = '".$username."' OR email = '".$email."'");

		if($db->numrows($validator1) > 0){
			echo "<br>A user with the username or email already exists.<br>";
			header("refresh:2;url=register.php");
			exit();
		}

		$salt = generate();	 // Generating a random salt to be hashed with.

		$password = crypt($password,$salt);
		$password = $db->escape(md5($password));	// One extra layer of protection.

		
		
	?>
</body>
</html>