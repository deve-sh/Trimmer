<?php
	session_start();

	# Script to check if the app has been intalled or not.

	$validator1 = fopen("../inc/lock","r");
	$validator2 = fopen("../inc/config.php","r");

	$validatorstring1 = fread($validator1, filesize("../inc/lock"));
	$validatorstring2 = fread($validator2, filesize("../inc/config.php"));

	if($validatorstring1[0] != "0" && $validatorstring2[0] != "0"){
		// If the lock or config file is set to 0.
		header("refresh:0;url=../");
		exit();
	}

	// Otherwise continue.
?>