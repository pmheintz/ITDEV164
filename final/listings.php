<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="UTF-8">
    <title>For Sale</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="img/png" href="images/favicon.png">
    <link href="https://fonts.googleapis.com/css?family=Nothing+You+Could+Do|Abel" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/base.css" />
  </head>
  <body>
    <?php include('headerNav.php'); ?>
    <section>
      <h3>For sale listings</h3>
      <h4>Current number of guitars/basses for sale: </h4>

      <!-- Form to narrow results -->
      <fieldset>
        <legend>Filter Results</legend>
        <form name="filterListings">
          <div class="row">
            <div class="col-4">
            <label> &nbsp;&nbsp;Type: </label>
            <select name="type" id="type">
              <option value="all" selected="selected">All</option>
              <option value="electric">Electric Guitar</option>
              <option value="acoustic">Acoustic Guitar</option>
              <option value="bass">Electric Bass</option>
              <option value="acousticBass">Acoustic Bass</option>
            </select>
            </div>
            <div class="col-4">
            <label> &nbsp;&nbsp;Manufactorer: </label>
            <select name="make" id="make">
              <option value="all" selected="selected">All</option>
              <option value="placeholder">Populate</option>
              <option value="placeholder">From</option>
              <option value="placeholder">Database</option>
            </select>
            </div>
            <div class="col-4">
            <label> &nbsp;&nbsp;Model: </label>
            <select name="model" id="model">
              <option value="all" selected="selected">All</option>
              <option value="placeholder">Populate</option>
              <option value="placeholder">From</option>
              <option value="placeholder">Database</option>
            </select>
            </div>
          </div>
          <div class="row">
            <div class="col-4">
            <label> &nbsp;&nbsp;Strings: </label>
            <select name="strings" id="strings">
              <option value="all" selected="selected">All</option>
              <option value="4">4</option>
              <option value="5">5</option>
              <option value="6">6</option>
              <option value="7">7</option>
              <option value="12">12</option>
            </select>
            </div>
            <div class="col-4">
            <label> &nbsp;&nbsp;Base Color: </label>
            <select name="color" id="color">
              <option value="all" selected="selected">All</option>
              <option value="white">White</option>
              <option value="black">Black</option>
              <option value="blue">Blue</option>
              <option value="green">Green</option>
              <option value="red">Red</option>
              <option value="yellow">Yellow/Wood</option>
              <option value="other">Other</option>
            </select>
            </div>
            <div class="col-4">
            <label> &nbsp;&nbsp;Max Price: </label>
            <select name="price" id="price">
              <option value="all" selected="selected">Any</option>
              <option value="100">&#36;100</option>
              <option value="250">&#36;250</option>
              <option value="500">&#36;500</option>
              <option value="1000">&#36;1000</option>
              <option value="2000">&#36;2000</option>
            </select>
            </div>
          </div>
        </form>
      </fieldset>
    </section>

    <!-- Listings -->
    <section class="listingOverview">
      <a href="#">
      <div class="listing row">
        <br />
        <div class="col-11">
          <p>
            <b>Manufactorer: </b>Ibanez<br />
            <b>Model: </b>S<br />
            <b>Type: </b>Electric Guitar<br />
            <b>Asking Price: </b>$250.00<br />
            Click for more details
          </p>
        </div>
        <div class="col-1">
          <br />
          <img src="images/ibanez.png" />
        </div>
      </div>
      </a>
      <hr />

      <a href="#">
      <div class="listing row">
        <br />
        <div class="col-11">
          <p>
            <b>Manufactorer: </b>Ibanez<br />
            <b>Model: </b>S<br />
            <b>Type: </b>Electric Guitar<br />
            <b>Asking Price: </b>$250.00<br />
            Click for more details
          </p>
        </div>
        <div class="col-1">
          <br />
          <img src="images/ibanez.png" />
        </div>
      </div>
      </a>
      <hr />

      <a href="#">
      <div class="listing row">
        <br />
        <div class="col-11">
          <p>
            <b>Manufactorer: </b>Ibanez<br />
            <b>Model: </b>S<br />
            <b>Type: </b>Electric Guitar<br />
            <b>Asking Price: </b>$250.00<br />
            Click for more details
          </p>
        </div>
        <div class="col-1">
          <br />
          <img src="images/ibanez.png" />
        </div>
      </div>
      </a>
      <hr />
    </section>
    <?php include('footer.php'); ?>
  </body>

</html>
