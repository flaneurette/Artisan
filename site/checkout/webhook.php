<?php

require(__DIR__."/../../resources/PHP/Bootstrap.php");


if(!isset($_POST["id"])) {
	http_response_code(400);
	exit;
}

try {

    include(__DIR__."/../mollie/examples/initialize.php");
	
    if(!isset($mollie)) {
		// Let Mollie try again later.
		http_response_code(500);
		exit;
    }

    $payment = $mollie->payments->get($_POST["id"]);
    $status  = $shop->paymentstatus($payment);
	
} catch (\Mollie\Api\Exceptions\ApiException $e) {
    echo "API call failed: " . \htmlspecialchars($e->getMessage());
	echo "<p>Make sure the API key is correct. If you see this error, the Mollie API key might be wrong.</p>";
}

if(isset($status)) {
	
	echo $status;
	
	$table    = '`shop_orders`';
	$column   = '`order_mollie_id`';
	$value    =  $_POST['id'];
	$operator = '*';
	$order    = $db->select($table,$operator,$column,$value);
	
	if(isset($order[0])) { 
		
		// Update database with a status from Mollie.
		
		$table    = '`shop_orders`';
		$columns  = ['order_status','order_paid'];
		$values   = [$status,($status == 'paid') ? 1 : 0];
		$db->update($table,$columns,$values,$order[0]['id']);
	
		if($status == 'paid') {
			
			// Update the inventory, using the cart that belongs to this order.
			
			$table_inventory     = '`shop_cart`';
			$column_inventory    = '`cart_id`';
			$value_inventory     =  $order[0]['order_cart_id'];
			$operator_inventory  = '*';
			$result_inventory = $db->select($table_inventory ,$operator_inventory ,$column_inventory ,$value_inventory);
			
			for($i=0;$i<count($result_inventory);$i++) {
				if($result_inventory[$i]['cart_processed'] != '1') {
					
					// Update cart to proccessed
					
					$table    = '`shop_cart`';
					$columns  = ['cart_processed'];
					$values   = [1];
					$db->update($table,$columns,$values,$result_inventory[$i]['id']);
					
					// Get total stock in shop items.
					
					$table    = '`shop`';
					$column   = '`id`';
					$value    =  $db->intcast($result_inventory[$i]['cart_product_id']);
					$operator = '*';
					$product  = $db->select($table,$operator,$column,$value);
					
					if(isset($product[0])) {
						
						// Update total stock in shop items.
						
						$table_stock    = '`shop`';
						$columns_stock  = ['product_stock'];
						$values_stock   = [max(0, ($product[0]['product_stock'] - $db->intcast($result_inventory[$i]['cart_qty'])))];
						$db->update($table_stock,$columns_stock,$values_stock,$db->intcast($product[0]['id']));
					}
				}
			}
		}
	}
}

?>