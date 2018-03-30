<!DOCTYPE html>
<?php
// Include db connection (Used require because it terminates the script on error)
require_once('dbconn.php');
// Create empty array to hold errors
$errors = [];
// Variable to determine if successfully added
$added = false;
// Path for image uploads
$targetDir = '../../uploads/houses/';
// Check if POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Check required fields
  if (empty($_POST['address'])) { $errors['address'] = 'Please enter an address.'; }
  if (empty($_POST['city'])) { $errors['city'] = 'Please enter a city.'; }
  if (empty($_POST['cost'])) { $errors['cost'] = 'Please enter an asking price.'; }
  if (empty($_POST['description'])) { $errors['description'] = 'Please enter a description.'; }

  // Check image attributes
  if (!empty($_FILES['fileToUpload']['name'])) {
    // Path with filename
    $targetFile = $targetDir.basename($_FILES['fileToUpload']['name']);

    // Get image size of upload file, will return false if not an image
    $check = getimagesize($_FILES['fileToUpload']['tmp_name']);
    // Set error if not an image
    if (!$check) {
      $errors['fileToUpload'] = 'File selected was not an image.';
    } else {
      // File is an image, check if file type is supported
      $imageFileType = pathinfo($targetFile,PATHINFO_EXTENSION);
      if ($imageFileType != 'jpg' && $imageFileType != 'png' && $imageFileType != 'jpeg' && $imageFileType != 'gif') {
        $errors['fileToUpload'] = 'Unsupported image format. <br />Please choose "jpg", "jpeg", "png", or "gif".';
      }
    }
    // Check if filename exists
    if (file_exists($targetFile)) {
      $errors['fileToUpload'] = 'File name exists. Please rename and try again.';
    }
    // Check image size (Max size 500000 bytes)
    if ($_FILES["fileToUpload"]["size"] > 500000) {
      $errors['fileToUpload'] = 'Photo exceeds maximum size. <br />Please choose a smaller photo.';
    }

    // Check if any errors occured, add listing to DB if not
    if (empty($errors)) {
      // Attempt to upload the image
      if (!move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $targetFile)) {
        $errors['fileToUpload'] = 'Listing added, but an error occured and the image wasn\'t uploaded';
      }

      // Base SQL statement
      $sql = "INSERT INTO houses (image, cost, address, city, state, bedrooms, bathrooms, description, county) "
              ."VALUES (:image, :cost, :address, :city, :state, :bedrooms, :bathrooms, :description, :county)";

      try {
        // Prepare statement
        $stmt = $pdo->prepare($sql);

        // Execute statment
        $stmt->execute(['image'=>$_FILES['fileToUpload']['name'], 'cost'=>str_replace(',', '', $_POST['cost']), 'address'=>$_POST['address'], 'city'=>$_POST['city'], 'state'=>$_POST['state'], 'bedrooms'=>$_POST['bedrooms'], 'bathrooms'=>$_POST['bathrooms'], 'description'=>$_POST['description'], 'county'=>$_POST['county']]);

        // Get rows
        $count = $stmt->rowCount();
      } catch (PDOException $e) {
        echo '<h4 class="alert">ERROR!</h4><p>Error message: '.$e->getMessage().'</p>';
      }

      // Mark as added
      $added = true;
    }
  } else {
    $errors['fileToUpload'] = 'Please enter a photo of the house';
  }
}
?>
<html>
  <head>
    <meta charset="UTF-8">
    <title>PHP real estate app</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Cinzel" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/base.css" />
  </head>
  <body>
    <header>
      <h1>Paul Heintz</h1>
      <h4>Class 10 assignment</h4>
      <h4>3/27/2018</h4>
      <h4>Real estate app</h4>
    </header>
    <hr />

    <!-- About -->
    <p>
      This is a form to add a house listing to the real estate app.
    </p>

    <!-- Content -->
    <fieldset <?php if ($added) { echo 'disabled'; } ?>>
      <legend><?php if ($added) { echo '<span class="alert">Listing added</span>'; } else { echo 'Add a listing'; } ?></legend>
      <form method="post" action="<?php echo hsc($_SERVER['PHP_SELF']);?>" enctype="multipart/form-data">
        <table>
          <tr>
            <td><label>Address: </label></td>
            <td><input type="text" name="address" value="<?php if (isset($_POST['address'])) { echo hsc($_POST['address']); } ?>"></td>
          </tr>
          <tr><td><span class="alert"><?php if (!empty($errors['address'])) { echo $errors['address']; } ?></span><td></tr>
          <tr>
            <td><label>City: </label></td>
            <td><input type="text" name="city" value="<?php if (isset($_POST['city'])) { echo hsc($_POST['city']); } ?>"></td>
          </tr>
          <tr><td><span class="alert"><?php if (!empty($errors['city'])) { echo $errors['city']; } ?></span><td></tr>
          <tr>
            <td><label>County: </label></td>
            <td><select name="county">
              <option value="Milwaukee" <?php if (isset($_POST['county']) && $_POST['county'] == "Milwaukee") { echo 'selected="selected"'; } ?>>Milwaukee</option>
              <option value="Waukesha" <?php if (isset($_POST['county']) && $_POST['county'] == "Waukesha") { echo 'selected="selected"'; } ?>>Waukesha</option>
            </select></td>
          </tr>
          <tr>
            <td><label>State: </label></td>
            <td><select name="state">
              <option value="WI" <?php if (isset($_POST['state']) && $_POST['state'] == "WI") { echo 'selected="selected"'; } ?>>Wisconsin</option>
            </select></td>
          </tr>
          <tr>
            <td><label>Bedrooms: </label></td>
            <td><select name="bedrooms">
              <option value="1" <?php if (isset($_POST['bedrooms']) && $_POST['bedrooms'] == "1") { echo 'selected="selected"'; } ?>>1</option>
              <option value="2" <?php if (isset($_POST['bedrooms']) && $_POST['bedrooms'] == "2") { echo 'selected="selected"'; } ?>>2</option>
              <option value="3" <?php if (isset($_POST['bedrooms']) && $_POST['bedrooms'] == "3") { echo 'selected="selected"'; } ?>>3</option>
              <option value="4" <?php if (isset($_POST['bedrooms']) && $_POST['bedrooms'] == "4") { echo 'selected="selected"'; } ?>>4</option>
              <option value="5" <?php if (isset($_POST['bedrooms']) && $_POST['bedrooms'] == "5") { echo 'selected="selected"'; } ?>>5</option>
            </select></td>
          </tr>
          <tr>
            <td><label>Bathrooms: </label></td>
            <td><select name="bathrooms">
              <option value="1" <?php if (isset($_POST['bathrooms']) && $_POST['bathrooms'] == "1") { echo 'selected="selected"'; } ?>>1</option>
              <option value="1.5" <?php if (isset($_POST['bathrooms']) && $_POST['bathrooms'] == "1.5") { echo 'selected="selected"'; } ?>>1.5</option>
              <option value="2" <?php if (isset($_POST['bathrooms']) && $_POST['bathrooms'] == "2") { echo 'selected="selected"'; } ?>>2</option>
              <option value="2.5" <?php if (isset($_POST['bathrooms']) && $_POST['bathrooms'] == "2.5") { echo 'selected="selected"'; } ?>>2.5</option>
              <option value="3" <?php if (isset($_POST['bathrooms']) && $_POST['bathrooms'] == "3") { echo 'selected="selected"'; } ?>>3</option>
              <option value="3.5" <?php if (isset($_POST['bathrooms']) && $_POST['bathrooms'] == "3.5") { echo 'selected="selected"'; } ?>>3.5</option>
              <option value="4" <?php if (isset($_POST['bathrooms']) && $_POST['bathrooms'] == "4") { echo 'selected="selected"'; } ?>>4</option>
              <option value="4.5" <?php if (isset($_POST['bathrooms']) && $_POST['bathrooms'] == "4.5") { echo 'selected="selected"'; } ?>>4.5</option>
              <option value="5" <?php if (isset($_POST['bathrooms']) && $_POST['bathrooms'] == "5") { echo 'selected="selected"'; } ?>>5</option>
            </select></td>
          </tr>
          <tr>
            <td><label>Asking Price <br /><span class="primary">(No commas or cents): </label></td>
            <td><input type="text" name="cost" value="<?php if (isset($_POST['cost'])) { echo hsc($_POST['cost']); } ?>"></td>
          </tr>
          <tr><td><span class="alert"><?php if (!empty($errors['cost'])) { echo $errors['cost']; } ?></span><td></tr>
          <tr>
            <td><label>Description: </label></td>
            <td><textarea name="description" rows="6" cols="50"  <?php if (empty($_POST['description'])) { echo 'placeholder="Please enter a brief description..."'; } ?>><?php if (isset($_POST['description'])) { echo hsc($_POST['description']); } ?></textarea></td>
          </tr>
          <tr><td><span class="alert"><?php if (!empty($errors['description'])) { echo $errors['description']; } ?></span><td></tr>
          <tr>
            <td><label>Choose a photo: </label></td>
            <td><input type="file" name="fileToUpload" id="fileToUpload"></td>
          </tr>
          <tr><td><span class="alert"><?php if (!empty($errors['fileToUpload'])) { echo $errors['fileToUpload']; } ?></span><td></tr>
          <tr>
            <td></td>
            <td><input type="submit" value="Add Listing" name="submit"></td>
          </tr>  
        </table>
      </form>
    </fieldset>

    <?php if ($added) { echo '<h4 class="primary">'.$count.' listing added.</h4>'.PHP_EOL.'<p><a href="index.php">Return to search.</a></p>'; }
      // Clear PDO object
      $pdo = null;
    ?>
  </body>
  </html>