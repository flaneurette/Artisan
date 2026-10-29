<?php

require("../../dashboard/configuration.php");
include("../../dashboard/resources/PHP/Class.DB.php");
include("../../dashboard/resources/PHP/Cryptography.php");
include("../../resources/PHP/Artisan.php");

// Tell PHP to load all Composer packages
require_once '../../vendor/autoload.php';

use Mollie\Api\Http\Data\Money;
use Mollie\Api\Http\Requests\GetPaymentRequest;
use Mollie\Api\Types\PaymentStatus;

# Get API key
$table    = '`shop.settings`';
$column   = '`id`';
$value    =  1;
$operator = '*';
$result = $db->select($table,$operator,$column,$value);
			
if(isset($result[0]['settings.mollie.api'])) {		
	$mollie = new \Mollie\Api\MollieApiClient();
    $mollie->setToken($result[0]['settings.mollie.api']);
} else {
	echo "<p>There has been an error: API key for Mollie is not found in shop settings!</p>";
}

$cryptography 	= new Cryptography;
$db 			= new sql();
$shop 			= new Artisan();

try {

    // Retrieve the payment's current state
    $payment = $mollie->send(
        new GetPaymentRequest(
            id: $shop->clean($_REQUEST["id"],'encode')
        )
    );

    $orderId = $payment->metadata->order_id;

    // First handle status changes
    if ($payment->status !== $previousPaymentStatus) {
        // Update your order administration with the new status
        $status = 'unpaid';
        // Handle the status change
        if ($payment->isPaid()) {
            // The payment is paid
            // Start the process of delivering the product to the customer
            $status = 'paid';
        } elseif ($payment->isFailed()) {
            // The payment has failed
            // Notify the customer
            $status = 'failed';
        } elseif ($payment->isExpired()) {
            // The payment is expired
            // Notify the customer
            $status = 'expired';
        } elseif ($payment->isCanceled()) {
            // The payment is canceled
            // Notify the customer
            $status = 'cancelled';
        }
    }

    // Then handle refunds and chargebacks (these don't change the payment status)
    if ($payment->hasRefunds()) {
        // The payment has possibly been (partially) refunded
        // Note: the payment status remains "paid"
        $status = 'refund';
    }
    
    if ($payment->hasChargebacks()) {
        // The payment has possibly been (partially) charged back
        // Note: the payment status remains "paid"
        $status = 'chargebacks';
    }

    // Always return 200 OK to Mollie
    http_response_code(200);
} catch (\Mollie\Api\Exceptions\ApiException $e) {
    echo "<p>Make sure the API key is correct. If you see this error, the Mollie API key might be wrong.</p>";
    exit;
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
							
							// Update cart to proccessed
							
							$table    = '`shop.cart`';
							$columns  = ['cart.processed'];
							$values   = [1];
							$db->update($table,$columns,$values,$result_inventory[$i]['id']);
							
							// Get total stock in shop items.
							
							$table    = '`shop`';
							$column   = '`id`';
							$value    =  $shop->clean($result_inventory[$i]['cart.product.id'],'encode');
							$operator = '*';
							$result   = $db->select($table,$operator,$column,$value);
							if(isset($result)) {
								
								// Update total stock in shop items.
								
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
