<?php
	session_start();
	require('./inc/checker.php');
	require('./inc/config.php');

	# Page to delete an already existing link.

	$linkid = $_GET['id'];

	if(!$_SESSION['tuserid'] || $_SESSION['tlogin']!=true){
		header("refresh:0;url=login.php");
		exit();
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $appname; ?> - Delete Link</title>
	<?php include './inc/styles.php'; ?>
</head>
<body class="container-fluid pad">
	<?php
		if($linkid){
			$sql1 = "SELECT * FROM ".$sub."links WHERE linkid='".$linkid."'";
			$sql2 = "SELECT * FROM ".$sub."userlinks WHERE linkid='".$linkid."'";

			try{
				$query1 = $db->query($sql1);

				$query2 = $db->query($sql2);
			}
			catch(Exception $e){
				throw new Exception($e,1);
			}

			if($db->numrows($query1) <= 0){
				echo "Invalid Link ID Passed.";
				header("refresh:0;url=usercp.php");
				exit();
			}

			// Now checking if the current user is the owner of the link.

			$linkuser = $db->fetch($query2);

			if($linkuser['userid'] != $_SESSION['tuserid']){
				echo "<br>Unauthorised.<br>";
				header("refresh:2;url=index.php");
				exit();
			}

			$deletionsql1 = "DELETE FROM ".$sub."links WHERE linkid = '".$linkid."'";
			$deletionsql2 = "DELETE FROM ".$sub."userlinks WHERE linkid = '".$linkid."'";

			if(!$db->query($deletionsql1) || !$db->query($deletionsql2)){
				echo "<br>There was a problem with the deletion.";
				header("refresh:2;url=usercp.php");
				exit();
			}

			echo "<br>Link Successfully Deleted.<br>You are being redirected to your User CP.";
			header("refresh:3;url=usercp.php");
			exit();
		}
		else{
			header("refresh:0;url=index.php");
			exit();
		}
	?>
</body>
</html>