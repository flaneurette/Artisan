<?php
if(isset($settings)) {
	if(isset($settings[0]['settings.announcement'])) {
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