<?php 

	session_start();
	
	require("../../dashboard/configuration.php");
	include("../../dashboard/resources/PHP/Class.DB.php");
	include("../../dashboard/resources/PHP/Cryptography.php");
	include("../../resources/PHP/Artisan.php");
	
	$cryptography 	= new Cryptography;
	$db 			= new sql();
	$shop 			= new Artisan();
	
	$pages 			= $db->query("SELECT * FROM `pages` order by ordering ASC");
	$menu 			= $db->query("SELECT * FROM `shop.categories` order by `category.order` ASC");
	$settings 		= $db->query("SELECT * from `shop.settings`"); 
	
	if(isset($_SESSION['token'])) {
		$token = $_SESSION['token'];
		} else {
		$token = $cryptography->getToken();
		$_SESSION['token'] = $token;
	}
	
	if(isset($_GET['delete'])) {
		if(isset($_SESSION['token']) && isset($_GET['token'])) { 
			if($_SESSION['token'] === $_GET['token']) {
				$_SESSION['cart'] = $shop->deletefromcart($_GET['delete']);
				header("Location: ../../../../cart/");
				exit;
			}
		}
	}

	if(isset($_GET['update'])) {
		if(isset($_SESSION['token']) && isset($_GET['token'])) {
			if($_SESSION['token'] === $_GET['token']) {			
				$_SESSION['cart'] = $shop->updatecart($shop->intcast($_GET['id']),$shop->intcast($_GET['update']));
				header("Location: ../cart/");
				exit;
			}
		}
	}
	
	$shop->sessioncheck();
	
	if(!isset($_SESSION['cart'])) {
		$_SESSION['cart'] = [];
	}
	
	if(isset($_SESSION['token']) && isset($_POST['token'])) { 
	
		if($_SESSION['token'] === $_POST['token']) {
			
			if(isset($_POST['product'])) {
				
				$table    		= 'shop';
				$column   		= 'id';
				$value    		=  $shop->intcast($_POST['id']);
				$operator 		= '*';
				$cart_result 	= $db->select($table,$operator,$column,$value);
				
				if(isset($cart_result)) { 
	
					$arr = [
						'product.id' => $shop->intcast($_POST['id']),
						'product.qty' => $shop->intcast($_POST['qty']),
						'product.title' => $shop->clean($cart_result[0]['product.title'],'encode')
					];
					
					$shop->addtocart($arr);
				}
				$_SESSION['cart'] = $shop->unique_array($_SESSION['cart'], 'product.id');
			}
		}
	}
	
	$meta = array();
	$meta[0]['meta_title'] 		 = 'Shopping cart';
	$meta[0]['meta_description'] = 'This is your shopping cart';
	$meta[0]['meta_tags'] 		 = 'Shopping cart, cart, basket'; 
	
?>

<!DOCTYPE html>
<html>
<head>
<?php
	include("../../components/Header.php");
?>	
</head>
<body>
<?php
	include("../../components/Head.php"); 
	include("../../components/Navigation.php"); 
?>
<article class="shop-cart-main">
	<div class="shop-cart-highlight">
		<div class="shop-cart">
<?php 	
	if(count($_SESSION['cart']) <= 0) {	
?>
	<div class="shop-cart-center">
		<div class="material-symbols-outlined" id="shop-cart-symbols">
		production_quantity_limits
		</div>
	<h2>Shopping cart is empty.</h2>
	</div>
<?php
	} else {
		
	$total = 0; 
	$subtotal = 0;
?>
				<div class="shop-nav-h1">
				<h1>Cart</h1>
				</div>
				<form name="checkout" method="post" action="<?php echo $shop->clean(WEBSITE,'encode');?>checkout/">
				<input type="hidden" name="token" value="<?php echo $token;?>">
				<input type="hidden" name="checkout" value="1">
						<table width="100%" class="table-list">
							<tr class="shop-tr">
								<td>Item</td>
								<td>Price</td>
								<td>Qty</td>
								<td>Total</td>			
								<td>X</td>
							</tr>
							<?php
							
							for($i=0;$i<count($_SESSION['cart']);$i++) { 
								if($i % 2 !== 0) { 
									$color = "background-color: var(--lightgray);";
									} else {
									$color = "background-color: var(--lightgrey);";
								}
								
								$item_table    	= 'shop';
								$item_column   	= 'id';
								$item_value    	=  $shop->intcast($_SESSION['cart'][$i]['product.id']);
								$item_operator 	= '*';
								$item_result 	= $db->select($item_table,$item_operator,$item_column,$item_value);
								
								if((int) $_SESSION['cart'][$i]['product.qty'] > (int) $item_result[0]['product.stock']) {
									$max_order 	= $shop->intcast($_SESSION['cart'][$i]['product.qty']);
									$max_qty 	= $shop->intcast($item_result[0]['product.stock']);
									} else {
									$max_order 	= $shop->intcast($item_result[0]['product.stock']);
									$max_qty 	= $shop->intcast($_SESSION['cart'][$i]['product.qty']);
								}
								
								$total = ($total + ($item_result[0]['product.price'] * $max_qty));
								$subtotal = ($subtotal + ($item_result[0]['product.price'] * $max_qty));
							?>
							
							<input type="hidden" name="id" value="<?php echo $shop->intcast($item_result[0]['id']);?>">
							<tr style="<?php echo $color;?>">
								<td><?php echo $shop->clean($_SESSION['cart'][$i]['product.title'],'encode');?></td>
								<td><?php echo $shop->clean($settings[0]['settings.currency'],'encode').number_format($item_result[0]['product.price'],2);?></td>
								<td><input type="number" name="qty" placeholder="Qty" onchange="document.location='<?php echo $shop->clean(WEBSITE,'encode');?>cart/?update='+this.value+'&id=<?php echo $shop->intcast($_SESSION['cart'][$i]['product.id']);?>&token=<?php echo $token;?>';" value="<?php echo $shop->intcast($max_qty);?>" min="1" max="<?php echo $max_order;?>" required/>
								</td>
								<td><?php echo $shop->clean($settings[0]['settings.currency'],'encode'). number_format($item_result[0]['product.price'] * $max_qty,2);?></td>			
								<td><a href="<?php echo $shop->clean(WEBSITE,'encode');?>cart/delete/<?php echo $shop->intcast($_SESSION['cart'][$i]['product.id']);?>/<?php echo $token;?>/"><span class="material-symbols-outlined">delete</span></a></td>
							<?php
							}
							
							
							$shipping_free = $shop->clean($settings[0]['settings.free'],'encode');
							if($total > $shipping_free) {
								$shipping = "Free";
								} else {
								$shipping = $shop->clean($settings[0]['settings.currency'],'encode').$shop->clean($settings[0]['settings.shipping'],'encode');
								$total = ($total + $shop->clean($settings[0]['settings.shipping'],'encode'));
							}
							?>
							</tr>
							<tr class="shop-tr">
								<td></td>
								<td></td>
								<td>Subtotal: <br /><?php echo $shop->clean($settings[0]['settings.currency'],'encode').number_format($subtotal,2);?> </td>
								<td>Shipping: <br /><?php echo $shipping;?></td>
								<td>Total: <br /><?php echo $shop->clean($settings[0]['settings.currency'],'encode').number_format($total,2);?></td>
							</tr>
						</table>
						
						<div>				
							<input type="submit" id="submit" name="submit" value="Checkout"/>
						</div>
				</form>
<?php
	}
?>
	</div>
</div>
</article>
<?php		
	include("../../components/Footer.php"); 
	include("../../components/Scripts.php"); 
?>
</body>
</html>