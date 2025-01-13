<?php
session_start();

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

if(isset($_SESSION['token'])) {
	$token = $_SESSION['token'];
	} else {
	$token = $cryptography->getToken();
	$_SESSION['token'] = $token;
}

if(isset($_SESSION['paytoken'])) {
	$paytoken = $_SESSION['paytoken'];
	} else {
	$paytoken = $cryptography->getToken();
	$_SESSION['paytoken'] = $paytoken;
}

	if(isset($_SESSION['token']) && isset($_POST['token'])) { 
	
		if($_SESSION['token'] === $_POST['token']) {
			
			if(!isset($_SESSION['cart'])) {
				header("Location: ../../cart");
				exit;
			}
			
			$settings = $db->query("SELECT * from `shop.settings`"); 
			
			if(isset($_SESSION['cartid'])) {
				$cartid  = $_SESSION['cartid'];
				$orderid = $_SESSION['orderid'];
				} else {
				$cartid  				= 'C' . time().'-'.$cryptography->uniqueID();
				$orderid 				= 'O' . time().'-'.$cryptography->uniqueID();
				$_SESSION['cartid'] 	= $cartid;
				$_SESSION['orderid']	= $orderid;		
			}
			
			$ip		 = $_SERVER['REMOTE_ADDR'];
			$total	 = 0;
			
			// check if cart already exists
			$result_cart_table    = '`shop.cart`';
			$result_cart_column   = '`cart.id`';
			$result_cart_value    =  $cartid;
			$result_cart_operator = '*';
			$result_cart = $db->select($result_cart_table,$result_cart_operator,$result_cart_column,$result_cart_value);
			
			for($i=0;$i<count($_SESSION['cart']);$i++) {
				
				if(count($result_cart) <=0) { 
					// Insert cart contents into database
					$table    = '`shop.cart`';
					$columns  = ['`cart.id`','`cart.created`','`cart.product`','`cart.product.id`','`cart.qty`','`cart.ip`','`cart.stage`','`cart.token`'];
					$values   = [$cartid,time(),$shop->clean($_SESSION['cart'][$i]['product.title'],'encode'),$shop->clean($_SESSION['cart'][$i]['product.id'],'encode'),$shop->clean($_SESSION['cart'][$i]['product.qty'],'encode'),$shop->clean($ip,'encode'),'mollie payment',$shop->clean($_POST["token"],'encode')];
					$db->insert($table,$columns,$values);
				}
				
				// Calculate the totals
				$table    		= 'shop';
				$column   		= 'id';
				$value    		=  $shop->intcast($_SESSION['cart'][$i]['product.id']);
				$operator 		= '*';
				$cart_result 	= $db->select($table,$operator,$column,$value);
				
				$total = ($total + ($shop->clean($cart_result[0]['product.price'],'encode') * $shop->intcast($_SESSION['cart'][$i]['product.qty'])));
			}
			
			$shipping_free = $shop->clean($settings[0]['settings.free'],'encode');
				if($total > $shipping_free) {
					$shipping = 0;
					} else {
					$shipping = $shop->clean($settings[0]['settings.shipping'],'encode');
				$total = ($total + $shop->clean($settings[0]['settings.shipping'],'encode'));
			}
			
			$total = number_format($total, 2, '.', '');
						
			// Shop Form Data
			$order_firstname 	= $shop->clean($_POST["first_name"],'encode');
			$order_lastname 	= $shop->clean($_POST["last_name"],'encode');
			$order_address 		= $shop->clean($_POST["address"],'encode');
			$order_city 		= $shop->clean($_POST["city"],'encode');
			$order_zip 			= $shop->clean($_POST["zip"],'encode');
			$order_state		= $shop->clean($_POST["state"],'encode');
			$order_country 		= $shop->clean($_POST["country"],'encode'); 
			$order_email		= $shop->clean($_POST["email"],'encode');
			$order_token		= $shop->clean($_POST["token"],'encode');
							
			// Now follow the Mollie logic.
			include("../mollie/examples/initialize.php");

			$payment = $mollie->payments->create([
				"amount" => [
					"currency" => substr($shop->clean($settings[0]['settings.country.code'],'encode'),0,3),
					"value" => "$total",
				],
				"description" => "$orderid",
				"redirectUrl" => $shop->clean(WEBSITE,'encode') . "site/checkout/success.php?cartid=".$cartid."&orderid=".$orderid."&paytoken=".$shop->clean($paytoken,'encode'),
				"webhookUrl" => $shop->clean(WEBSITE,'encode') . "site/checkout/webhook.php?cartid=".$cartid."&orderid=".$orderid."&paytoken=".$shop->clean($paytoken,'encode'),
				"metadata" => [
					"order_id" => "$orderid",
				],
			]);
			
			if(isset($payment->id)) { 
				// Insert the order into database
				$table    = '`shop.orders`';
				$columns  = ['`order.id`','`order.cart.id`','`order.mollie.id`','`order.firstname`','`order.lastname`','`order.address`','`order.city`','`order.zip`','`order.state`','`order.country`','`order.email`','`order.phone`','`order.status`','`order.paid`','`order.shipping.price`','`order.total`','`order.date`','`order.token`'];
				$values   = [$orderid,$cartid,$payment->id,$order_firstname,$order_lastname,$order_address,$order_city,$order_zip,$order_state,$order_country,$order_email,0,'mollie transit',0,$shipping,$total,time(),$paytoken];
				$db->insert($table,$columns,$values);

				header("Location: " . $payment->getCheckoutUrl(), \true, 303);
				
				exit;
			} else {
				echo "<p>Could not process your order. Please try again later. <a href=\"".$shop->clean(WEBSITE,'encode')."cart/\">Click here to return to your cart.</a></p>";
			}

		}
	}
	
	

?>
