<?php

	require(__DIR__."/../../resources/PHP/Storefront.php");

	
	
	
	$meta 		= $db->query("SELECT * FROM `pages` WHERE id = 1");
	
	$table    	= '`shop`';
	$column   	= '`product_description`';
	$operator 	= '*';
	$value 		= isset($_REQUEST['search']) ? (string) $_REQUEST['search'] : '';

	$search_results = $db->search($table,$operator,$column,$value);
	
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

if(count($search_results) <=0) {

?>
	<div class="shop-cart-center">
		<div class="material-symbols-outlined" id="shop-cart-symbols">
		production_quantity_limits
		</div>
	<h2>No items found while searching.</h2>
	</div>
<?php
} else {
?>
<article class="content-1">
		<div class="shop-center">
			<div class="shop-content-bar">
				<?php
				for($i=0;$i<count($search_results);$i++) {
					$search_results_url_image = "../".$shop->clean(UPLOAD_DIR.$search_results[$i]['product_image'],'paths');
					$search_results_item_url = $shop->clean(WEBSITE,'encode') . "shop/".$shop->seoUrl($shop->intcast($search_results[$i]['id']))."/".$shop->seoUrl($search_results[$i]['product_title'])."/";
				?>
				<form name="addtocart" action="<?php echo $shop->clean(WEBSITE,'encode');?>cart/" method="post">
				<input type="hidden" name="id" value="<?php echo $shop->intcast($search_results[$i]['id']);?>" />
				<input type="hidden" name="product" value="<?php echo $shop->clean($search_results[$i]['product_title'],'encode');?>" />
				<input type="hidden" name="token" value="<?php echo $token;?>" />
				<div class="shop-item-product-list">
					<div class="shop-item-product-image-div">
						<a href="<?php echo $search_results_item_url;?>"><img src="<?php echo $search_results_url_image;?>" class="shop-item-product-image"></a></div>
						<div class="shop-item-list-product-link">
							<a href="<?php echo $search_results_item_url;?>">
							<?php echo $shop->clean($search_results[$i]['product_title'],'encode');?>
							</a> 
						</div>
						<div class="shop-item-list-product-desc"><?php echo $shop->clean($search_results[$i]['product_description'],'encode');?></div>
						<div class="shop-item-list-product-price"><?php echo $shop->clean($settings[0]['settings_currency'],'encode').number_format($search_results[$i]['product_price'],2);?></div>
						<div><input type="number" name="qty" size="1" value="1" min="1" max="9999" class="shop-item-group-cart-qty">
						<input type="submit" class="shop-item-list-cart-button" name="add_cart" value="Buy">
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
}

include("../../components/Footer.php"); 
include("../../components/Scripts.php"); 
?>
</body>
</html>