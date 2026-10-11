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

    $payment = $mollie->payments->get($shop->clean($_POST["id"],'encode'));
    $status  = $shop->paymentstatus($payment);
	
} catch (\Mollie\Api\Exceptions\ApiException $e) {
    echo "API call failed: " . \htmlspecialchars($e->getMessage());
	echo "<p>Make sure the API key is correct. If you see this error, the Mollie API key might be wrong.</p>";
}

if(isset($status)) {
	
	echo $status;
	
	$table    = '`shop.orders`';
	$column   = '`order.mollie.id`';
	$value    =  $shop->clean($_POST['id'],'encode');
	$operator = '*';
	$order    = $db->select($table,$operator,$column,$value);
	
	if(isset($order[0])) { 
		
		// Update database with a status from Mollie.
		
		$table    = '`shop.orders`';
		$columns  = ['order.status','order.paid'];
		$values   = [$status,($status == 'paid') ? 1 : 0];
		$db->update($table,$columns,$values,$order[0]['id']);
	
		if($status == 'paid') {
			
			// Update the inventory, using the cart that belongs to this order.
			
			$table_inventory     = '`shop.cart`';
			$column_inventory    = '`cart.id`';
			$value_inventory     =  $order[0]['order.cart.id'];
			$operator_inventory  = '*';
			$result_inventory = $db->select($table_inventory ,$operator_inventory ,$column_inventory ,$value_inventory);
			
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
					$value    =  $db->intcast($result_inventory[$i]['cart.product.id']);
					$operator = '*';
					$product  = $db->select($table,$operator,$column,$value);
					
					if(isset($product[0])) {
						
						// Update total stock in shop items.
						
						$table_stock    = '`shop`';
						$columns_stock  = ['product.stock'];
						$values_stock   = [max(0, ($product[0]['product.stock'] - $db->intcast($result_inventory[$i]['cart.qty'])))];
						$db->update($table_stock,$columns_stock,$values_stock,$db->intcast($product[0]['id']));
					}
				}
			}
		}
	}
}

?>