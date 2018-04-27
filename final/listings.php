<?php 
session_start(); 
require_once('dbconn.php');
// Set filter parameters if set
$filterParams = [];
if (isset($_GET['type']) && $_GET['type'] != 'all') { $filterParams['type'] = $_GET['type']; }
if (isset($_GET['make']) && $_GET['make'] != 'all') { $filterParams['make'] = $_GET['make']; }
if (isset($_GET['model']) && $_GET['model'] != 'all') { $filterParams['model'] = $_GET['model']; }
if (isset($_GET['numStrings']) && $_GET['numStrings'] != 'all') { $filterParams['numStrings'] = $_GET['numStrings']; }
if (isset($_GET['color']) && $_GET['color'] != 'all') { $filterParams['color'] = $_GET['color']; }
if (isset($_GET['price']) && $_GET['price'] != 'all') { $filterParams['price'] = $_GET['price']; }
$_SESSION['listingResultsPage'] = 'listings.php?'.$_SERVER['QUERY_STRING'];
?>
<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="UTF-8">
    <title>For Sale</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="img/png" href="images/favicon.png">
    <link href="https://fonts.googleapis.com/css?family=Nothing+You+Could+Do|Abel" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/base.css" />
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script>
      $(document).ready(function() {
        $("#clear").on("click", function(e) {
          e.preventDefault();
          window.location.href = "listings.php?page=listings";
        });
      });
    </script>
  </head>
  <body>
    <?php 
    include('headerNav.php');
    if (!empty($filterParams)) {
      $listings = getFilteredListings($filterParams, $pdo);
    } else {
      $listings = getListings('all', $pdo);
    }
    ?>
    <section>
      <h3>For sale listings</h3>
      <h4>Current number of guitars/basses for sale: <?php echo $listings['rows']; ?></h4>

      <!-- Form to narrow results -->
      <fieldset>
        <legend>Filter Results</legend>
        <form name="filterListings" action="listings.php?page=listings" action="get">
          <input type="hidden" name="page" value="listings">
          <div class="row">
            <div class="col-4">
            <label> &nbsp;&nbsp;Type: </label>
            <select name="type" id="type">
              <option value="all" <?php if (isset($_GET['type']) && $_GET['type'] == 'all' || !isset($_GET['type'])) { echo 'selected="selected"'; } ?>>All</option>
              <option value="electric" <?php if (isset($_GET['type']) && $_GET['type'] == 'electric') { echo 'selected="selected"'; } ?>>Electric Guitar</option>
              <option value="acoustic" <?php if (isset($_GET['type']) && $_GET['type'] == 'acoustic') { echo 'selected="selected"'; } ?>>Acoustic Guitar</option>
              <option value="bass" <?php if (isset($_GET['type']) && $_GET['type'] == 'bass') { echo 'selected="selected"'; } ?>>Electric Bass</option>
              <option value="acousticBass" <?php if (isset($_GET['type']) && $_GET['type'] == 'acousticBass') { echo 'selected="selected"'; } ?>>Acoustic Bass</option>
            </select>
            </div>
            <div class="col-4">
            <label> &nbsp;&nbsp;Manufactorer: </label>
            <select name="make" id="make">
              <option value="all" <?php if (isset($_GET['make']) && $_GET['make'] == 'all' || !isset($_GET['make'])) { echo 'selected="selected"'; } ?>>All</option>
              <?php
              $column = getDistinct('make', $pdo);
              foreach ($column as $record) {
                echo '<option value="'.$record.'" ';
                if (isset($_GET['make']) && $_GET['make'] == $record) { echo 'selected="selected"'; }
                echo '>'.$record.'</option>'.PHP_EOL;
              }
              ?>
            </select>
            </div>
            <div class="col-4">
            <label> &nbsp;&nbsp;Model: </label>
            <select name="model" id="model">
              <option value="all" <?php if (isset($_GET['model']) && $_GET['model'] == 'all' || !isset($_GET['model'])) { echo 'selected="selected"'; } ?>>All</option>
              <?php
              $column = getDistinct('model', $pdo);
              foreach ($column as $record) {
                echo '<option value="'.$record.'" ';
                if (isset($_GET['model']) && $_GET['model'] == $record) { echo 'selected="selected"'; }
                echo '>'.$record.'</option>'.PHP_EOL;
              }
              ?>
            </select>
            </div>
          </div>
          <div class="row">
            <div class="col-4">
            <label> &nbsp;&nbsp;Strings: </label>
            <select name="numStrings" id="numStrings">
              <option value="all" <?php if (isset($_GET['numStrings']) && $_GET['numStrings'] == 'all' || !isset($_GET['numStrings'])) { echo 'selected="selected"'; } ?>>All</option>
              <option value="4" <?php if (isset($_GET['numStrings']) && $_GET['numStrings'] == '4') { echo 'selected="selected"'; } ?>>4</option>
              <option value="5" <?php if (isset($_GET['numStrings']) && $_GET['numStrings'] == '5') { echo 'selected="selected"'; } ?>>5</option>
              <option value="6" <?php if (isset($_GET['numStrings']) && $_GET['numStrings'] == '6') { echo 'selected="selected"'; } ?>>6</option>
              <option value="7" <?php if (isset($_GET['numStrings']) && $_GET['numStrings'] == '7') { echo 'selected="selected"'; } ?>>7</option>
              <option value="12" <?php if (isset($_GET['numStrings']) && $_GET['numStrings'] == '12') { echo 'selected="selected"'; } ?>>12</option>
            </select>
            </div>
            <div class="col-4">
            <label> &nbsp;&nbsp;Base Color: </label>
            <select name="color" id="color">
              <option value="all" <?php if (isset($_GET['color']) && $_GET['color'] == 'all' || !isset($_GET['color'])) { echo 'selected="selected"'; } ?>>All</option>
              <option value="white" <?php if (isset($_GET['color']) && $_GET['color'] == 'white') { echo 'selected="selected"'; } ?>>White</option>
              <option value="black" <?php if (isset($_GET['color']) && $_GET['color'] == 'black') { echo 'selected="selected"'; } ?>>Black</option>
              <option value="blue" <?php if (isset($_GET['color']) && $_GET['color'] == 'blue') { echo 'selected="selected"'; } ?>>Blue</option>
              <option value="green" <?php if (isset($_GET['color']) && $_GET['color'] == 'green') { echo 'selected="selected"'; } ?>>Green</option>
              <option value="red" <?php if (isset($_GET['color']) && $_GET['color'] == 'red') { echo 'selected="selected"'; } ?>>Red</option>
              <option value="wood" <?php if (isset($_GET['color']) && $_GET['color'] == 'wood') { echo 'selected="selected"'; } ?>>Wood</option>
              <option value="other" <?php if (isset($_GET['color']) && $_GET['color'] == 'other') { echo 'selected="selected"'; } ?>>Other</option>
            </select>
            </div>
            <div class="col-4">
            <label> &nbsp;&nbsp;Max Price: </label>
            <select name="price" id="price">
              <option value="all" <?php if (isset($_GET['price']) && $_GET['price'] == 'all' || !isset($_GET['price'])) { echo 'selected="selected"'; } ?>>Any</option>
              <option value="100" <?php if (isset($_GET['price']) && $_GET['price'] == '100') { echo 'selected="selected"'; } ?>>&#36;100</option>
              <option value="250" <?php if (isset($_GET['price']) && $_GET['price'] == '250') { echo 'selected="selected"'; } ?>>&#36;250</option>
              <option value="500" <?php if (isset($_GET['price']) && $_GET['price'] == '500') { echo 'selected="selected"'; } ?>>&#36;500</option>
              <option value="1000" <?php if (isset($_GET['price']) && $_GET['price'] == '1000') { echo 'selected="selected"'; } ?>>&#36;1000</option>
              <option value="2000" <?php if (isset($_GET['price']) && $_GET['price'] == '2000') { echo 'selected="selected"'; } ?>>&#36;2000</option>
            </select>
            </div>
          </div>
          <div class="row">
            <div class="col-4"><input type="submit" value="Clear All Filters" id="clear" /> <input type="submit" value="Filter Results" /></div>
            <div class="col-8"></div>
          </div>
        </form>
      </fieldset>
    </section>

    <!-- Listings -->
    <section class="listingOverview">
    <?php  
    if ($listings['rows'] != 0) {
      unset($listings['rows']);
      foreach ($listings as $listing) {
      echo '<a href="detailView.php?page=listings&listingId='.$listing['listingId'].'">';
      echo '<div class="listing row">';
      echo '  <br />';
      echo '  <div class="col-11">';
      echo '    <p>';
      echo '      <b>Manufactorer: </b>'.$listing['make'].'<br />';
      echo '      <b>Model: </b>'.$listing['model'].'<br />';
      echo '      <b>Type: </b>'.$listing['type'].'<br />';
      echo '      <b>Asking Price: </b>'.$listing['price'].'<br />';
      echo '      Click for more details';
      echo '    </p>';
      echo '  </div>';
      echo '  <div class="col-1">';
      echo '    <br />';
      echo '    <img src="../../uploads/sellerImgs/'.$listing['photo'].'" />';
      echo '  </div>';
      echo '</div>';
      echo '</a>';
      echo '<hr />';
      }
    } else {
      echo '<h3>Sorry, currently nothing is for sale.</h3>';
    }
    ?>
    </section>
    <?php include('footer.php'); ?>
  </body>

</html>
