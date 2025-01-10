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
	$meta[0]['meta_title'] 		 = 'Checkout';
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
	include("../../components/Search.php");
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
				<form action="https://www.paypal.com/us/cgi-bin/webscr" method="post">

							<?php
							
							$max_qty = 0;
							
							for($i=0;$i<count($_SESSION['cart']);$i++) { 
								if($i % 2 !== 0) { 
									$color = "background-color: var(--lightgrey);";
									} else {
									$color = "";
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
							
							$shipping_free = $shop->clean($settings[0]['settings.free'],'encode');
							$shipping_paid = $shop->clean($settings[0]['settings.shipping'],'encode');
							if($total > $shipping_free) {
								$add_shipping 	= 0.00;
								} else {
								$add_shipping 	= $shipping_paid;
							}
							
							$max_qty++;
							?>
							
							<input type="hidden" name="item_name_<?php echo ($i+1);?>" maxlength="127" size="20" value="<?php echo substr($shop->clean($item_result[0]['product.title'],'encode'),0,127);?>" title="cart item, 127 chars" />
							<input type="hidden" name="item_number_<?php echo ($i+1);?>" maxlength="127" size="20" value="<?php echo $shop->intcast($item_result[0]['id']);?>" title="track payments, 127 chars" />
							<input type="hidden" name="item_price_<?php echo ($i+1);?>" maxlength="127" size="20" id="item_price" value="<?php echo round($shop->clean($item_result[0]['product.price'],'encode'),2);?>" title="" />
							<!-- required -->
							<input type="hidden" name="amount_<?php echo ($i+1);?>" maxlength="127" size="20" id="item_price" value="<?php echo round($shop->clean($item_result[0]['product.price'],'encode'),2);?>" title="" /> 
							<input type="hidden" name="quantity_<?php echo ($i+1);?>" value="<?php echo $shop->intcast($max_qty);?>" />
							<!-- <input type="hidden" name="shipping_<?php echo ($i+1);?>" maxlength="127" size="20" id="shipping_x" value="<?php echo $add_shipping;?>" title=""> -->
							
							<?php
							}
							?>
							<div class="shop-checkout-block">
							<table width="100%" class="table-list">
							<tr>
								<td><label for="first_name">First name</label></td>
								<td><input type="text" name="first_name" id="first_name" size="15" maxlength="32" value="" title="The customer's first name (32-alphanumeric character limit)." required></td>
								<td><label for="last_name">Last name</label></td>
								<td><input type="text" name="last_name" id="last_name" size="15" maxlength="64" value="" title="The customer's last name (64-alphanumeric character limit)." required></td>
								</tr>
								<tr>
								<td><label for="address1">Address</label></td>
								<td><input type="text" name="address1" id="address1" maxlength="100" value="" title="The first line of the customer's address (100-alphanumeric character limit)." required></td>
								<td><label for="city">City</label></td>
								<td><input type="text" name="city" id="city" maxlength="100" value="" title="The city noted in the customer's address (100-alphanumeric character limit)." required></td>
								</tr>
								<tr>
								<td><label for="day_phone_a">Area code</label></td>
								<td><input type="text" name="day_phone_a" id="day_phone_a" size="5" value="" required></td>
								<td><label for="state">State</label></td>
								<td><input type="text" name="state" id="state" size="3" maxlength="3" value="" title="The state noted in the customer's address (the official two-letter abbreviation)." required></td>
								</tr>
								<tr>
								<td><label for="zip">Zip</label></td>
								<td><input type="text" name="zip" id="zip" size="5"  maxlength="32" value="" title="The postal code noted in the customer's address." required></td>
								<td><label for="email">E-mail</label></td>
								<td><input type="text" name="email" id="email" size="15" value="" title="The customer's email address." required></td>
								</tr>
								<tr>
								<td><label for="day_phone_b">Phone</label></td>
								<td><input type="text" name="day_phone_b" id="day_phone_b" size="7" value="" value="" required></td>
								<td><label for="zip">Country</label></td>
								<td><input type="text" name="country" value="" required></td>
								</tr>
								</table>
							</div>
							<?php
							
							$shipping_free = $shop->clean($settings[0]['settings.free'],'encode');
							if($total > $shipping_free) {
								$shipping 			= "Free";
								$shipping_paypal 	= 0;
								} else {
								$shipping 			= $shop->clean($settings[0]['settings.currency'],'encode').$shop->clean($settings[0]['settings.shipping'],'encode');
								$shipping_paypal 	= $shop->clean($settings[0]['settings.shipping'],'encode');
								$total 				= ($total + $shop->clean($settings[0]['settings.shipping'],'encode'));
							}
							?>
							
							<input type="hidden" name="no_note" maxlength="1" min="0" max="1" value="1" title="0">
							<input type="hidden" name="tax" title="totaltax" value="0.00">
							<input type="hidden" name="shipping" id="shipping" size="5" title="The item's shipping cost" value="<?php echo $shipping_paypal;?>">
							<input type="hidden" name="handling" id="handling" size="5" title="handling cost" value="<?php echo $shipping_paypal;?>">
							<input type="hidden" name="amount" size="5" id="total_amount" title="total amount" value="<?php echo round(($total) ,2);?>">
							<input type="hidden" name="image_url" value="http://www.paypal.com/en_US/i/btn/x-click-but01.gif">
							<input type="hidden" name="currency_code" value="USD">		
							<input type="hidden" name="business" value="<?php echo $shop->clean($settings[0]['settings.paypal'],'encode');?>">
							<input type="hidden" name="cancel_return" value="<?php echo $shop->clean(WEBSITE,'encode');?>cancel/?token=<?php echo $token;?>">
							<input type="hidden" name="custom" value="USD">
							<!-- <input type="hidden" name="invoice" value="0"> -->
							<input type="hidden" name="notify_url" value="<?php echo $shop->clean(WEBSITE,'encode');?>notify/?token=<?php echo $token;?>">
							<input type="hidden" name="return" value="<?php echo $shop->clean(WEBSITE,'encode');?>success/?token=<?php echo $token;?>">
							<input type="hidden" name="cmd" value="_cart">
							<input type="hidden" name="upload" value="1">
							<input type="hidden" name="rm" value="2">
						<div>				
							<input type="submit" id="submit" name="submit" value="Pay now"/>
						</div>
				</form>
<?php
	}
?>
	</div>
</div>
<?php		
	include("../../components/Footer.php"); 
	include("../../components/Scripts.php"); 
?>
</body>
</html>