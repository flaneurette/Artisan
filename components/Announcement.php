<?php
if(isset($settings)) {
	if(strlen($settings[0]['settings.announcement']) >= 1) {
?>

	<article class="shop-announcement">
		<div class="shop-center">
			<div class="shop-announcement-bar">
			<?php echo $shop->clean($settings[0]['settings.announcement'],'encode');?>
			</div>
		</div>
	</article>
<?php
	}
}
?>