<?php

require(__DIR__."/../../resources/PHP/Storefront.php");

if(isset($_SESSION['paytoken'])) {
	$paytoken = $_SESSION['paytoken'];
	} else {
	$paytoken = $cryptography->getToken();
	$_SESSION['paytoken'] = $paytoken;
}

	if(!isset($_POST['token'])) {
		header("Location: ../../cart/");
		exit;
	}

	if(isset($_SESSION['token']) && isset($_POST['token'])) { 
	
		if($_SESSION['token'] === $_POST['token']) {
			
			if(!isset($_SESSION['cart'])) {
				header("Location: ../../cart/");
				exit;
			}
			
			// The inventory may have changed since the cart was shown. If the cart had 
			// to be corrected, show it again before taking a payment.
			if($shop->validatecart($db) || count($_SESSION['cart']) < 1) {
				header("Location: ../../cart/");
				exit;
			}
			
			
			// The form is checked in the browser, but never trust that alone.
			foreach(['first_name','last_name','address','city','zip','country','email'] as $field) {
				if(!isset($_POST[$field]) || trim((string) $_POST[$field]) === '') {
					header("Location: ../");
					exit;
				}
			}
			
			if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
				header("Location: ../");
				exit;
			}
			
			if(!isset($_POST['state'])) {
				$_POST['state'] = '';
			}
			
			// Each payment attempt gets its own cart and order id, so that a cart which 
			// was changed after an abandoned payment is never paid under the old order.
			$cartid  = 'C' . time().'-'.$cryptography->uniqueID();
			$orderid = 'O' . time().'-'.$cryptography->uniqueID();
			
			$ip		 = $_SERVER['REMOTE_ADDR'];
			$total	 = 0;
			$lines	 = [];
			
			for($i=0;$i<count($_SESSION['cart']);$i++) {
				
				// Calculate the totals
				$table    		= 'shop';
				$column   		= 'id';
				$value    		=  $shop->intcast($_SESSION['cart'][$i]['product_id']);
				$operator 		= '*';
				$cart_result 	= $db->select($table,$operator,$column,$value);
				
				$price 	= (float) $cart_result[0]['product_price'];
				$qty 	= $shop->intcast($_SESSION['cart'][$i]['product_qty']);
				
				$total = ($total + ($price * $qty));
				
				// The price is stored with the cart, so the order keeps what was actually paid.
				$lines[] = [$cartid,time(),$cart_result[0]['product_title'],$value,$qty,number_format($price, 2, '.', ''),$ip,'mollie payment',$_POST["token"]];
			}
			
			$shipping_free = $shop->clean($settings[0]['settings_free'],'encode');
				if($total > $shipping_free) {
					$shipping = 0;
					} else {
					$shipping = $shop->clean($settings[0]['settings_shipping'],'encode');
				$total = ($total + $shop->clean($settings[0]['settings_shipping'],'encode'));
			}
			
			$total = number_format($total, 2, '.', '');
						
			// Shop Form Data
			$order_firstname 	= $_POST["first_name"];
			$order_lastname 	= $_POST["last_name"];
			$order_address 		= $_POST["address"];
			$order_city 		= $_POST["city"];
			$order_zip 			= $_POST["zip"];
			$order_state		= $_POST["state"];
			$order_country 		= $_POST["country"]; 
			$order_email		= $_POST["email"];
			$order_token		= $_POST["token"];
							
			// Now follow the Mollie logic.
			include(__DIR__."/../mollie/examples/initialize.php");
			
			$payment = null;
			
			if(isset($mollie)) {
				try {
					$payment = $mollie->payments->create([
						"amount" => [
							"currency" => substr($shop->clean($settings[0]['settings_country_code'],'encode'),0,3),
							"value" => "$total",
						],
						"description" => "$orderid",
						"redirectUrl" => $shop->clean(WEBSITE,'encode') . "site/checkout/success.php?orderid=".$orderid."&paytoken=".$shop->clean($paytoken,'encode'),
						"webhookUrl" => $shop->clean(WEBSITE,'encode') . "site/checkout/webhook.php",
						"metadata" => [
							"order_id" => "$orderid",
						],
					]);
				} catch (\Mollie\Api\Exceptions\ApiException $e) {
					error_log("Artisan: Mollie payment could not be created: " . $e->getMessage());
				}
			}
			
			if(isset($payment->id)) { 
			
				// Insert cart contents into database
				$table    = '`shop_cart`';
				$columns  = ['`cart_id`','`cart_created`','`cart_product`','`cart_product_id`','`cart_qty`','`cart_price`','`cart_ip`','`cart_stage`','`cart_token`'];
				for($i=0;$i<count($lines);$i++) {
					$db->insert($table,$columns,$lines[$i]);
				}
				
				// Insert the order into database
				$table    = '`shop_orders`';
				$columns  = ['`order_id`','`order_cart_id`','`order_mollie_id`','`order_firstname`','`order_lastname`','`order_address`','`order_city`','`order_zip`','`order_state`','`order_country`','`order_email`','`order_phone`','`order_status`','`order_paid`','`order_shipping_price`','`order_total`','`order_date`','`order_token`'];
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
