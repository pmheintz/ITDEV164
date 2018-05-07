<?php session_start();
if (!isset($_SESSION['detailReturnPage'])) { $_SESSION['detailReturnPage'] = 'sell.php?page=sell'; }?>
<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="UTF-8">
    <title>Sign up or log in</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="img/png" href="images/favicon.png">
    <link href="https://fonts.googleapis.com/css?family=Nothing+You+Could+Do|Abel" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/base.css" />
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <style type="text/css">td:first-child {text-align: right} td:last-child {text-align: left;} table {margin: auto; width: 100%;}</style>
  </head>
  <body>
    <?php include('headerNav.php'); ?>
    <section>
      <h3>Sign up or log in</h3>
      <div class="row">
        <div class="col-6" id="login">
          <h4 style="text-align: center;">Want to buy or sell something?<br />Sign up here</h4>
          <?php 
          if(!empty($_SESSION['errors'])) { 
            foreach ($_SESSION['errors'] as $error) {
              echo '<p style="color: red; margin: .25em;">'.$error.'</p>'.PHP_EOL;
            }
            unset($_SESSION['errors']);
          }?>
          <form name="signup" id="signup" action="register.php" method="post">
            <fieldset style="border: none;" 
            <?php if (isset($_SESSION['pgsLoggedIn']) && $_SESSION['pgsLoggedIn'] === true) { echo 'disabled="disabled">'.PHP_EOL.
            '<h4>Please log out before registering a new user</h4>'; } else { echo '>'; } ?>
            <table>
              <tr>
                <td>First Name: </td>
                <td><input type="text" name="fname" required
                  <?php if(isset($_SESSION['fname'])) { echo 'value="'.$_SESSION['fname'].'"'; } ?>/></td>
              </tr>
              <tr>
                <td>Last Name: </td>
                <td><input type="text" name="lname" required
                  <?php if(isset($_SESSION['lname'])) { echo 'value="'.$_SESSION['lname'].'"'; } ?>/></td>
              </tr>
              <tr>
                <td>Email: </td>
                <td><input type="email" name="email" required
                  <?php if(isset($_SESSION['email'])) { echo 'value="'.$_SESSION['email'].'"'; } ?>/></td>
              </tr>
              <tr>
                <td>Password: </td>
                <td><input type="password" name="password" id="password" required/></td>
              </tr>
              <tr>
                <td>Confirm Password: </td>
                <td><input type="password" name="cnfPassword" id="cnfPassword" required/></td>
              </tr>
              <tr>
                <td id="alert" style="color: red;"></td>
                <td><input type="submit" name="submit" value="Register" id="regBtn"/>
            </table>
            </fieldset>
          </form>
        </div>
        <div class="col-6">
          <h4 style="text-align: center;">Already registered?<br />Log in here</h4>
          <?php 
          $loginErrors = [];
          if(!empty($_SESSION['loginFail'])) { 
            $loginErrors = $_SESSION['loginFail'];
            unset($_SESSION['loginFail']);
            }
            foreach ($loginErrors as $loginErr) {
              echo '<p style="color: red; margin: .25em;">'.$loginErr.'</p>'.PHP_EOL;
            }?>
          <form name="login" id="loginForm" action="login_proc.php" method="post">
            <table>
              <tr>
                <td>Email: </td>
                <td><input type="email" name="loginEmail" required/></td>
              </tr>
              <tr>
                <td>Password: </td>
                <td><input type="password" name="loginPassword" id="loginPassword" required/></td>
              </tr>
              <tr>
                <td></td>
                <td><input type="hidden" name="returnUrl" 
                  <?php if (isset($_SESSION['loginReturnPage'])) { echo 'value="'.$_SESSION['loginReturnPage'].'"'; } else { echo 'value="sell.php?page=sell"'; } ?>
                  ></td>
              </tr>
              <tr>
                <td id="loginAlert" style="color: red;"></td>
                <td><input type="submit" name="login" value="Login" id="loginBtn"/>
            </table>
            </table>

        </div>
      </div>
      <!-- Password confimation JS -->
      <script type="text/javascript">
        $(document).ready(function(){
          $("#cnfPassword").keyup(function(){
            var password = $("#password").val();
            var cnfPassword = $("#cnfPassword").val();
            if (password != cnfPassword) {
              $("#alert").text("Passwords do not match").attr("style", "color: red;");
              $("#regBtn").prop("disabled", true);
            } else {
              $("#alert").text("Passwords match").attr("style", "color: lime;");
              $("#regBtn").prop("disabled", false);
            }
          });
        });
      </script>
    </section>
    <?php include('footer.php'); ?>
  </body>

</html>
