<?php
session_start();
if (!isset($_SESSION['pgsLoggedIn'])) {
	header("Location: login.php?page=login");
} else {
	// Clear the session
	$_SESSION = array();
	// Destroy the session
	session_destroy();
	// Claer the cookie
	setcookie('PHPSESSID', '', time()-3600, '/', '', 0, 0);
	// Return to previous page
	echo '<script>alert("You have been logged off");
			if (document.referrer) { window.location.replace(document.referrer); } 
			else { window.location.replace("index.php?page=home"); }</script>';
}
?>