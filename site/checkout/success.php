<?php

session_start();

// We use a webhook to see whether order has been paid.
// This script only updates the order to 'mollie processing' and redirects to the Artisan shop.
require("../../dashboard/configuration.php");
include("../../dashboard/resources/PHP/Class.DB.php");
include("../../dashboard/resources/PHP/Cryptography.php");
include("../../resources/PHP/Artisan.php");

require_once("../mollie/vendor/autoload.php");
require_once("../mollie/examples/functions.php");
include("../mollie/examples/initialize.php");

$cryptography 	= new Cryptography;
$db 			= new sql();
$shop 			= new Artisan();

if(isset($_SESSION['paytoken']) && isset($_REQUEST['paytoken'])) { 
	
		if($_SESSION['paytoken'] === $_REQUEST['paytoken']) {
			
			// this should never happen
			if(!isset($_REQUEST['cartid'])) {
				header("Location: ../../cart");
				exit;	
			}
			// this should never happen
			if(!isset($_REQUEST['orderid'])) {
				header("Location: ../../cart");
				exit;	
			}
			
			$table    = '`shop.orders`';
			$column   = '`order.id`';
			$value    =  $shop->clean($_REQUEST['orderid'],'encode');
			$operator = '*';
			$result = $db->select($table,$operator,$column,$value);

			if(isset($result)) { 
				if($result[0]['order.token'] == $_REQUEST['paytoken']) {
					// Update database with a status of processing order.
					$table    = '`shop.orders`';
					$columns  = ['order.status'];
					$values   = ['mollie processing'];
					$db->update($table,$columns,$values,$result[0]['id']);
				}
			}
			
			// Empty session to prevent replay.
			unset($_SESSION['cartid']);
			unset($_SESSION['cart']);
			$_SESSION['cartid'] = [];
			$_SESSION['cart'] 	= [];
			session_destroy();
			
			// redirect to shop again.
			header("Location: ../../");
			exit;
		}
		
} else {
	// redirect to shop again.
	header("Location: ../../");
	exit;	
}

?>
<h1>Your order is being processed.</h1>
<p>
	<a href="<?php echo $shop->clean(WEBSITE,'encode');?>">Click here to go back to the webshop.</a>
</p>