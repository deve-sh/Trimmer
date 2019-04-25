<?php
	// Script to generate a shortened link.

	session_start();

	require('./inc/config.php');

	if($_SESSION['tlogin'] == true && $_SESSION['tuserid']){
		if($_GET['link']){
			
			// If the Link is passed.

			$link = $_GET['link'];

			$sql1 = "INSERT INTO ".$sub."links(link,nclicks) VALUES('".$link."',0)";

			try{
				$linked = $db->query($sql1);

				$sql2 = "SELECT * FROM ".$sub."links ORDER BY linkid DESC LIMIT 1";	// Selecting the last record just inserted.

				$linked = $db->query($sql2);

				$linked1 = $db->fetch($linked);

				$linkid = $linked1['linkid'];

				$sql3 = "INSERT INTO ".$sub."userlinks(userid,linkid) VALUES('".$_SESSION['tuserid']."','".$linkid."')";

				$db->query($sql3);
			}
			catch(Exception $e){
				echo "{\"status\":500}";	// Error in database.
			}
			
			echo "{\"status\":200,\"id\":".$linkid."}";
		}
		else{
			echo "{\"status\":300}";	// Link not passed.
		}
	}
	else{
		echo "{\"status\":401}";	// Unauthorised.
	}

?>