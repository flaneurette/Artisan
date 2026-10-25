<?php
	session_start();
	
	require("../../dashboard/configuration.php");
	include("../../dashboard/resources/PHP/Class.DB.php");
	include("../../dashboard/resources/PHP/Cryptography.php");
	include("../../resources/PHP/Artisan.php");
	
	$db 	= new sql();
	$shop 	= new Artisan();
	$cryptography 	= new Cryptography;
	
	$menu 	= $db->query("SELECT * FROM `shop.categories` order by `category.order` ASC");
	$pages 	= $db->query("SELECT * FROM `pages` order by ordering ASC");
	$settings 	= $db->query("SELECT * from `shop.settings`"); 
	
	$table    	= '`shop.categories`';
	$column   	= '`category.name`';
	$value    	=  $shop->clean($_GET['category'],'navigate');
	$operator 	= '*';
	$meta 		= $db->select($table,$operator,$column,$value);

	$table_categories    	= '`shop`';
	$column_categories   	= '`product.category`';
	$value_categories    	=  $shop->clean($_GET['category'],'navigate');
	$operator_categories 	= '*';
	$categories				= $db->select($table_categories,$operator_categories,$column_categories,$value_categories);
	
	if(count($meta) <=0) {
		header("Location ../index.php");
		exit;
	}

	if(isset($_SESSION['token'])) {
		$token = $_SESSION['token'];
		} else {
		$token = $cryptography->getToken();
		$_SESSION['token'] = $token;
	}
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

if(count($categories) <=0) {

?>
	<div class="shop-cart-center">
		<div class="material-symbols-outlined" id="shop-cart-symbols">
		production_quantity_limits
		</div>
	<h2>No items found in this category.</h2>
	</div>
<?php
} else {
?>
<article class="content-1">
		<div class="shop-center">
			<div class="shop-content-bar">
				<?php
				for($i=0;$i<count($categories);$i++) {
					
					if($categories[$i]['product.stock'] >=1) {
						
					$categories_url_image = "../../".$shop->clean(UPLOAD_DIR.$categories[$i]['product.image'],'paths');
					if(!file_exists($categories_url_image)) {
						$categories_url_image = $shop->clean(WEBSITE,'encode') ."resources/content/thumb.png";
					}
					$categories_item_url = $shop->clean(WEBSITE,'encode') . "shop/".$shop->seoUrl($shop->intcast($categories[$i]['id']))."/".$shop->seoUrl($shop->clean($categories[0]['product.title'],'encode'))."/";
				?>
				<form name="addtocart" action="<?php echo $shop->clean(WEBSITE,'encode');?>cart/" method="post">
				<input type="hidden" name="id" value="<?php echo $shop->intcast($categories[$i]['id']);?>" />
				<input type="hidden" name="product" value="<?php echo $shop->clean($categories[$i]['product.title'],'encode');?>" />
				<input type="hidden" name="token" value="<?php echo $token;?>" />
				<div class="shop-item-product-list">
					<div class="shop-item-product-image-div">
						<a href="<?php echo $categories_item_url;?>"><img src="<?php echo $categories_url_image;?>" class="shop-item-product-image"></a></div>
						<div class="shop-item-list-product-link">
							<a href="<?php echo $categories_item_url;?>">
							<?php echo $shop->clean($categories[$i]['product.title'],'encode');?>
							</a> 
						</div>
						<div class="shop-item-list-product-desc"><?php echo $shop->clean($categories[$i]['product.description'],'encode');?></div>
						<div class="shop-item-list-product-price"><?php echo $shop->clean($settings[0]['settings.currency'],'encode').number_format($categories[$i]['product.price'],2);?></div>
						<div><input type="number" name="qty" size="1" value="1" min="1" max="<?php echo $shop->intcast($categories[$i]['product.stock']);?>" class="shop-item-group-cart-qty">
						<input type="submit" class="shop-item-list-cart-button" name="add_cart" value="Buy">
					</div>
				</div>
				</form>
				<?php
					} else {
						?>
							<div class="shop-cart-center">
								<div class="material-symbols-outlined" id="shop-cart-symbols">
								production_quantity_limits
								</div>
							<h2>Items in stock is too low.</h2>
							</div>
						<?php
						
					}
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