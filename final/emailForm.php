<?php
session_start();
require_once('dbconn.php');
$_SESSION['loginReturnPage'] = 'detailView.php?'.$_SERVER['QUERY_STRING'];
$sent = false;
if (isset($_POST['sent'])) { $sent = $_POST['sent']; }
?>
<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="UTF-8">
    <title>Email seller</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="img/png" href="images/favicon.png">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Nothing+You+Could+Do|Abel" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/base.css" />
  </head>
  <body>
    <?php include('headerNav.php');
	// Redirect if not logged in
	if (!isset($_SESSION['pgsLoggedIn']) || !$_SESSION['pgsLoggedIn']) {
		exit('<script>if (!confirm("You must register or login to email a seller.")) { window.location = "detailView.php?page=listings&listingId='.$_GET['listingId'].'"; } else { window.location = "login.php?page=login"; }</script>');
	} else {
		if ($_SERVER['REQUEST_METHOD'] != 'POST') {
			$listing = getOneListing($_GET['listingId'], $pdo);
		} else {
			$listing = getOneListing($_POST['listingId'], $pdo);
		}
		$seller = getUser($listing['sellerId'], $pdo);
		$subject = $listing['make'].' '.$listing['model'].' on Paul\'s Guitar Shop';
		if (isset($_POST['send'])) {
		    // Validate the email addresses first
		    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
		    $replyEmail = filter_input(INPUT_POST, 'replyEmail', FILTER_VALIDATE_EMAIL);
		    // Process the form only if the email is valid
		    if ($email && $replyEmail) {
		    	$to = $seller['email'];
		    	if (isset($_POST['cc']) && $_POST['cc']) { $to .= ', '.$replyEmail; }
		    	$from = 'paul@mca.matc.edu';
		    	$message = $_POST['message'];
		    	$headers = "From: $from\r\nReply-to: $replyEmail";
		    	$sent = mail($to, $subject, $message, $headers);
		    	if ($sent) {
		    		echo '<h3>Your message has been sent!</h3>';
		    	}
		    } else {
		    	echo '<h3 style="color: red;">Unable to send email. Please contact support!</h3>';
		    	exit();
		    }
		}
	}
	?>
    <section>
    	<h3>Email the seller</h3>
  		<fieldset <?php if ($sent) { echo 'disabled'; }?>>
  		<form method="post" action="<?php echo hsc($_SERVER['PHP_SELF']).'?page=listings';?>">
  			<input type="checkbox" name="cc" value="true" <?php if (isset($_POST['cc']) && $_POST['cc']) { echo 'checked'; }?>>
  			<label>Send copy to self?</label><br /><br />
  			<label>Subject: &nbsp;&nbsp;&nbsp;</label>
  			<input type="text" name="subject" style="width: 30em" value="<?php echo $subject; ?>" disabled><br /><br />
  			<p>Edit this message to your liking.</p>
  			<label style="vertical-align: top;">Message: &nbsp;&nbsp;</label>
  			<textarea name="message" cols="50" rows="10">Hello <?php echo $seller['fname']; ?>,&#13;&#10;&#13;&#10;I'm interested in the <?php echo $listing['make'].' '.$listing['model']; ?> you have listed on Paul's Guitar Shop. Please reply to this message so we can talk about it.&#13;&#10;&#13;&#10;Thanks,&#13;&#10;<?php echo $_SESSION['fname']; ?>
  			</textarea><br />
  			<input type="hidden" name="email" value="<?php echo $seller['email']; ?>" />
  			<input type="hidden" name="replyEmail" value="<?php echo $_SESSION['email']; ?>" />
  			<input type="hidden" name="listingId" value="<?php echo $listing['listingId']; ?>" />
  			<input type="hidden" name="sent" value="<?php echo $sent; ?>" />
  			<input type="submit" value="Cancel" id="cancel"> 
  			<input type="submit" name="send" value="Send Email!" />
  		</form>
		</fieldset>
		<?php
		if ($sent) { echo '<a href="'.$_SESSION['detailPage'].'">Return to listing.</a>'; }
		?>
    </section>
    <?php include('footer.php'); ?>
    <script>
    	$(document).ready(function(){
    		$("#cancel").on("click", function(e){
    			e.preventDefault();
    			if (confirm("Discard all changes?")) {
    				window.location.href = "<?php echo $_SESSION['loginReturnPage']; ?>";
    			}
    		});
    	});
    </script>
  </body>

</html>