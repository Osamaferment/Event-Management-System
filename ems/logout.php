<?php

	session_start();
	
	// If the SESSION isn't set, the user is redirected to the homepage: Index page, to login.
	if (!isset($_SESSION['user'])) {
		header("Location: index.php");
	} else if(isset($_SESSION['user'])!="") {
		header("Location: create_event.php");
	}
	
	// The logout button was selected so all SESSIONS are cleared and the user is redirected to the login page.
	if (isset($_GET['logout'])) {
		unset($_SESSION['user']);
		session_unset();
		session_destroy();
		header("Location: index.php");
		exit;
	}