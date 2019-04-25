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
		if(isset($_COOKIE['tlogin']))
		{
			// Setting both session variables.
			$_SESSION['tuserid'] = json_decode($_COOKIE['tlogin'])->userid;
			$_SESSION['tlogin'] = true;

			header("refresh:0;url=./usercp.php");
			exit();
		}
	}
?>
<!doctype html>
<html>
<head>
	<title><?php echo $appname; ?></title>
	<?php 
		// Styles
		include 'inc/styles.php';

		// Pre-Requisites
		include 'inc/prereq.php';
	?>
</head>
<body>
	<?php
		include 'header.php';
	?>
	
	<main class="containter-fluid">
		<div id="first">
			<?php
				echo $introhtml;
			?>
		</div>
		<div class="indexcontinue">
			<?php
				echo $continuetext;
			?>
		</div>

	</main>
	<footer>
		<?php 
			echo $footerhtml;
		?>
	</footer>

	<?php
		// Scripts
		
	?>
</body>
</html>