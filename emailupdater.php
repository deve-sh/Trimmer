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
	<title>Updating Email</title>
	<?php include './inc/styles.php'; ?>
</head>
<body>
	<?php include './header.php'; ?>
	<div class="pad">
		<?php
			if(!empty($_POST['email'])){
				
				$email = $db->escape($_POST['email']);

				$sql = "SELECT * FROM ".$sub."users WHERE userid='".$_SESSION['tuserid']."'";
				$sql1 = "SELECT * FROM ".$sub."users WHERE email='".$email."'";

				// Checking if any other already has the entered Email ID.

				if($db->numrows($db->query($sql1))>0){
					echo "<br>
							<div class='alert alert-danger'>
								A User already exists with the entered email. Kindly try another one.
							</div>";
				}

				$user = $db->fetch($db->query($sql));

				if(strcmp($email,$user['email'])!=0){	// Just a validation. Not needed.
					$updater = "UPDATE ".$sub."users SET email='".$email."' WHERE userid='".$_SESSION['tuserid']."'";

					if($db->query($updater)){
						echo "<br>
							<div class='alert alert-success'>
								Updated Email Successfully. Kindly Login Again.
							</div>";
						header("refresh:2;url=./logout.php");
						exit();
					}
					else{
						echo "<br>
							<div class='alert alert-danger'>
								Could not update due to a problem with the datbase. Kindly try again.
							</div>";
					}
				}
			}
		?>
	</div>
</body>
</html>