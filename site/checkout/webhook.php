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
}

if(isset($status)) {
	
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
}

?>