<?php
// Database connection attributes
DEFINE('DB_USER', 'phpheintzpm');
DEFINE('DB_PASSWORD', '1154112');
DEFINE('DB_HOST', 'mca.matc.edu');
DEFINE('DB_NAME', 'phpheintzpm');
DEFINE('CHARSET', 'utf8mb4');

// Set data source name
$dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset='.CHARSET;
// Set additional options
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// Establish connection
try {
	$pdo = new PDO($dsn, DB_USER, DB_PASSWORD, $opt);
}
catch (PDOException $e) {
    echo 'Connection failed: '.$e->getMessage();
}

// Function to ensure html special characters are properly displayed
function hsc($str) { return htmlspecialchars($str); }

// Function to get listings
function getListings($userId, $pdo) {
	$listings = [];
	// Base sql statement
	$sql = 'SELECT * FROM guitars';
	// Determine if looking for 1 seller or for all listings
	if ($userId != 'all') {
		$sql .= ' WHERE sellerId='.$userId;
	}

	try {
		// Prepare statement
		$stmt = $pdo->prepare($sql);
		// Execute statement
		$stmt->execute();
		$i = 0;
		while ($row = $stmt->fetch()) {
			$listings[$i] = $row;
			$i++;
		}
		// Get number of listings
		$listings['rows'] = $stmt->rowCount();
	} catch (PDOException $e) {
		$listings['error'] = 'Database error: '.$e->getMessage();
	}
	$pdo = null;
	return $listings;
}

// Function to get a single listing
function getOneListing($listingId, $pdo) {
	// Base sql statement
	$sql = 'SELECT * FROM guitars WHERE listingId='.$listingId;

	try {
		// Prepare statement
		$stmt = $pdo->prepare($sql);
		// Execute statement
		$stmt->execute();
		// Return row
		$row = $stmt->fetch();
	} catch (PDOException $e) {
		echo '<h3 style="color: red">* Critical Error: '.$e.'</h3>';
		exit();
	}
	$pdo = null;
	return $row;
}

// Function to create a placeholder listing
function createPlaceholder($userId, $pdo) {
	// Base sql
	$sql = "INSERT INTO guitars (sellerId, make, model, `type`, numStrings, color, `condition`, description, price, photo) VALUES (:sellerId, :make, :model, :type, :numStrings, :color, :condition, :description, :price, :photo)";

	try {
		// Prepare statement
		$stmt = $pdo->prepare($sql);

		// Execute statement
		$stmt->execute(['sellerId'=>$userId, 'make'=>'Creating Listing...', 'model'=>'Creating Listing...', 'type'=>'electric', 'numStrings'=>6, 'color'=>'Creating Listing...', 'condition'=>5, 'description'=>'Creating Listing...', 'price'=>'Creating Listing...', 'photo'=>'noImg.png']);

		// Listing ID
		$listing = $pdo->lastInsertId();
    } catch (PDOException $e) {
    	$pdo = null;
	    exit($e);
    }
    return $listing;
}

// Function to add a row
function addListing($params, $imgName, $userId, $pdo) {
	// Base sql
	$sql = "INSERT INTO guitars (sellerId, make, model, `type`, numStrings, color, `condition`, description, price, photo) VALUES (:sellerId, :make, :model, :type, :numStrings, :color, :condition, :description, :price, :photo)";

	try {
		// Prepare statement
		$stmt = $pdo->prepare($sql);

		// Execute statement
		$stmt->execute(['sellerId'=>$userId, 'make'=>$params['make'], 'model'=>$params['model'], 'type'=>$params['type'], 'numStrings'=>$params['numStrings'], 'color'=>$params['color'], 'condition'=>$params['condition'], 'description'=>$params['description'], 'price'=>$params['price'], 'photo'=>$imgName]);
    } catch (PDOException $e) {
    	$pdo = null;
	    exit($e);
    }
    return true;
}

// Function to update a row
function updateListing($params, $listingId, $pdo) {
	// Base sql
	$sql = 'UPDATE guitars SET make=:make, model=:model, `type`=:type, numStrings=:numStrings, color=:color, `condition`=:condition, description=:description, price=:price, photo=:photo WHERE listingId='.$listingId;

	try {
		// Prepare statement
		$stmt = $pdo->prepare($sql);

		// Execute statement
		$stmt->execute(['make'=>$params['make'], 'model'=>$params['model'], 'type'=>$params['type'], 'numStrings'=>$params['numStrings'], 'color'=>$params['color'], 'condition'=>$params['condition'], 'description'=>$params['description'], 'price'=>$params['price'], 'photo'=>$params['photo']]);
    } catch (PDOException $e) {
    	$pdo = null;
	    exit($e);
    }
    return true;
}

// Function to return last listing ID
function getLastListingId ($sellerId, $pdo) {
	$sql = "SELECT listingId FROM guitars WHERE sellerId=$sellerId ORDER BY listingId DESC LIMIT 1;";
	try {
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$row = $stmt->fetch();
		$pdo = null;
		return $row['listingId'];
	} catch (PDOException $e) {
		$pdo = null;
		return $e->getMessage();
    }
}

// Function to delete a row
function deleteListing ($listingId, $pdo) {
	$sql = "DELETE FROM guitars WHERE listingId=:listingId";

	try {
		// Prepare statement
		$stmt = $pdo->prepare($sql);

		// Execute statement
		$stmt->execute(['listingId'=>$listingId]);

		$success = $stmt->rowCount();
		$pdo = null;
		return $success;
	} catch (PDOException $e) {
		$pdo = null;
		return $e->getMessage();
	}
}

