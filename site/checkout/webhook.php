<?php

require("../../dashboard/configuration.php");
include("../../dashboard/resources/PHP/Class.DB.php");
include("../../dashboard/resources/PHP/Cryptography.php");
include("../../resources/PHP/Artisan.php");

require_once("../mollie/vendor/autoload.php");
require_once("../mollie/examples/functions.php");

$cryptography 	= new Cryptography;
$db 			= new sql();
$shop 			= new Artisan();

try {

    include("../mollie/examples/initialize.php");

    $payment = $mollie->payments->get($shop->clean($_REQUEST["id"],'encode'));
    $orderId = $payment->metadata->order_id;
	
    database_write($orderId, $payment->status);
	
    if ($payment->isPaid() && !$payment->hasRefunds() && !$payment->hasChargebacks()) {
		$status = 'paid';
    } elseif ($payment->isOpen()) {
		$status = 'open';
    } elseif ($payment->isPending()) {
		$status = 'pending';
    } elseif ($payment->isFailed()) {
		$status = 'failed';
    } elseif ($payment->isExpired()) {
		$status = 'expired';
    } elseif ($payment->isCanceled()) {
		$status = 'cancelled';
    } elseif ($payment->hasRefunds()) {
		$status = 'refunded';
    } elseif ($payment->hasChargebacks()) {
		$status = 'charged back';
    }
} catch (\Mollie\Api\Exceptions\ApiException $e) {
    echo "API call failed: " . \htmlspecialchars($e->getMessage());
	echo "<p>Make sure the API key is correct. If you see this error, the Mollie API key might be wrong.</p>";
}

if(isset($status)) {
	
	echo $status;
	
	$table    = '`shop.orders`';
	$column   = '`order.mollie.id`';
	$value    =  $shop->clean($_REQUEST['id'],'encode');
	$operator = '*';
	$result   = $db->select($table,$operator,$column,$value);
	
	if(isset($result)) { 
		if(count($result) >=1) { 
			// Update database with a status from Mollie.
			$table    = '`shop.orders`';
			$columns  = ['order.status'];
			$values   = [$status];
			$db->update($table,$columns,$values,$result[0]['id']);
		}
	}
	
	if($status == 'paid') {
		// Update the inventory
		if(isset($_REQUEST['cartid'])) { 
			$table_inventory     = '`shop.cart`';
			$column_inventory    = '`cart.id`';
			$value_inventory     =  $shop->clean($_REQUEST['cartid'],'encode');
			$operator_inventory  = '*';
			$result_inventory = $db->select($table_inventory ,$operator_inventory ,$column_inventory ,$value_inventory);
			if(isset($result_inventory)) {
				if(count($result_inventory) >=1) {
					for($i=0;$i<count($result_inventory);$i++) {
						if($result_inventory[$i]['cart.processed'] != '1') {
							
							// update cart to proccessed
							$table    = '`shop.cart`';
							$columns  = ['cart.processed'];
							$values   = [1];
							$db->update($table,$columns,$values,$result_inventory[$i]['id']);
							
							// get total stock in shop items.
							$table    = '`shop`';
							$column   = '`id`';
							$value    =  $shop->clean($result_inventory[$i]['cart.product.id'],'encode');
							$operator = '*';
							$result   = $db->select($table,$operator,$column,$value);
							if(isset($result)) {
								// update total stock in shop items.
								$table_stock    = '`shop`';
								$columns_stock  = ['product.stock'];
								$values_stock   = [($result[0]['product.stock'] - $db->intcast($result_inventory[$i]['cart.qty']))];
								$db->update($table_stock,$columns_stock,$values_stock,$db->intcast($result_inventory[$i]['id']));
							}
						}
					}
				}
			}
		}
	}
	
}

?>