<?php
if(isset($highlight)) {
	$highlight_url_image = $shop->clean(UPLOAD_DIR.$highlight[0]['product.featured.image'],'paths');
	if(!file_exists($highlight_url_image)) {
		$highlight_url_image = $shop->clean(WEBSITE,'encode') ."resources/content/thumb.png";
	}
	$highlight_item_url = $shop->clean(WEBSITE,'encode') . "shop/".$shop->seoUrl($shop->intcast($highlight[0]['id']))."/".$shop->seoUrl($shop->clean($highlight[0]['product.title'],'encode'))."/";
?>
	<article class="shop-highlight">
		<div class="shop-center">
			<a href="<?php echo $highlight_item_url;?>" target="_self"><img id="shop-highlight-cover" src="<?php echo $highlight_url_image;?>"/></div></a>
		</div>
	</article>
<?php
}
?>