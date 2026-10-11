<?php

	include("Header.php");
	
	
	if(isset($_GET['csrf'])) {
		
		if($_GET['csrf'] === $_SESSION['uuid']) {
			
			$table    	= '`shop_orders`';
			$column   	= 'id';
			$value    	=  $shop->intcast($_GET['id']);
			$operator 	= '*';
			$order 		= $db->select($table,$operator,$column,$value);
			$settings 	= $db->query("SELECT * from `shop_settings`");	
			
			if(isset($order)) {
				$cart_table    	= '`shop_cart`';
				$cart_column   	= '`cart_id`';
				$cart_value    	=  $order[0]['order_cart_id'];
				$cart_operator 	= '*';
				$cart 			= $db->select($cart_table,$cart_operator,$cart_column,$cart_value);
				$order_id = explode('-',$order[0]['order_id']);
			} else {
				echo "Order unknown.";
				exit;
			}
		}
	} 
	
?>
<!DOCTYPE html>
<html>
<head>
<?php include("Meta.php");?>
<style media="print">
  @media print {
       @page {
            margin-top: 0;
            margin-bottom: 0;
       }
       body {
           padding-top: 72px;
           padding-bottom: 72px ;
       }
   } 
</style>
</head>
<body>

<div class="container">

	<article class="print">
		<?php
		echo "<p>" . $shop->clean($order[0]['order_firstname'],'encode'). " " . $shop->clean($order[0]['order_lastname'],'encode'). "</p>";
		echo "<p>" . $shop->clean($order[0]['order_address'],'encode'). "</p>"; 
		echo "<p>" . $shop->clean($order[0]['order_zip'],'encode'). " " .$shop->clean($order[0]['order_city'],'encode'). "</p>";
		echo "<p>" . $shop->clean($order[0]['order_state'],'encode'). " " .ucfirst($shop->clean($order[0]['order_country'],'encode')). "</p>";
		echo "<p>" . $shop->clean($order[0]['order_email'],'encode'). "</p>"; 
		echo "<hr />";
		echo "<p>" . "Order: " .$shop->clean($order_id[0],'encode'). "</p>";
		echo "<hr />";
	?>
	<table width="100%">
	<tr>
	<td>Product name</td>
	<td>Qty</td>
	<td>Price</td>
	<td>Subtotal</td>
	</tr>
	<?php 
		for($i=0;$i<count($cart);$i++){
			
			$product_table    	= '`shop`';
			$product_column   	= '`id`';
			$product_value    	=  $shop->intcast($cart[$i]['cart_product_id']);
			$product_operator 	= '*';
			$product 			= $db->select($product_table,$product_operator,$product_column,$product_value);
			
			// Orders placed before prices were stored with the cart fall back to the current price.
			if(isset($cart[$i]['cart_price']) && $cart[$i]['cart_price'] !== null) {
				$price = $cart[$i]['cart_price'];
				} else {
				$price = isset($product[0]) ? $product[0]['product_price'] : 0;
			}
	?>
		<tr>
		<td><?php echo $db->clean($cart[$i]['cart_product'],'encode');?></td>
		<td><?php echo $db->clean($cart[$i]['cart_qty'],'encode');?></td>
		<td><?php echo $db->clean($settings[0]['settings_currency'],'encode') . number_format((float) $price,2);?></td>
		<td><?php echo $db->clean($settings[0]['settings_currency'],'encode') . number_format(((float) $price * (int) $cart[$i]['cart_qty']),2);?></td>
		</tr>
	<?php
	}
	?>
		<tr>
		<td></td>
		<td>Subtotal: <?php echo $db->clean($settings[0]['settings_currency'],'encode') . number_format(((float) $order[0]['order_total'] - (float) $order[0]['order_shipping_price']),2);?></td>
		<td>Shipping: <?php echo $db->clean($settings[0]['settings_currency'],'encode') . $db->clean($order[0]['order_shipping_price'],'encode');?></td>
		<td>Total: <?php echo $db->clean($settings[0]['settings_currency'],'encode') . $db->clean($order[0]['order_total'],'encode');?></td>
		</tr>
	</table>
	</article>
</div>
</body>
</html>
