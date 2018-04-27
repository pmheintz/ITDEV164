<?php
require_once('dbconn.php');
$params = array("type"=>"bass", "make"=>"Rickenbacker", "model"=>"4003W", 
	"numStrings"=>"4", "color"=>"wood", "price"=>"2000");
$sql = 'WHERE ';
$execute = [];
foreach ($params as $key => $value) {
	$comp = '=';
	if ($key == 'price') { $comp = '<='; }
	$sql .= $key.$comp.':'.$key.' AND ';
	$execute[':'.$key] = $value;
}
$sql = substr($sql, 0, -5);
var_dump($execute);
$stmt = $pdo->prepare($sql);
$stmt->execute($execute);

echo '<br><br>';
$temp = ['sellerId'=>$userId, 'make'=>'Creating Listing...', 'model'=>'Creating Listing...', 'type'=>'electric', 'numStrings'=>6, 'color'=>'Creating Listing...', 'condition'=>5, 'description'=>'Creating Listing...', 'price'=>'Creating Listing...', 'photo'=>'noImg.png'];
var_dump($temp);
/*
foreach (array_keys($params) as $key) {
	if (!empty($params[$key])) {
		$sql .= $key;
		if ($key == 'price') {
			$sql .= '<=';
		} else {
			$sql .= '=';
		}
		$sql .= ':'.$key.' AND ';
	}
}
*/

/*
$column = getDistinct('make', $pdo);
var_dump($column);
foreach ($column as $record) {
	//var_dump($record);
	//echo '<br>';
}
*/
?>