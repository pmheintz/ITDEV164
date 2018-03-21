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
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
      exit('<h4 class="alert">No query data. Please search from <a href="index.php">this</a> page.</h4></body></html>');
    }

    // Function to ensure html special characters are properly returned
    function hsc($str) { return htmlspecialchars($str); }

    // Build the sql
    $sql = 'SELECT * FROM houses WHERE county=:county';

    // Parameters for sql statement
    if (!empty($_POST['county'])) {
      $parameters = ['county'=>$_POST['county']];
    } else {
      $parameters = ['county'=>'Milwaukee'];
    }

    // Add any additional parameters
    if (!empty($_POST['city']) && $_POST['city'] != 'Any') {
      $sql .= ' AND city=:city';
      $parameters['city'] = $_POST['city'];
    }
    if (!empty($_POST['bedrooms']) && $_POST['bedrooms'] != 'Any') {
      $sql .= ' AND bedrooms>=:bedrooms';
      $parameters['bedrooms'] = $_POST['bedrooms'];
    }
    if (!empty($_POST['bathrooms']) && $_POST['bathrooms'] != 'Any') {
      $sql .= ' AND bathrooms>=:bathrooms';
      $parameters['bathrooms'] = $_POST['bathrooms'];
    }

    // Execute query
    try {
      // Prepare statement
      $stmt = $pdo->prepare($sql);
      // Execute statement with parameters
      $stmt->execute($parameters);
      // Get count
      $count = $stmt->rowCount();
    } catch (PDOException $e) {
      echo '<h4 class="alert">ERROR!</h4><p>Error message: '.$e->getMessage().'</p>';
    }

    // Display results
    if ($count !== 0) {
      // Display number of listings
      echo '<h4 class="primary">Number of listings found: '.$count.'</h4>'.PHP_EOL;
      // Display listings in containers
      while ($row = $stmt->fetch()) {
        echo '<div class="flex-container">'.PHP_EOL;
        echo '<div><image src="houseimages/'.hsc($row['image']).'" /></div>'.PHP_EOL;
        echo '  <div>'.PHP_EOL;
        echo '    <table style="margin: 1em;">'.PHP_EOL;
        echo '      <tr>'.PHP_EOL;
        echo '        <td>Address: </td>'.PHP_EOL;
        echo '        <td>'.hsc($row['address']).'<br />'.PHP_EOL;
        echo '            '.hsc($row['city']).', '.hsc($row['state']).'</td>'.PHP_EOL;
        echo '      </tr>'.PHP_EOL;
        echo '      <tr>'.PHP_EOL;
        echo '        <td>County: </td>'.PHP_EOL;
        echo '        <td>'.hsc($row['county']).'</td>'.PHP_EOL;
        echo '      </tr>'.PHP_EOL;
        echo '      <tr>'.PHP_EOL;
        echo '        <td>Bedrooms: </td>'.PHP_EOL;
        echo '        <td>'.$row['bedrooms'].'</td>'.PHP_EOL;
        echo '      </tr>'.PHP_EOL;
        echo '      <tr>'.PHP_EOL;
        echo '        <td>Bathrooms: </td>'.PHP_EOL;
        echo '        <td>'.$row['bathrooms'].'</td>'.PHP_EOL;
        echo '      </tr>'.PHP_EOL;
        echo '      <tr>'.PHP_EOL;
        echo '        <td>Description: </td>'.PHP_EOL;
        echo '        <td>'.hsc($row['description']).'</td>'.PHP_EOL;
        echo '      </tr>'.PHP_EOL;
        echo '      <tfoot>'.PHP_EOL;
        echo '        <tr>'.PHP_EOL;
        echo '          <td>Cost: </td>'.PHP_EOL;
        echo '          <td>$'.$row['cost'].'</td>'.PHP_EOL;
        echo '        </tr>'.PHP_EOL;
        echo '      </tfoot>'.PHP_EOL;
        echo '    </table>'.PHP_EOL;
        echo '  </div>'.PHP_EOL;
        echo '</div>'.PHP_EOL.'<br />'.PHP_EOL;
      }
    } else {
      echo '<h4 class="alert">No listings found. Please adjust search criteria.</h4>'.PHP_EOL;
    }
    echo '<a href="index.php">Return to search</a>';
    $pdo = null;

    ?>

  </body>
  </html>
