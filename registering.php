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
	<title><?php echo $appname; ?> - Registering</title>

	<?php
		include 'inc/styles.php';
		include 'inc/prereq.php';
	?>
</head>
<?php include 'header.php'; ?>
<body class="container-fluid pad">
	<?php
		
		// Checking the form inputs.

		$username = $db->escape($_POST['username']);

		$email = $db->escape($_POST['email']);

		$password = $db->escape($_POST['password']);

		$validator1 = $db->query("SELECT * FROM ".$sub."users WHERE username = '".$username."' OR email = '".$email."'");

		if($db->numrows($validator1) > 0){
			echo "<br>A user with the username or email already exists.<br>";
			header("refresh:50;url=register.php");
			exit();
		}

		$salt = saltgen();	 // Generating a random salt to be hashed with.

		$password = crypt($password,$salt);
		$password = md5($password);	// One extra layer of protection.

		$insertionsql = "INSERT INTO ".$sub."users(username,email,password,salt) VALUES('".$username."','".$email."','".$password."','".$salt."')";

		if($db->query($insertionsql)){
			echo "Successfully Registered. Redirecting to login page.";
			header("refresh:2.5;url=login.php");
			exit();
		}
		else{
			echo "There was a problem registering.";
			header("refresh:1.url=register.php");
			exit();
		}

	?>
</body>
</html>