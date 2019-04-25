<?php
	session_start();
	include 'inc/checker.php';
	require('inc/config.php');
	require('inc/salt.php');

	// Checking if user is already logged in.

	if((!$_SESSION['tlogin'] == true || !$_SESSION['tuserid']) && (!isset($_COOKIE['tlogin']))){
		header("refresh:0;url=./login.php");
		exit();
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Updating Password</title>
	<?php include './inc/styles.php'; ?>
</head>
<body>
	<?php include './header.php'; ?>
	<div class="pad">
		<?php
			if(!empty($_POST['pass1']) && !empty($_POST['pass2'])){
				
				$oldpass = $db->escape($_POST['pass1']);
				$newpass = $db->escape($_POST['pass2']);

				$sql = "SELECT * FROM ".$sub."users WHERE userid='".$_SESSION['tuserid']."'";
				
				$user = $db->fetch($db->query($sql));

				if(strcmp($db->escape(md5(crypt($oldpass,$user['salt']))),$user['password'])==0){	
					
					// Just a validation. Not needed.

					$newsalt = saltgen();

					$newpass = crypt($newpass,$newsalt);
					$newpass = md5($newpass);
					$newpass = $db->escape($newpass);

					$updater = "UPDATE ".$sub."users SET password='".$newpass."',salt='".$newsalt."' WHERE userid='".$_SESSION['tuserid']."'";

					if($db->query($updater)){
						echo "<br>
							<div class='alert alert-success'>
								Updated Password Successfully. Kindly Login Again.
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
				else{
					echo "<br>Invalid Old Password entered.";
					header("refresh:1;url=./changepass.php");
					exit();
				}
			}
			else{
				header("refresh:0;url=index.php");
				exit();
			}
		?>
	</div>
</body>
</html>