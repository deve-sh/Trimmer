<?php
	session_start();
	require('./installchecker.php');
	require('../inc/connect.php');
	require('../inc/salt.php');

	$_SESSION['tuserid'] = false;
	$_SESSION['tlogin'] = false;

	if(isset($_COOKIE['tlogin'])){
		setcookie('tlogin','',time()-3600);
	}

	// Removing any login info from any instance of the pre-installed app.
?>
<!DOCTYPE html>
<html>
<head>
	<title>Installing Trimmer</title>
	<?php 
		include '../inc/prereq.php';
		include '../inc/installstyles.php';
	?>
</head>
<body>
	<main class="installmain container-fluid">
		<div class="installindicator">
			<?php
				// Storing the details into an associative array.

				$dbdetails = array('dbhost' => $_POST['dbhost'], 'dbuser' => $_POST['dbuser'], 'dbpass' => $_POST['dbpass'], 'dbname' => $_POST['dbname']);

				foreach ($dbdetails as $dbdet => $value) {
					
					$value = escapestr($value);

					if(strcmp($dbdet,"dbpass")!=0){
						// The database password can be empty for unprotected databases. That should not be the case, but who am I to judge, I have seen people have databases without usernames!

						if(!$value){
							// If the value stored in the associative array is not valid. Exit the execution and print an error message.

							echo "<br><br>{$dbdet} not valid!";
							?>
							<a href="./index.php"><button class="btn btn-danger">Back</button></a>
							<?php
							exit();
						}
					}
				}

				// Storing the details of the admin in an associative array. Also storing the app details.

				$appdetails = array('appname' => $_POST['appname'], 'subs' => escapestr($_POST['subs']));

				if(!$appdetails['appname']){
					echo "<br><br>App Name not valid!";
					?>
					<a href="./index.php"><button class="btn btn-danger">Back</button></a>
					<?php
					exit();
				}


				$admindetails = array('adminusername' => $_POST['adminusername'], 'adminpass' => $_POST['adminpass'], 'adminemail' => $_POST['adminemail']);

				// Repeating the validation step for Admin Details too.

				foreach ($admindetails as $key => $value) {
					
					$value = escapestr($value);	// Escaping any injective stuff.

					if(!$value){
						// If the value stored in the associative array is not valid. Exit the execution and print an error message.

							echo "<br><br>{$key} not valid!";
							?>
							<a href="./index.php"><button class="btn btn-danger">Back</button></a>
							<?php
							exit();
					}
				}

				// Now the validations are done. Let's create some tables and queries.

				// If the table subscript has not been specified, then set it to skim_

				if(!$appdetails['subs']){
					$appdetails['subs'] = "trim_";
				}

				// Hashing Admin Password

				$salt = saltgen(); // Random Salt.

				$admindetails['adminpass'] = crypt($admindetails['adminpass'],$salt);

				$admindetails['adminpass'] = md5($admindetails['adminpass']);

				$admindetails['adminpass'] = escapestr($admindetails['adminpass']);

				// Table Queries

				$tablequeries = array(
					// Deletion Queries
					"DROP TABLE IF EXISTS ".$appdetails['subs']."userlinks;",
					"DROP TABLE IF EXISTS ".$appdetails['subs']."links CASCADE;",
					"DROP TABLE IF EXISTS ".$appdetails['subs']."users CASCADE;",
					// Users Table
					
					"CREATE TABLE ".$appdetails['subs']."users(userid int primary key auto_increment, username varchar(255) unique not null, password varchar(255) not null, email varchar(255) unique not null, salt varchar(255) not null);",
					
					// Links Table
					
					"CREATE TABLE ".$appdetails['subs']."links(linkid int primary key auto_increment, link text not null, nclicks int DEFAULT 0);",

					// Many - Many Relationship between the user and Links Table

					"CREATE TABLE ".$appdetails['subs']."userlinks(userid int references ".$appdetails['subs']."users(userid) on delete cascade on update set null, linkid int references ".$appdetails['subs']."links(linkid) on delete cascade on update set null, creationtime timestamp not null);"
					// MySQLI will set the timestamp to current time
					,
					// Admin Details.
					"INSERT INTO ".$appdetails['subs']."users(username,password,email,salt) VALUES('".$admindetails['adminusername']."','".$admindetails['adminpass']."','".$admindetails['adminemail']."','".$salt."')"
				);

				// User Messages while Query Execution

				$usermessages = array(
					"Dropping User-Links Table if Exists.<br>",
					"Dropping Links Table if exists.<br>",
					"Dropping Users Table if exists.<br>",
					"Creating Users Table.<br>",
					"Creating Links Table.<br>",
					"Creating User-Links Table.<br>",
					"Inserting Admin Data.<br>"
				);

				// Error Messages

				$errormessages = array(
					"Could not drop User-Links table.<br>",
					"Could not drop Links Table.<br>",
					"Could not drop Users Table.<br>",
					"Could not create Users Table.<br>",
					"Could not create Links Table.<br>",
					"Could not create User-Links Table.<br>",
					"Could not insert admin data.<br>"
				);


				// Creating a DB Driver Class Instance / Object.

				$db = new dbdriver();

				// Connecting to the Database.

				if(!$db->connect($dbdetails['dbhost'],$dbdetails['dbuser'],$dbdetails['dbpass'], $dbdetails['dbname'])){
					echo "<br><br>Database Details Incorrec for connection.<br><br>";
					?>
					<br><br><a href="./index.php"><button class="btn btn-danger">Back</button></a>
					<?php
					exit();
				}

				// Running each query on the database.

				$i = 0; // Counter Variable

				foreach ($tablequeries as $query) {
					echo $usermessages[$i];

					if(!$db->query($query)){
						echo $errormessages[$i];
						?>
						<br><br><a href="./index.php"><button class="btn btn-danger">Back</button></a>
						<?php
						exit();
					}

					$i++;
				}

				// Databases Set. Now working on the files.

				$filename1 = "../inc/lock";
				$filename2 = "../inc/config.php";

				// Opening the files.

				$handle1 = fopen($filename1, "w");
				$handle2 = fopen($filename2, "w");

				if(!$handle1 || !$handle2){
					// If one of the files did not open.

					echo "<br>There is some problem with the files.<br>";
					exit();
				}

				// Writing the lock.

				if(!fwrite($handle1, "1")){
					echo "<br>Could not write the lock file.<br>";
					exit();
				}

				// Forming the database string to the configuration file.

				$configstring = "<?php\nsession_start();\nrequire('./inc/connect.php');\n";
				$configstring .= "\$appname = '{$appdetails['appname']}';\n\$sub = '{$appdetails['subs']}';\n";

				foreach ($dbdetails as $key => $value) {
					$configstring .= "\$".$key." = '".$value."';\n";
				}

				$configstring .= "\$db = new dbdriver();\n\$db->connect(\$dbhost,\$dbuser,\$dbpass,\$dbname);";
				$configstring .= "\n?>";

				// Writing the configuration file.

				if(!fwrite($handle2, $configstring)){
					echo "<br>Could not write the lock file.<br>";
					exit();
				}

				// If everything worked perfectly and nothing has stopped till now. Print the success message.

				echo "<br>App Successfully Installed.<br><br>";
				echo "<a href='../'><button class='btn btn-primary'>View the App</button></a>";

			?>
		</div>
	</main>
</body>
</html>