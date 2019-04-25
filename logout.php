<?php
	session_start();
	include 'inc/checker.php';
	require('inc/config.php');
	include 'inc/appconfig.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $appname; ?> - Logout</title>
</head>
<body>
	<?php
		$_SESSION['tlogin'] = false;
		$_SESSION['tuserid'] = null;

		if(isset($_COOKIE['tlogin'])){
			setcookie('tlogin','',time() - 3600);
		}

		header("refresh:0;url=index.php");
		exit();
	?>
</body>
</html>