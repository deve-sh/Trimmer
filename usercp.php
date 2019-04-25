<?php
	session_start();
	include 'inc/checker.php';
	require('inc/config.php');

	// Checking if user is already logged in.

	if((!$_SESSION['tlogin'] == true || !$_SESSION['tuserid']) && (!isset($_COOKIE['tlogin']))){
		header("refresh:0;url=./login.php");
		exit();
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $appname; ?> - User CP</title>
	<?php
		include './inc/styles.php';
		include './inc/prereq.php';
	?>
</head>
<body>
	<?php
		include './header.php';
	?>
	<div class="pad">
		<?php
			// Printing Hello User.

			try{
				$query = "SELECT * FROM ".$sub."users WHERE userid='".$_SESSION['tuserid']."'";

				$user = $db->fetch($db->query($query));

				echo "Hey ".$user['username']."!";
			}
			catch(Exception $e){
				throw new Exception($e,1);
			}
		?>
	</div>

	<div class="row useroptions">
		<div class="col-xs-6">
			<a href="./changeemail.php"><button class='btn updateemail'>Update Email</button></a>
		</div>
		<div class="col-xs-6">
			&nbsp;<a href="./changepass.php"><button class='btn updatepass'>Update Password</button></a>
		</div>
	</div>

	<div id='linkshortener'>
		<form id="shortenform" method="POST">
			<h4 class="aligncenter">Shorten a Link</h4>
			<input type="text" id="link" name="link" required placeholder="Link to shorten">
			<br/>
			<button type="submit" class="btn btn-submit">Shorten</button>
			<br/><br/>
			<div id="shortenedlink">
				<!-- Element to print the shortened links or error messages -->
			</div>
		</form>
	</div>
	<div id='userdetails'>
		<?php
			// Count the number of shortened links of the user and show it to them.

			$query = "SELECT count(*) FROM ".$sub."userlinks WHERE userid='".$_SESSION['tuserid']."'";

			$numlinks = $db->fetch($db->query($query));

			$numlinks = $numlinks['count(*)'];

			echo "<div class='numlinks'>".$numlinks."</div>
			<div class='aligncenter'>Links total</div>";
		?>
		<br/>

		<div id="userlinks">
		<?php
			// Printing each link and its details if there are any.

			if($numlinks > 0){
				/* BASIC PAGINATION */

				$rowsperpage = 5;	// 5 links per page.
				
				$totalpages = ceil($numlinks / $rowsperpage);
				
				
				if (isset($_GET['currentpage']) && is_numeric($_GET['currentpage'])) {
				   $currentpage = (int) $_GET['currentpage'];
				} 
				else {
				   $currentpage = 1;
				} 

				
				if ($currentpage > $totalpages) {
				   
				   $currentpage = $totalpages;
				} 
				
				if ($currentpage < 1) {
				   
				   $currentpage = 1;
				} 

				
				$offset = ($currentpage - 1) * $rowsperpage;

				
				$sql = "SELECT * FROM ".$sub."links WHERE linkid IN (SELECT linkid FROM trim_userlinks WHERE userid='".$_SESSION['tuserid']."') LIMIT $offset, $rowsperpage";

				$result = $db->query($sql);

				echo "<table class='linkstable'>
					<tr class='userlink'>
						<th>Link To</th>
						<th>Link</th>
						<th>Visits</th>
						<th>Remove</th>
					</tr>
				";
				
				while ($link = $db->fetch($result)) {
				  	echo "<tr class='userlink'>
							<td>
								<a href='".$link['link']."' target='_blank' rel='noreferrer noopener'>".$link['link']."</a>
							</td>
							<td>
								<a href='./redir.php?id=".$link['linkid']."' target='_blank' rel='noreferrer noopener'>Shortened Link</a>
							</td>
							<td>
								".$link['nclicks']."
							</td>
							<td>
								<a href='./delete.php?id=".$link['linkid']."'><i class=\"fas fa-trash deleteicon\"></i></a>
							</td>
						  </tr>
						";
				} 

				echo "</table>";

				$range = 5;

				// Pagination

				echo "<br><ul class='pagination_s'>";

				if ($currentpage > 1) {
				   
				   echo "&nbsp;<a href='{$_SERVER['PHP_SELF']}?currentpage=1'><li class=\"page-link\"><i class=\"fas fa-angle-double-left\"></i></li></a>";
				   
				   $prevpage = $currentpage - 1;
				   
				   echo " &nbsp;<a href='{$_SERVER['PHP_SELF']}?currentpage=$prevpage'><li class=\"page-link\"><i class=\"fas fa-angle-left\"></i> </li></a>";
				} 

					
				for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {
				
				   if (($x > 0) && ($x <= $totalpages)) {
				
				      if ($x == $currentpage) {
				         echo "&nbsp;<li class='page-link active'><b>$x</b></li> ";
				      
				      } else {
				         // make it a link
				         echo " &nbsp;<a href='{$_SERVER['PHP_SELF']}?currentpage=$x'><li class=\"page-link\">$x</li></a>";
				      } 
				   } 
				} 
				                 
				
				if ($currentpage != $totalpages) {
				   
				   $nextpage = $currentpage + 1;
				    
				   echo " &nbsp;<a href='{$_SERVER['PHP_SELF']}?currentpage=$nextpage'><li class=\"page-link\"><i class=\"fas fa-angle-right\"></i></li></a> ";
				   
				   echo " &nbsp;<a href='{$_SERVER['PHP_SELF']}?currentpage=$totalpages'><li class=\"page-link\"><i class=\"fas fa-angle-double-right\"></i></li></a> ";
				}

				echo "</ul>";
			}
		?>
		</div>
	</div>

	<script type="text/javascript">

		// Script for generating shortened link.

		function generate(event) {
			console.log(event);
			event.preventDefault();	// Prevent a page refresh.

			let xhr = new XMLHttpRequest();
			let link = encodeURIComponent(document.querySelector('#link').value);
			
			xhr.open('GET',`./generate.php?link=${link}`,true);

			xhr.send();

			xhr.onload = function(){
				let response = JSON.parse(xhr.responseText);

				let path = window.location.origin + window.location.pathname;
				let index = path.indexOf('usercp.php');

				path = path.slice(0,index) + "redir.php?id=";

				if(response.status === 200){
					document.querySelector('#shortenedlink').innerHTML = `
						<div id='link' class='row'>
							<div class='col-md-9 linkarea'>
								${path+response.id.toString()}
							</div>
							<div class='col-md-3 copybutton' onclick='copy()'>
									Copy
							</div>
						</div>
						`;
				}
				else{
					document.querySelector('#shortenedlink').innerHTML = "<div class='alert alert-danger'>There was some problem with the database.</div>";
				}
			}	
		}

		document.querySelector("#shortenform").addEventListener('submit',generate);


		function copy(){
			// Function to copy text.
			  let target = document.getElementsByClassName('linkarea')[0];
			  let range, select;
			  if (document.createRange) {
			    range = document.createRange();
			    range.selectNode(target)
			    select = window.getSelection();
			    select.removeAllRanges();
			    select.addRange(range);
			    document.execCommand('copy');
			    select.removeAllRanges();
			  } 
			  else {
			    range = document.body.createTextRange();
			    range.moveToElementText(target);
			    range.select();
			    document.execCommand('copy');
			  }
		}
	</script>
</body>
</html>