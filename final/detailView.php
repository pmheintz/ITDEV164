<?php
session_start();
require_once('dbconn.php');
$listing = '';
$listingFound = false;
if (isset($_GET['listingId']) && !empty($_GET['listingId']) && is_numeric($_GET['listingId'])) {
	$listing = getOneListing($_GET['listingId'], $pdo);
	if ($listing !== false) {
		$listingFound = true;
	}
}
?>
<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="UTF-8">
    <title><?php if ($listingFound) { echo $listing['make'].' '.$listing['model']; } else { echo 'Not found'; }?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="img/png" href="images/favicon.png">
    <link href="https://fonts.googleapis.com/css?family=Nothing+You+Could+Do|Abel" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/base.css" />
  </head>
  <body>
    <?php include('headerNav.php'); ?>
    <section>
      <h3>Welcome to Paul's Guitar Shop!</h3>
      <?php 
      if (!$listingFound) {
      	echo '<h3 style="color: red;">Listing not found!</h3>'.PHP_EOL.'<p>Redirecting to <a href="listings.php?page=listings">listings</a>.'.PHP_EOL;
      	echo '<script>setTimeout(function(){ window.location = "listings.php?page=listings"; }, 3000);</script>';
      }
      ?>
      <div class="row detailView">
      	<div class="col-10">
      		<p>
      			<b>Manufactorer: </b><?php echo $listing['make']; ?><br />
      			<b>Model: </b><?php echo $listing['model']; ?><br />
      			<b>Type: </b><?php echo $listing['type']; ?><br />
      			<b>Number of Strings: </b><?php echo $listing['numStrings']; ?><br />
      			<b>Color: </b><?php echo $listing['color']; ?><br />
      			<b>Condition: </b><?php echo $listing['condition']; ?>&#47;5<br />
      			<b>Description: </b><br /><?php echo $listing['description']; ?><br /><br />
      			<b>Asking Price: </b><?php echo $listing['price']; ?>
      		</p>
      		<h4>
      			<a href="emailForm.php?page=listings&listingId=<?php echo $listing['listingId']; ?>">Interested? Click here to email the seller!</a>
      		</h4>
      	</div>
      	<div class="col-2">
      		<a href="../../uploads/sellerImgs/<?php echo $listing['photo']; ?>" target="_blank">
      			<img src="../../uploads/sellerImgs/<?php echo $listing['photo']; ?>" /></a>
      	</div>
      </div>
      <a href="<?php if (isset($_SESSION['listingResultsPage'])) { echo $_SESSION['listingResultsPage']; } else { echo 'listings.php?page=listings'; }?>">Return to listings</a>
    </section>
    <?php include('footer.php'); ?>
  </body>

</html>
