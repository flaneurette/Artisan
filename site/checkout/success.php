<?php

require(__DIR__."/../../resources/PHP/Storefront.php");

// We use a webhook to see whether order has been paid.
// This script only updates the order to 'mollie processing', send e-mails and redirects to the Artisan shop.

require(__DIR__."/../../dashboard/resources/PHP/Class.SecureMail.php");

include(__DIR__."/../mollie/examples/initialize.php");


if(isset($_SESSION['paytoken']) && isset($_REQUEST['paytoken']) && isset($_REQUEST['orderid'])) { 
	
	if($_SESSION['paytoken'] === $_REQUEST['paytoken']) {
		
		$table    = '`shop_orders`';
		$column   = '`order_id`';
		$value    =  $_REQUEST['orderid'];
		$operator = '*';
		$result = $db->select($table,$operator,$column,$value);

		if(isset($result[0]) && $result[0]['order_token'] == $_REQUEST['paytoken']) {
			
			// Mollie also returns the customer here after a cancelled or failed 
			// payment, so ask Mollie what really happened.
			
			try {
				if(isset($mollie)) {
					$payment = $mollie->payments->get($result[0]['order_mollie_id']);
					$status  = $shop->paymentstatus($payment);
				} else {
					$status = 'unknown';
				}
			} catch (\Mollie\Api\Exceptions\ApiException $e) {
				error_log("Artisan: Mollie payment could not be read: " . $e->getMessage());
				$status = 'unknown';
			}
			
			if($status != 'unknown') {
				
				// Update database with the status from Mollie. The webhook does the 
				// same, and updates the inventory.
				
				$table    = '`shop_orders`';
				$columns  = ['order_status','order_paid'];
				$values   = [$status,($status == 'paid') ? 1 : 0];
				$db->update($table,$columns,$values,$result[0]['id']);
			}
			
			if($status != 'paid' && $status != 'pending') {
				// Not paid: keep the cart, so the customer can try again.
				header("Location: " . $shop->clean(WEBSITE,'encode') . "cart/");
				exit;
			}

			// Mail the client.
			
			if(isset($result[0]['order_email'])) { 
				$email = $result[0]['order_email'];
				$name = $result[0]['order_firstname'];
				$tpl = new \security\forms\SecureMail([]);
				$template_location = '../../inc/templates/order.html';
				$template_pairs = [
					"name" => $shop->clean($result[0]['order_firstname'],'encode'),
					"shop" => $shop->clean($_SERVER['HTTP_HOST'],'encode')
				];
				$html = $tpl->parseTemplate($template_location,$template_pairs);
				$parameters = array( 
					'to' => $email,
					'email' => $email,			
					'subject' => 'Order',
					'body' => $html
				);
				$checkForm = new \security\forms\SecureMail($parameters);
				$checkForm->sendmail();
			}
			
			// Mail shop owner to notify new order.
			
			$email = $settings[0]['settings_email'];
			
			if(isset($email)) { 
				$tpl = new \security\forms\SecureMail([]);
				$template_location = '../../inc/templates/shoporder.html';
				$template_pairs = [
					"link" => $db->clean(WEBSITE,'encode') . "dashboard/",
					"shop" => $shop->clean($_SERVER['HTTP_HOST'],'encode')
				];
				$html = $tpl->parseTemplate($template_location,$template_pairs);
				$parameters = array( 
					'to' => $email,
					'email' => $email,			
					'subject' => 'Order',
					'body' => $html
				);
				$checkForm = new \security\forms\SecureMail($parameters);
				$checkForm->sendmail();
			}
			
			// Empty session to prevent replay.
			unset($_SESSION['cart']);
			$_SESSION['cart'] 	= [];
			session_destroy();
		}
	}
}

// redirect to shop again.
header("Location: ../../");
exit;

?>
