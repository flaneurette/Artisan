<?php
	require("../configuration.php");
	include("Header.php");
	
	include("../../resources/PHP/Artisan.php");

	$shop 	= new Artisan();
	
	if(isset($_GET['csrf'])) {
		
		if($_GET['csrf'] === $_SESSION['uuid']) {
			
			$table    	= '`shop.orders`';
			$column   	= 'id';
			$value    	=  $shop->intcast($_GET['id']);
			$operator 	= '*';
			$order 		= $db->select($table,$operator,$column,$value);
			$settings 	= $db->query("SELECT * from `shop.settings`");	
			
			if(isset($order)) {
				$cart_table    	= '`shop.cart`';
				$cart_column   	= '`cart.id`';
				$cart_value    	=  $shop->clean($order[0]['order.cart.id'],'encode');
				$cart_operator 	= '*';
				$cart 			= $db->select($cart_table,$cart_operator,$cart_column,$cart_value);
				$order_id = explode('-',$order[0]['order.id']);
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
		echo "<p>" . $shop->clean($order[0]['order.firstname'],'encode'). " " . $shop->clean($order[0]['order.lastname'],'encode'). "</p>";
		echo "<p>" . $shop->clean($order[0]['order.address'],'encode'). "</p>"; 
		echo "<p>" . $shop->clean($order[0]['order.zip'],'encode'). " " .$shop->clean($order[0]['order.city'],'encode'). "</p>";
		echo "<p>" . $shop->clean($order[0]['order.state'],'encode'). " " .ucfirst($shop->clean($order[0]['order.country'],'encode')). "</p>";
		echo "<p>" . $shop->clean($order[0]['order.email'],'encode'). "</p>"; 
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
			$product_value    	=  $shop->clean($cart[$i]['cart.product.id'],'encode');
			$product_operator 	= '*';
			$product 			= $db->select($product_table,$product_operator,$product_column,$product_value);
	?>
		<tr>
		<td><?php echo $db->clean($cart[$i]['cart.product'],'encode');?></td>
		<td><?php echo $db->clean($cart[$i]['cart.qty'],'encode');?></td>
		<td><?php echo $db->clean($settings[0]['settings.currency'],'encode') . number_format($db->clean($product[0]['product.price'],'encode'),2);?></td>
		<td><?php echo $db->clean($settings[0]['settings.currency'],'encode') . number_format(($db->clean($product[0]['product.price'],'encode') * $db->clean($cart[$i]['cart.qty'],'encode')),2);?></td>
		</tr>
	<?php
	}
	?>
		<tr>
		<td></td>
		<td>Subtotal: <?php echo $db->clean($settings[0]['settings.currency'],'encode') . ($db->clean($order[0]['order.total'],'encode') - $order[0]['order.shipping.price']);?></td>
		<td>Shipping: <?php echo $db->clean($settings[0]['settings.currency'],'encode') . $db->clean($order[0]['order.shipping.price'],'encode');?></td>
		<td>Total: <?php echo $db->clean($settings[0]['settings.currency'],'encode') . $db->clean($order[0]['order.total'],'encode');?></td>
		</tr>
	</table>
	</article>
</div>
</body>
</html>
