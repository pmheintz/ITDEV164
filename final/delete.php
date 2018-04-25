<?php
session_start();
require_once('dbconn.php');
$errors = '';
if (!isset($_SESSION['pgsLoggedIn']) || !$_SESSION['pgsLoggedIn']) {
	$errors = 'You must be logged in.';
	header('Location: login.php?page=login');
}
$row = getOneListing($_GET['listingId'], $pdo);
if ($_SESSION['userId'] !== $row['sellerId']) {
	$errors = 'User is not the registered seller of this item.';
}
?>
<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="UTF-8">
    <title>Paul's Guitar Shop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="img/png" href="images/favicon.png">
    <link href="https://fonts.googleapis.com/css?family=Nothing+You+Could+Do|Abel" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/base.css" />
  </head>
  <body>
    <?php include('headerNav.php'); ?>
    <section>
      <h3><?php echo $errors; ?></h3>
      <?php
      if (empty($errors)) {
      	echo '<h3>Deleting listing...</h3>';
	    // Delete lising image if exists
      	$targetDir = '../../uploads/sellerImgs/';
      	$targetFile = $targetDir.$row['photo'];
	    if (file_exists($targetFile) && $targetFile != $targetDir.'noImg.png') {
	      unlink($targetFile);
	    }
	    if (deleteListing($row['listingId'], $pdo) == 1) {
	    	echo '<h3>Success!</h3>';
	    }
      	echo '<p>Redirecting back to your <a href="sell.php?page=sell">sales page</a>.';
      	echo '<script>setTimeout(function(){ window.location = "sell.php?page=sell"; }, 3000);</script>';
      }
      ?>
    </section>
    <?php include('footer.php'); ?>
  </body>

</html>
