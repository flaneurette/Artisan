<?php

	session_start();
	
	require("../dashboard/configuration.php");
	include("../dashboard/resources/PHP/Class.DB.php");
	include("../dashboard/resources/PHP/Cryptography.php");
	include("../resources/PHP/Artisan.php");
	
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
	
	$table    		= 'shop';
	$column   		= 'id';
	$value    		=  $shop->intcast($_GET['id']);
	$operator 		= '*';
	$page_result 	= $db->select($table,$operator,$column,$value);
	
	if(!isset($page_result[0]['id'])) {
		header("Location: ../../../index.php");
		exit;
	}
	
	$meta = array();
	$meta[0]['meta_title'] 		 = $shop->clean($page_result[0]['product.title'],'encode');
	$meta[0]['meta_description'] = $shop->clean(substr(strip_tags($page_result[0]['product.description']),0,160), 'encode');
	$meta[0]['meta_tags'] 		 = $shop->clean($page_result[0]['product.title'].','.$page_result[0]['product.category'],'encode');
?>
<!DOCTYPE html>
<html>
<head>
<?php
	include("../components/Header.php");
?>	
</head>
<body>
<?php
	include("../components/Head.php"); 
	include("../components/Navigation.php");
	include("../components/Search.php"); 
?>
<article class="shop-page-highlight">
	<div class="shop-center">
		<div class="shop-item">
			<div class="shop-item-image">
				<?php
					if($page_result[0]['product.image'] !=='') {
				?>
						<img id="main-image" src="<?php echo $shop->clean(WEBSITE,'encode')."resources/content/".$shop->clean($page_result[0]['product.image'],'encode');?>" />
				<?php
					}
					
					if($page_result[0]['product.image.2'] !='' && $page_result[0]['product.image.3'] !='') {
				?>
					<div class="shop-item-image-thumbs">
						<div class="shop-item-image-thumb"><img src="<?php echo $shop->clean(WEBSITE,'encode')."resources/content/".$shop->clean($page_result[0]['product.image'],'encode');?>" onclick="plainui.thumb(this.src,'main-image');" /></div>
						<div class="shop-item-image-thumb"><img src="<?php echo $shop->clean(WEBSITE,'encode')."resources/content/".$shop->clean($page_result[0]['product.image.2'],'encode');?>"  onclick="plainui.thumb(this.src,'main-image');" /></div>
						<div class="shop-item-image-thumb"><img src="<?php echo $shop->clean(WEBSITE,'encode')."resources/content/".$shop->clean($page_result[0]['product.image.3'],'encode');?>"  onclick="plainui.thumb(this.src,'main-image');" /></div>
					</div>
				<?php
					}
				?>
			</div>
		<?php
			if(isset($page_result[0]['product.title'])) {
				echo "<div class=\"shop-item-title\"><h1>".$shop->clean($page_result[0]['product.title'],'encode')."</h1></div>";
			}
			if(isset($page_result[0]['product.description'])) {
				echo "<div class=\"shop-item-description\">".$shop->clean($page_result[0]['product.description'],'html')."</div>";
			}
			if(isset($page_result[0]['product.price'])) {
				echo "<div class=\"shop-item-price\">Price: ".$shop->clean($settings[0]['settings.currency'],'encode').number_format($shop->clean($page_result[0]['product.price'],'encode'),2)."</div>";
			}
		?>
		<form name="addtocart" action="<?php echo $shop->clean(WEBSITE,'encode');?>cart/" method="post">
		<input type="hidden" name="id" value="<?php echo $shop->intcast($page_result[0]['id']);?>" />
		<input type="hidden" name="product" value="<?php echo $shop->clean($page_result[0]['product.title'],'encode');?>" />
		<input type="hidden" name="token" value="<?php echo $token;?>" />
			<div class="shop-item-form">
				<div class="shop-item-form-element">
				<input type="number" name="qty" value="1" placeholder="Qty" min="1" max="<?php echo $shop->intcast($page_result[0]['product.stock']);?>" required/>
				<input type="submit" id="submit" name="submit" value="Add to cart"/>
				</div>
				<div class="shop-item-form-element"></div>
				<div class="shop-item-form-element"></div>
				<div class="shop-item-form-element"></div>
			</div>
		</form>
		</div>
	</div>
</article>
<?php		
	include("../components/Footer.php"); 
	include("../components/Scripts.php"); 
?>
</body>
</html>