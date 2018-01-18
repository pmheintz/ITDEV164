<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Simple PHP Display</title>
  </head>
  <body>

    <?php
    if (!empty($_REQUEST['firstname'])) {
      $firstname = $_REQUEST['firstname'];
      echo "<p>Thank you, $firstname for filling out the form</p>";
    } else {
      $firstname = "";
      echo "<p>You forgot to enter your first name!</p>";
    }
    ?>

  </body>
</html>
