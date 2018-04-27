<?php
// Start session
session_start();
// Include PDO
require_once('dbconn.php');
// Make sure page was accessed correctly
if ($_SERVER['REQUEST_METHOD']=='POST') {
	// Make sure login email exists
	if (!empty($_REQUEST['loginEmail'])) {
		$email = hsc($_REQUEST['loginEmail']);
	} else {
		$_SESSION['loginFail']['noEmail'] = 'No email found! ';
	}
	// Make sure login password exists and encrypt it if it does
	if (!empty($_REQUEST['loginPassword'])) {
		$pwd = sha1($_REQUEST['loginPassword']);
	} else {
		$_SESSION['loginFail']['noPassword'] = 'No password found!';
	}
	// If no login errors
	if (empty($_SESSION['loginFail'])) {
		// Base SQL
		$sql = "SELECT * FROM users WHERE (email=:email AND password=:password)";
		try {
			// Prepare statement
			$stmt = $pdo->prepare($sql);
			// Execute query
			$stmt->execute(['email'=>$email, 'password'=>$pwd]);
			// Get rows
			$rows = $stmt->rowCount();
			echo '<script>alert("'.$rows.'")</script>';
			// If row returned, log user in, set session variables
			if ($rows >= 1) {
				$rows = $stmt->fetch();
				$_SESSION['pgsLoggedIn'] = true;
				$_SESSION['userId'] = $rows['userId'];
				$_SESSION['fname'] = $rows['fname'];
				$_SESSION['lname'] = $rows['lname'];
				$_SESSION['email'] = $rows['email'];
				$pdo = null;
				header('Location: '.$_REQUEST['returnUrl']);
			} else { // Login failed
				$_SESSION['loginFail']['failed'] = 'Password and email do not match!';
				$pdo = null;
				header('Location: login.php?page=login');
				exit();
			}

		} catch (PDOException $e) {
			$_SESSION['loginFail']['e'] = $e->getMessage();
			$pdo = null;
			header('Location: login.php?page=login');
			exit();
		}
	} else {
		$pdo = null;
		header('Location: login.php?page=login');
		exit();
	}
} else { // Page not accessed through login screen
	$pdo = null;
	header('Location: login.php?page=login');
}
?>