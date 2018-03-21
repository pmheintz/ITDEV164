<!DOCTYPE html>
<?php
// Include db connection (Used require because it terminates the script on error)
require_once('dbconn.php');
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
      <h4>Class 9 assignment</h4>
      <h4>3/20/2018</h4>
      <h4>Real estate app</h4>
    </header>
    <hr />

    <!-- About -->
    <p>
      This is a fictional real estate app.
    </p>

    <?php
    // Check if there are records in the database
    $stmt = $pdo->query('SELECT id FROM houses');
    if (!$row = $stmt->fetch()) {
      exit('<h4 class="alert">No houses available in database</h4>');
    }

    // Function to ensure html special characters are properly returned
    function hsc($str) { return htmlspecialchars($str); }
    ?>

    <!-- Content -->
    <fieldset>
      <legend>Find a house</legend>
      <form method="post" action="houses.php">
        <table>
          <tr>
            <td><label>County <span class="alert">(required)</span>: </label></td>
            <td><select name="county">
              <option value="Milwaukee" selected="selected">Milwaukee</option>
              <option value="Waukesha">Waukesha</option>
            </select></td>
          </tr>
          <tr>
            <td><label>City <span class="primary">(optional)</span>: </label></td>
            <td><select name="city">
              <?php
                $stmt = $pdo->query('SELECT DISTINCT city FROM houses ORDER BY city ASC');
                echo '<option value="Any">Any</option>';
                while ($row = $stmt->fetch()) {
                  echo '<option value="'.hsc($row['city']).'">'.hsc($row['city']).'</option>'.PHP_EOL;
                }
              ?>
            </select></td>
          </tr>
          <tr>
            <td><label>Minimum Bedrooms <span class="primary">(optional)</span>: </label></td>
            <td><select name="bedrooms">
              <?php
                $stmt = $pdo->query('SELECT DISTINCT bedrooms FROM houses ORDER BY bedrooms ASC');
                echo '<option value="Any">Any</option>';
                while ($row = $stmt->fetch()) {
                  echo '<option value="'.$row['bedrooms'].'">'.$row['bedrooms'].'</option>'.PHP_EOL;
                }
              ?>
            </select></td>
          </tr>
          <tr>
            <td><label>Minimum Bathrooms <span class="primary">(optional)</span>: </label></td>
            <td><select name="bathrooms">
              <?php
                $stmt = $pdo->query('SELECT DISTINCT bathrooms FROM houses ORDER BY bathrooms ASC');
                echo '<option value="Any">Any</option>';
                while ($row = $stmt->fetch()) {
                  echo '<option value="'.$row['bathrooms'].'">'.$row['bathrooms'].'</option>'.PHP_EOL;
                }
              ?>
            </select></td>
          </tr>
          </tr>
          <tr>
            <td></td>
            <td><input type="submit" value="Search"></td>
          </tr>
        </table>
      </form>
    </fieldset>
    <?php $pdo = null; ?>

  </body>
  </html>