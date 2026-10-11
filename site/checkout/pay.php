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
				$value    		=  $shop->intcast($_SESSION['cart'][$i]['product.id']);
				$operator 		= '*';
				$cart_result 	= $db->select($table,$operator,$column,$value);
				
				$price 	= (float) $cart_result[0]['product.price'];
				$qty 	= $shop->intcast($_SESSION['cart'][$i]['product.qty']);
				
				$total = ($total + ($price * $qty));
				
				// The price is stored with the cart, so the order keeps what was actually paid.
				$lines[] = [$cartid,time(),$shop->clean($cart_result[0]['product.title'],'encode'),$value,$qty,number_format($price, 2, '.', ''),$shop->clean($ip,'encode'),'mollie payment',$shop->clean($_POST["token"],'encode')];
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
			include(__DIR__."/../mollie/examples/initialize.php");
			
			$payment = null;
			
			if(isset($mollie)) {
				try {
					$payment = $mollie->payments->create([
						"amount" => [
							"currency" => substr($shop->clean($settings[0]['settings.country.code'],'encode'),0,3),
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
				$table    = '`shop.cart`';
				$columns  = ['`cart.id`','`cart.created`','`cart.product`','`cart.product.id`','`cart.qty`','`cart.price`','`cart.ip`','`cart.stage`','`cart.token`'];
				for($i=0;$i<count($lines);$i++) {
					$db->insert($table,$columns,$lines[$i]);
				}
				
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
