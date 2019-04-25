<?php
	session_start();
	require('inc/checker.php');
	require('inc/config.php');

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
<?php
		include 'header.php';
?>
<body class="container-fluid pad">
	<?php
		
		// Checking the login info.

		$username = $db->escape($_POST['username']);		// Form Inputs
		$password = $db->escape($_POST['password']);
		$rememberme = $db->escape($_POST['remember']);

		// User Selection Query.

		$query1 = "SELECT * FROM ".$sub."users WHERE username = '{$username}' OR email='{$email}'";

		if($db->query($query1)){
			$userarray = $db->fetch($db->query($query1));

			$salt = $userarray['salt'];

			$cryptedpass = crypt($password,$salt);
			$cryptedpass = md5($cryptedpass);
			$cryptedpass = $db->escape($cryptedpass);

			if(strcmp($cryptedpass,$userarray['password']) == 0){
				// If passwords match.

				$_SESSION['tlogin'] = true;
				$_SESSION['tuserid'] = $userarray['userid'];

				if($rememberme != false){
					// If the user opted to be remembered.
					setcookie('tlogin','{"userid":'.$_SESSION['tuserid'].'}',time() + 84600*15);	// Create a login cookie for the next 15 days.
				}

				// Print the success message.

				echo "<br>Successfully Logged In.<br>";
				
				// Redirect the user to User Control Panel.

				header("refresh:2;url=usercp.php");
				exit();
			}
			else{
				echo "<br>Wrong Password Entered.<br>You are being redirected to login page.<br>";
				header("refresh:3;url=login.php");
				exit();
			}
		}
		else{
			echo "<br>No such user found.<br>";
			header("refresh:3;url=login.php");
			exit();
		}
	?>
</body>
</html>