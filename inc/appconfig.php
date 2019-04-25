<?php
	/*	
		App Configuration File. 
		Edit this file to edit the app content.
		This page assumes that the script is already installed.

		*** NOTE *** : DO NOT EDIT IF YOU DO NOT HAVE ANY PREVIOUS KNOWLEDGE OF PHP or HTML. ASK SOMEONE ELSE WHO DOES TO DO IT FOR YOU. BECAUSE I WAS WAY TOO LAZY TO MAKE A DASHBOARD FOR EDITING THIS.
	*/

	$applogo = "";

	# Index Page.

	$appdesc = "A web app that lets you get a short link to your website, and use it wherever you want.";

	$introhtml = "
		<div class='indexdesc'>
			<h1>
				{$appname}
			</h1>
			<br/>
				{$appdesc}
			<br/><br/>
		</div>
	";

	# This appears in the second part of the index page.

	$continuetext = "
		<br/>
			Enter your continuation text here.
			<br/>
			<br/>

			Try the following : <br/>

			<ul class='explainer'>
				<li>Explain how to do it.</li>
				<li>Add some images to it.</li>
			</ul>
		<br/>
	";

	# These are the social links.
	# Add your own by adding the name of the Social Network and link to it.
	# You can convert the name into icons by simply changing the text to html of the icon.
	# Example : '<i class="instagram"></i> => "https://instagram.com/page"'

	$sociallinks = array(
		'Instagram' => "https://instagram.com/",
		'Facebook' => "https://facebook.com/"
	);

	# The HTML that needs to be displayed in the footer along with the social icons.

	$footerhtml = "";

	$footerhtml .= "Find us on : <br/><br/>";

	// Forms the HTML to be displayed in the footer.

	foreach ($sociallinks as $site => $link) {
		$footerhtml .= "<a href='{$link}' target='_blank' rel='noreferrer noopener'>{$site}</a> &nbsp;";
	}

	// The wait time for the app to redirect to a link. Can be extended if someone wishes to run ads on the redirect.

	$appwaittime = 0;	// Default set to 0.

	// String to store ad scripts if one would want to.
	// Change the empty string to HTML of the ad you want to show in order to show ads on the redirect page.

	$appadspot = "";
?>