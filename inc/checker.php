<?php
	
	# Script to check whether the web application has been installed or not.

	session_start();

	$validator1 = fopen("./inc/lock","r");
	$validator2 = fopen("./inc/config.php","r");

	$validatorstring1 = fread($validator1, filesize("./inc/lock"));
	$validatorstring2 = fread($validator2, filesize("./inc/config.php"));

	if($validatorstring1[0] == "0" || $validatorstring2[0] == "0"){
		// If the lock or config file is set to 0.
		header("refresh:0;url=./install/");
		exit();
	}

	// Otherwise continue.
?>