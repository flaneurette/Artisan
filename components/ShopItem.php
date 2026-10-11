<?php

	require(__DIR__."/../resources/PHP/Storefront.php");

	
	
	
	
	
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
	$meta[0]['meta_title'] 		 = $page_result[0]['product_title'];
	$meta[0]['meta_description'] = substr(strip_tags($page_result[0]['product_description']),0,160);
	$meta[0]['meta_tags'] 		 = $page_result[0]['product_title'].','.$page_result[0]['product_category'];
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
					if($page_result[0]['product_image'] !=='') {
				?>
						<img id="main-image" src="<?php echo $shop->clean(WEBSITE,'encode')."resources/content/".$shop->clean($page_result[0]['product_image'],'encode');?>" />
				<?php
					}
					
					if($page_result[0]['product_image_2'] !='' && $page_result[0]['product_image_3'] !='') {
				?>
					<div class="shop-item-image-thumbs">
						<div class="shop-item-image-thumb"><img src="<?php echo $shop->clean(WEBSITE,'encode')."resources/content/".$shop->clean($page_result[0]['product_image'],'encode');?>" onclick="plainui.thumb(this.src,'main-image');" /></div>
						<div class="shop-item-image-thumb"><img src="<?php echo $shop->clean(WEBSITE,'encode')."resources/content/".$shop->clean($page_result[0]['product_image_2'],'encode');?>"  onclick="plainui.thumb(this.src,'main-image');" /></div>
						<div class="shop-item-image-thumb"><img src="<?php echo $shop->clean(WEBSITE,'encode')."resources/content/".$shop->clean($page_result[0]['product_image_3'],'encode');?>"  onclick="plainui.thumb(this.src,'main-image');" /></div>
					</div>
				<?php
					}
				?>
			</div>
		<?php
			if(isset($page_result[0]['product_title'])) {
				echo "<div class=\"shop-item-title\"><h1>".$shop->clean($page_result[0]['product_title'],'encode')."</h1></div>";
			}
			if(isset($page_result[0]['product_description'])) {
				echo "<div class=\"shop-item-description\">".$shop->clean($page_result[0]['product_description'],'html')."</div>";
			}
			if(isset($page_result[0]['product_price'])) {
				echo "<div class=\"shop-item-price\">Price: ".$shop->clean($settings[0]['settings_currency'],'encode').number_format($shop->clean($page_result[0]['product_price'],'encode'),2)."</div>";
			}
		?>
		<form name="addtocart" action="<?php echo $shop->clean(WEBSITE,'encode');?>cart/" method="post">
		<input type="hidden" name="id" value="<?php echo $shop->intcast($page_result[0]['id']);?>" />
		<input type="hidden" name="product" value="<?php echo $shop->clean($page_result[0]['product_title'],'encode');?>" />
		<input type="hidden" name="token" value="<?php echo $token;?>" />
			<div class="shop-item-form">
				<div class="shop-item-form-element">
				<input type="number" name="qty" value="1" placeholder="Qty" min="1" max="<?php echo $shop->intcast($page_result[0]['product_stock']);?>" required/>
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