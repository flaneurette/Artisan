<?php
if(isset($carousel)) { 
?>
	<article class="shop-carousel">
			<div class="shop-center">
				<div class="shop-carousel-bar">
				
				<?php
				for($i=0;$i<count($carousel);$i++) {
					
					if($carousel[$i]['product.stock'] >=1) {
					$carousel_url_image = $shop->clean(UPLOAD_DIR.$carousel[$i]['product.image'],'paths');
					if(!file_exists($carousel_url_image)) {
						$carousel_url_image = $shop->clean(WEBSITE,'encode') ."resources/content/thumb.png";
					}
					$carousel_item_url = $shop->clean(WEBSITE,'encode') . "shop/".$shop->seoUrl($shop->intcast($carousel[$i]['id']))."/".$shop->seoUrl($shop->clean($carousel[0]['product.title'],'encode'))."/";
				?>
				<form name="addtocart" action="<?php echo $shop->clean(WEBSITE,'encode');?>cart/" method="post">
				<input type="hidden" name="id" value="<?php echo $shop->intcast($carousel[$i]['id']);?>" />
				<input type="hidden" name="product" value="<?php echo $shop->clean($carousel[$i]['product.title'],'encode');?>" />
				<input type="hidden" name="token" value="<?php echo $token;?>" />
				<div class="shop-item-product-list">
					<div class="shop-item-product-image-div">
						<a href="<?php echo $carousel_item_url;?>"><img src="<?php echo $carousel_url_image;?>" class="shop-item-product-image"></a></div>
						<div class="shop-item-list-product-link">
							<a href="<?php echo $carousel_item_url;?>">
							<?php echo $shop->clean($carousel[$i]['product.title'],'encode');?>
							</a> 
						</div>
						<div class="shop-item-list-product-desc"><?php echo $shop->clean($carousel[$i]['product.description'],'encode');?></div>
						<div class="shop-item-list-product-price"><?php echo $shop->clean($settings[0]['settings.currency'],'encode').number_format($carousel[$i]['product.price'],2);?></div>
						<div><input type="number" name="qty" size="1" value="1" min="1" max="<?php echo $shop->intcast($carousel[$i]['product.stock']);?>" class="shop-item-group-cart-qty">
						<input type="submit" class="shop-item-list-cart-button" name="add_cart" value="Buy">
					</div>
				</div>
				</form>
				<?php
					}
				}
				?>
				</div>
			</div>	
	</article>
<?php
}
?>
