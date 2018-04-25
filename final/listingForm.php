<?php
// Start session
session_start();
// Redirect if not logged in
if (!isset($_SESSION['pgsLoggedIn'])) {
	header('Location: login.php?page=login');
}
// Make database connection
require_once('dbconn.php');
// Create empty array to hold errors
$errors = [];
// Variable to determine if successfully added
$added = false;
// Check if update or add
$entry = 'Add';
if (isset($_GET['listingId'])) {
	$entry = 'Update';
	$row = getOneListing($_GET['listingId'], $pdo);
	if ($_SESSION['userId'] !== $row['sellerId']) {
		header('Location: sell.php?page=sell');
	}
}
// Check if POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	// Check required fields
	if (empty($_POST['make'])) { $errors['make'] = 'Please enter a manufactorer.'; }
	if (empty($_POST['model'])) { $errors['model'] = 'Please enter a model.'; }
	if (empty($_POST['numStrings'])) { $errors['numStrings'] = 'Please enter number of strings.'; }
	if (empty($_POST['description'])) { $errors['description'] = 'Please enter a description.'; }
	if (empty($_POST['price'])) { $errors['price'] = 'Please enter a price.'; }
	$row = $_POST;

	// If no errors, add/update listing
	if (empty($errors)) {
		// Create placeholder row if new entry
		if (empty($row['listingId'])) {
			$row['listingId'] = createPlaceholder($_SESSION['userId'], $pdo);
			$placeholderCreated = true;
		}

		// Path for image uploads
		$targetDir = '../../uploads/sellerImgs/';

		// Check for image, existing or new. Set default image if none
		if (empty($_FILES['fileToUpload']['name']) && !isset($newImageName)) {
			$row['photo'] = 'noImg.png';
		}
		if (isset($newImageName)) {
			$row['photo'] = $newImageName;
		}

		// If new image has been uploaded...
		if (!empty($_FILES['fileToUpload']['name'])) {
		    // Path with filename
		    $targetFile = $targetDir.basename($_FILES['fileToUpload']['name']);
		    // Get image size of upload file, will return false if not an image
		    $check = getimagesize($_FILES['fileToUpload']['tmp_name']);
		    // Set error if not an image
		    if (!$check) {
				$imgErrors['fileToUpload'] = 'File selected was not an image.';
			} else {
				// File is an image, check if file type is supported
				$imageFileType = pathinfo($targetFile, PATHINFO_EXTENSION);
				if ($imageFileType != 'jpg' && $imageFileType != 'png' && $imageFileType != 'jpeg' && $imageFileType != 'gif') {
					$imgErrors['fileToUpload'] = 'Unsupported image format. <br />Please choose "jpg", "jpeg", "png", or "gif".';
				}
			}
		    // Rename the image
		    $newImageName = 'user'.$_SESSION['userId'].'-l'.$row['listingId'].'.'.$imageFileType;
		    $row['photo'] = $newImageName;
		    $targetFile = $targetDir.$newImageName;
		    // Delete previous image if exists
		    if (file_exists($targetFile)) {
		    	unlink($targetFile);
		    }
			// Check image size (Max size 500000 bytes)
			if ($_FILES["fileToUpload"]["size"] > 500000) {
				$imgErrors['fileToUpload'] = 'Photo exceeds maximum size. <br />Please choose a smaller photo.';
			}
			// Check if errors occured
			if (empty($imgErrors)) {
				// Attempt to upload the image
				if (!move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $targetFile)) {
					if ($placeholderCreated) { deleteListing($row['listingId'], $pdo); }
					$imgErrors['fileToUpload'] = 'Listing added, but an error occured and the image wasn\'t uploaded';
				}
			}
		}
		// Add listing to DB
		$added = updateListing($row, $row['listingId'], $pdo);
	}
}
?>
<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="UTF-8">
    <title>Sell A Guitar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="img/png" href="images/favicon.png">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Nothing+You+Could+Do|Abel" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/base.css" />
  </head>
  <body>
    <?php include('headerNav.php'); ?>
    <section>
	<?php
		if (!empty($errors)) {
	      	echo '<p style="color: red;">';
	      	foreach ($errors as $error) {
      			echo $error.'<br />';
			}
	      	foreach ($imgErrors as $imgError) {
      			echo $imgError.'<br />';
			}
      		echo '</p>';
    	} else if ($added) {
    		echo '<h3>Your listing has been posted</h3>';
    	}
	?>
	<br />
    <fieldset <?php if ($added) { echo 'disabled'; } ?>>
      	<legend>Your guitar listing</legend>
		    <form method="post" action="<?php echo hsc($_SERVER['PHP_SELF']).'?page=sell';?>" enctype="multipart/form-data">
	        <div style="overflow-x:auto;">
	      	<table>
	      		<tr>
	      			<td>Manufactorer: </td>
	      			<td><input type="text" name="make" value="<?php if (isset($row['make'])) { echo $row['make']; } ?>" required/></td>
	      		</tr>
	      		<tr>
	      			<td>Model: </td>
	      			<td><input type="text" name="model" value="<?php if (isset($row['model'])) { echo $row['model']; } ?>" required/></td>
	      		</tr>
	      		<tr>
	      			<td>Type: </td>
	      			<td>
	      				<select name="type">
	      					<option value="electric" <?php if (isset($row['type']) && $row['type'] == 'electric') { echo 'selected'; } ?>>Electric</option>
	      					<option value="acoustic" <?php if (isset($row['type']) && $row['type'] == 'acoustic') { echo 'selected'; } ?>>Acoustic</option>
	      					<option value="bass" <?php if (isset($row['type']) && $row['type'] == 'bass') { echo 'selected'; } ?>>Bass</option>
	      					<option value="acousticBass" <?php if (isset($row['type']) && $row['type'] == 'acousticBass') { echo 'selected'; } ?>>Acoustic Bass</option>
	      				</select>
	      			</td>
	      		</tr>
	      		<tr>
	      			<td>Number of Strings: </td>
	      			<td><input type="number" name="numStrings" value="<?php if (isset($row['numStrings'])) { echo $row['numStrings']; } ?>" required/></td>
	      		</tr>
	      		<tr>
	      			<td>Base Color: </td>
	      			<td>
	      				<select name="color">
			              <option value="white" <?php if (isset($row['color']) && $row['color'] == 'white') { echo 'selected'; } ?>>White</option>
			              <option value="black" <?php if (isset($row['color']) && $row['color'] == 'black') { echo 'selected'; } ?>>Black</option>
			              <option value="blue" <?php if (isset($row['color']) && $row['color'] == 'blue') { echo 'selected'; } ?>>Blue</option>
			              <option value="green" <?php if (isset($row['color']) && $row['color'] == 'green') { echo 'selected'; } ?>>Green</option>
			              <option value="red" <?php if (isset($row['color']) && $row['color'] == 'red') { echo 'selected'; } ?>>Red</option>
			              <option value="wood" <?php if (isset($row['color']) && $row['color'] == 'wood') { echo 'selected'; } ?>>Wood</option>
			              <option value="other" <?php if (isset($row['color']) && $row['color'] == 'other') { echo 'selected'; } ?>>Other</option>
	      				</select>
	      			</td>
	      		</tr>
	      		<tr>
	      			<td>Condition (0 - Unplayable, 5 - Like New): </td>
	      			<td>
	      				<select name="condition">
			              <option value="0" <?php if (isset($row['condition']) && $row['condition'] == '0') { echo 'selected'; } ?>>0</option>
			              <option value="1" <?php if (isset($row['condition']) && $row['condition'] == '1') { echo 'selected'; } ?>>1</option>
			              <option value="2" <?php if (isset($row['condition']) && $row['condition'] == '2') { echo 'selected'; } ?>>2</option>
			              <option value="3" <?php if (isset($row['condition']) && $row['condition'] == '3') { echo 'selected'; } ?>>3</option>
			              <option value="4" <?php if (isset($row['condition']) && $row['condition'] == '4') { echo 'selected'; } ?>>4</option>
			              <option value="5" <?php if (isset($row['condition']) && $row['condition'] == '5') { echo 'selected'; } ?>>5</option>
	      				</select>
	      			</td>
	      		</tr>
	      		<tr>
	      			<td>Description: </td>
	      			<td><textarea rows="5" cols="50" name="description" placeholder="Enter a description..."><?php
	      				if (isset($row['description'])) { echo $row['description']; }
	      			?></textarea></td>
	      		</tr>
	      		<tr>
	      			<td>Asking Price: </td>
	      			<td>&#36;<input type="text" name="price" value="<?php if (isset($row['price'])) { echo $row['price']; } ?>" required/>
	      				<input type="hidden" name="listingId" value="<?php if (isset($row['listingId'])) { echo $row['listingId']; } ?>" /></td>
	      		</tr>
	      		<tr>
	      			<td>Choose a Photo: <?php if (isset($row['photo']) && $row['photo'] != 'noImg.png') { echo '<a href="../../uploads/sellerImgs/'.$row['photo'].'" target="_blank">Current Photo</a>'; } ?></td>
	      			<td><input type="file" name="fileToUpload" id="fileToUpload"></td>
	      		</tr>
	      		<tr>
	      			<td><input type="submit" value="Cancel Changes" id="cancel"></td>
	      			<td><input type="submit" value="<?php echo $entry; ?> Listing" name="submit"></td>
      			</tr>
	      	</table>
	        </div>
	        </form>
    	</fieldset>
    <?php if ($added) { echo '<a href="sell.php?page=sell">Return to your sales list</a>'; } ?>
	</section>
    <?php include('footer.php'); ?>
    <script>
    	$(document).ready(function(){
    		$("#cancel").on("click", function(e){
    			e.preventDefault();
    			if (confirm("Discard all changes?")) {
    				window.location.href = "sell.php?page=sell";
    			}
    		});
    	});
    </script>
  </body>

</html>
