<?php
	session_start();
	include 'inc/checker.php';
	require('inc/config.php');
	include 'inc/appconfig.php';

	// Getting the Passed Link ID

	$linkid = $_GET['id'];

	if(!$linkid){
		header("refresh:0;url=index.php");
		exit();
	}

	$sql = "SELECT * FROM ".$sub."links WHERE linkid = '".$linkid."'";

	try {
		$res = $db->query($sql);	// Executing the query.

		$numlinks = $db->numrows($res);

		if($numlinks <= 0 || $numlinks > 1){	
			// If not a valid linkid.
			throw new Exception("Not a valid request id.", 1);
		}

		// If a valid linkid.

		$result = $db->fetch($res);

		$updationquery = $db->query("UPDATE ".$sub."links SET nclicks=nclicks+1 WHERE linkid='".$result['linkid']."'");

		header("refresh:".$appwaittime.";url=".$result['link']."");

	} catch (Exception $e) {
		throw new Exception($e, 1);
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Redirecting ...</title>
</head>
<body>
	<?php
		echo $appadspot;
	?>
</body>
</html>