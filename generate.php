<?php
	// Script to generate a shortened link.

	session_start();

	require('./inc/config.php');

	if($_SESSION['tlogin'] == true && $_SESSION['tuserid']){
		if($_GET['link']){
			
			// If the Link is passed.

			$link = $_GET['link'];

			$query1 = "SELECT * FROM ".$sub."links";

			$n = $db->numrows($db->query($query1));
			$n = $n + 1;

			$morequeries = array(
				"INSERT INTO ".$sub."links(link,nclicks) VALUES('".$link."',0)",
				"INSERT INTO ".$sub."userlinks(userid,linkid) VALUES(".$_SESSION['tuserid'].",".$n.")"
			);

			$errors = 0;

			foreach ($morequeries as $mquery) {
				if(!$db->query($mquery)){
					$errors++;
					echo "{\"status\":500}";	// Error in database.
					exit();
				}
			}
			
			echo "{\"status\":200,\"id\":".$n."}";
		}
		else{
			echo "{\"status\":300}";	// Link not passed.
		}
	}
	else{
		echo "{\"status\":401}";	// Unauthorised.
	}

?>