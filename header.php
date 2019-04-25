<?php
	session_start();
?>
<!-- Header -->
<header class='header row'>
	<div class="left col-xs-6">
		<?php 
			if(!$applogo)
				echo $appname; 
			else{
				?>
					<img src=<?php echo $applogo; ?> class='logoimage' alt=<?php echo $appname; ?> >&nbsp;
				<?php
			}
		?>
	</div>
	<div class="right col-xs-6">
		<?php
			if($_SESSION['tlogin'] == true && $_SESSION['tuserid']){
				?>
					<a href="./logout.php">Logout</a>
				<?php
			}
			else{
				?>
					<a href="./login.php">Login</a>
					&nbsp;&nbsp;
					<a href="./register.php">Register</a>
				<?php
			}
		?>
	</div>
</header>