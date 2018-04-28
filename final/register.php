<?php
// Start session
session_start();
// Connect to database
require_once('dbconn.php');
// Array to hold errors
$errors = [];
// Check to make sure page was properly accessed
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	// Verify fields
	if (empty($_POST['fname'])) { $errors['fname'] = "First name required"; }
	if (empty($_POST['lname'])) { $errors['lname'] = "Last name required"; }
	if (empty($_POST['email'])) { $errors['email'] = "Email required"; }
	if (empty($_POST['password'])) { $errors['password'] = "Password required"; }
	// If no errors, register user and mark them as logged in
	if (empty($errors)) {
		// Encrypt password 
		// *NOTE* MATC server currently runs php 5.4 so sha1 is used opposed to password_hash
		$pwd = sha1($_POST['password']);
		// Base sql statement
		$sql = "INSERT INTO users (fname, lname, email, password) VALUES (:fname, :lname, :email, :password)";

		try {
			// Prepare statement
			$stmt = $pdo->prepare($sql);

			// Execute statement
			$stmt->execute(['fname'=>$_POST['fname'], 'lname'=>$_POST['lname'], 'email'=>$_POST['email'], 'password'=>$pwd]);

			// Log new user in
			if ($stmt->rowCount() > 0) {
				$sql = "SELECT * FROM users WHERE (email=:email AND password=:password)";
				try {
					// Prepare statement
					$stmt = $pdo->prepare($sql);
					// Execute query
					$stmt->execute(['email'=>$_POST['email'], 'password'=>$pwd]);
					// Get rows
					$rows = $stmt->rowCount();
					if ($rows >= 1) {
						$rows = $stmt->fetch();
						$_SESSION['pgsLoggedIn'] = true;
						$_SESSION['userId'] = $rows['userId'];
						$_SESSION['fname'] = $rows['fname'];
						$_SESSION['lname'] = $rows['lname'];
						$_SESSION['email'] = $rows['email'];
						$pdo = null;
					}
				} catch (PDOException $e) {
					$pdo = null;
					exit ('There was an error logging you into your new account!');
				}
			}
		} catch (PDOException $e) {
			$_SESSION += $_POST;
			$_SESSION['errors']['dbError'] = $e->getMessage();
			header('Location: login.php?page=login');
			exit('Registration contains errors');
		}
	} else { // Errors exist, redirect back to login/signup page
		$_SESSION += $_POST;
		$_SESSION['errors'] = $errors;
		header('Location: login.php?page=login');
		exit('Registration contains errors');
	}
} else { // Page incorrectly accessed, redirect to login/signup page
	$pdo = null;
	header("Location: login.php?page=login");
}
?>
<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="UTF-8">
    <title>Registered</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="img/png" href="images/favicon.png">
    <link href="https://fonts.googleapis.com/css?family=Nothing+You+Could+Do|Abel" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/base.css" />
  </head>
  <body>
    <?php include('headerNav.php'); ?>
    <section>
      <h3>Thanks for registering <?php echo $_POST['fname']; ?>!</h3>
      <p>Feel free to browse the listings <a href="listings.php?page=listings">here</a>,<br />
      	or add/manage your sales <a href="sell.php?page=sell">here</a>.</p>
    </section>
    <?php include('footer.php'); ?>
  </body>

</html>
