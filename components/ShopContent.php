<?php
if(isset($latest)) {
?>
<article class="content-1">
		<div class="shop-center">
			<div class="shop-content-bar">
				<?php
				for($i=0;$i<count($latest);$i++) {
					
				if($latest[$i]['product.stock'] >=1) {
					$latest_url_image = $shop->clean(UPLOAD_DIR.$latest[$i]['product.image'],'paths');
					if(!file_exists($latest_url_image)) {
						$latest_url_image = $shop->clean(WEBSITE,'encode') ."resources/content/thumb.png";
					}
					$latest_item_url = $shop->clean(WEBSITE,'encode') . "shop/".$shop->seoUrl($shop->intcast($latest[$i]['id']))."/".$shop->seoUrl($shop->clean($latest[0]['product.title'],'encode'))."/";
				?>
				<form name="addtocart" action="<?php echo $shop->clean(WEBSITE,'encode');?>cart/" method="post">
				<input type="hidden" name="id" value="<?php echo $shop->intcast($latest[$i]['id']);?>" />
				<input type="hidden" name="product" value="<?php echo $shop->clean($latest[$i]['product.title'],'encode');?>" />
				<input type="hidden" name="token" value="<?php echo $token;?>" />
				<div class="shop-item-product-list">
					<div class="shop-item-product-image-div">
						<a href="<?php echo $latest_item_url;?>"><img src="<?php echo $latest_url_image;?>" class="shop-item-product-image"></a></div>
						<div class="shop-item-list-product-link">
							<a href="<?php echo $latest_item_url;?>">
							<?php echo $shop->clean($latest[$i]['product.title'],'encode');?>
							</a> 
						</div>
						<div class="shop-item-list-product-desc"><?php echo $shop->clean($latest[$i]['product.description'],'encode');?></div>
						<div class="shop-item-list-product-price"><?php echo $shop->clean($settings[0]['settings.currency'],'encode').number_format($latest[$i]['product.price'],2);?></div>
						<div><input type="number" name="qty" size="1" value="1" min="1" max="<?php echo $shop->intcast($latest[$i]['product.stock']);?>" class="shop-item-group-cart-qty">
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