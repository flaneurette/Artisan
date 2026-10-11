<?php

	require(__DIR__."/../../resources/PHP/Storefront.php");

 

	
	
	
	
	
	
	// remove items that are gone or sold out, and limit quantities to the stock.
	$shop->validatecart($db);
	
	include(__DIR__."/../../components/Countries.php");
	
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
?>
		<form action="pay/" method="post" class="shop-checkout-form">
		<input type="hidden" name="token" value="<?php echo $token;?>" />
		<div class="shop-nav-h1"><h1>Checkout</h1></div>
		<div class="shop-checkout-fields">
			<div class="shop-checkout-field">
				<label for="first_name">First name</label>
				<input type="text" id="first_name" name="first_name" autocomplete="given-name" required>
			</div>
			<div class="shop-checkout-field">
				<label for="last_name">Last name</label>
				<input type="text" id="last_name" name="last_name" autocomplete="family-name" required>
			</div>
			<div class="shop-checkout-field shop-checkout-wide">
				<label for="address">Address</label>
				<input type="text" id="address" name="address" autocomplete="street-address" required>
			</div>
			<div class="shop-checkout-field">
				<label for="zip">Zip / postal code</label>
				<input type="text" id="zip" name="zip" autocomplete="postal-code" required>
			</div>
			<div class="shop-checkout-field">
				<label for="city">City</label>
				<input type="text" id="city" name="city" autocomplete="address-level2" required>
			</div>
			<div class="shop-checkout-field">
				<label for="state">State / province (optional)</label>
				<input type="text" id="state" name="state" maxlength="40" autocomplete="address-level1">
			</div>
			<div class="shop-checkout-field">
				<label for="country">Country</label>
				<select id="country" name="country" autocomplete="country-name" required>
					<option value="">Select country</option>
					<?php
					foreach($countries as $country) {
						echo "<option value=\"".$shop->clean($country,'encode')."\">".$shop->clean(ucwords($country),'encode')."</option>";
					}
					?>
				</select>
			</div>
			<div class="shop-checkout-field shop-checkout-wide">
				<label for="email">E-mail</label>
				<input type="email" id="email" name="email" maxlength="255" autocomplete="email" required>
			</div>
			<div class="shop-checkout-field shop-checkout-wide">
				<input type="submit" id="submit" name="submit" value="Pay Now"/>
			</div>
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