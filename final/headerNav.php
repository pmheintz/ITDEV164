<header>
  <h1>Paul's Guitar Shop</h1>
  <h2>The place for buying and selling used guitars</h2>
</header>
<nav class="nav" id="myNav">
  <a href="index.php?page=home" <?php  if (!isset($_GET['page']) || $_GET['page'] == 'home') echo 'class="active"'; ?>>Home</a>
  <a href="listings.php?page=listings" <?php if (isset($_GET['page']) && $_GET['page'] == 'listings') echo 'class=active'; ?>>For Sale</a>
  <a href="sell.php?page=sell" <?php if (isset($_GET['page']) && $_GET['page'] == 'sell') echo 'class=active'; ?>>Sell Something</a>
  <?php if (isset($_SESSION['pgsLoggedIn']) && $_SESSION['pgsLoggedIn'] === true) { 
  	echo '<a href="logout.php?page=login">Log out</a>'; } else {
  		echo '<a href="login.php?page=login" ';
  		if (isset($_GET['page']) && $_GET['page'] == 'login') echo 'class=active'; 
  		echo '>Sign Up/Log In</a>';
  	} ?>
  <a href="javascript:void(0);" style="font-size:15px;" class="icon" onclick="respNav()">&#9776;</a>
</nav>