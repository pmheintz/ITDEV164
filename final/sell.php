<?php 
session_start(); 
require_once('dbconn.php');
?>
<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="UTF-8">
    <title>Sell a guitar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="img/png" href="images/favicon.png">
    <link href="https://fonts.googleapis.com/css?family=Nothing+You+Could+Do|Abel" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="css/base.css" />
    <!-- Delete Confirmation -->
    <script>
      $(document).ready(function(){
        $("a.deleteBtn").on("click", function(e){
          if (!confirm("Delete this listing?")) {
            e.preventDefault();
            return false;
          }
          return true;
        });
      });
    </script>
  </head>
  <body>
    <?php include('headerNav.php'); ?>
    <section>
      <?php 
      if (!isset($_SESSION['pgsLoggedIn']) || $_SESSION['pgsLoggedIn'] !== true) {
        echo '      <script>';
        echo '        $(document).ready(function(){';
        echo '          alert("Please register or login first.");';
        echo '          window.location.href = "login.php?page=login";';
        echo '        });';
        echo '      </script>';
      }
      ?>
      <h3>Welcome <?php if (isset($_SESSION['fname'])) { echo $_SESSION['fname']; } ?></h3>
      <?php 
      if (isset($_SESSION['userId'])) {
        $userListings = getListings($_SESSION['userId'], $pdo); 
        if (isset($userListings['error'])) { echo '<h3 style="color: red">'.$userListings['error'].'</h3>'; exit(); }
      }
      ?>
      <h4>You have <?php if (isset($userListings['rows'])) {
        echo $userListings['rows'];
        } else { echo '0'; } ?> items currently posted for sale.<br />
          Click <a href="listingForm.php?page=sell">here</a> to post a new item for sale.</h4>
      <div style="overflow-x:auto;">
      <table class="userListings">
      <?php
      if (isset($userListings[0])) {
          echo '<tr>'.PHP_EOL;
          echo '  <td colspan="6">Guitars you currently have listed</td>'.PHP_EOL;
          echo '</tr>'.PHP_EOL;
          echo '<tr>'.PHP_EOL;
          echo '  <th></th>'.PHP_EOL;
          echo '  <th>Manufactorer</th>'.PHP_EOL;
          echo '  <th>Model</th>'.PHP_EOL;
          echo '  <th>Type</th>'.PHP_EOL;
          echo '  <th>Color</th>'.PHP_EOL;
          echo '  <th>Price</th>'.PHP_EOL;
          echo '</tr>'.PHP_EOL;
        $i = 0;
        while (isset($userListings[$i])) {
          echo '<tr>'.PHP_EOL;
          echo '  <td><a href="listingForm.php?page=sell&listingId='.$userListings[$i]['listingId'].'">Edit</a> <a href="delete.php?page=sell&listingId='.$userListings[$i]['listingId'].'" " class="deleteBtn">Delete</a></td>'.PHP_EOL;
          echo '  <td>'.$userListings[$i]['make'].'</td>'.PHP_EOL;
          echo '  <td>'.$userListings[$i]['model'].'</td>'.PHP_EOL;
          echo '  <td>'.$userListings[$i]['type'].'</td>'.PHP_EOL;
          echo '  <td>'.$userListings[$i]['color'].'</td>'.PHP_EOL;
          echo '  <td>'.$userListings[$i]['price'].'</td>'.PHP_EOL;
          echo '</tr>'.PHP_EOL;
          $i++;
        }
      }
      ?>
      </table>
      </div>
    </section>
    <?php include('footer.php'); ?>
  </body>

</html>
