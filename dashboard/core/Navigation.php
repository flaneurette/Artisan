	<h1><a href="<?php echo $db->clean(SITE,'encode');?>index.php">Artisan</a></h1>
	<ul class="navigate">
		<li><a href="<?php echo $db->clean(SITE,'encode');?>./">Dashboard</a></li>
		<li><a href="<?php echo $db->clean(SITE,'encode');?>shop/">Shop</a></li>
		<li><a href="<?php echo $db->clean(SITE,'encode');?>shop/add/">Add shop item</a></li>
		<li><a href="<?php echo $db->clean(SITE,'encode');?>shop/categories/">Categories</a></li>
		<li><a href="<?php echo $db->clean(SITE,'encode');?>shop-settings/">Settings</a></li>
	</ul>	
	
	<ul class="navigate">
		<li><a href="<?php echo $db->clean(SITE,'encode');?>pages/">Pages</a></li>
		<li><a href="<?php echo $db->clean(SITE,'encode');?>pages/add/">Add page</a></li>
		<li><a href="<?php echo $db->clean(SITE,'encode');?>components/add/">Component</a></li>
		<li><a href="<?php echo $db->clean(SITE,'encode');?>resources/">Resources</a></li>
		<li><a href="<?php echo $db->clean(SITE,'encode');?>lightbox/">Lightbox</a></li>
		<li><a href="<?php echo $db->clean(SITE,'encode');?>settings/">Admin Settings</a></li>
		<li><a href="<?php echo $db->clean(SITE,'encode');?>logout/<?php echo $token;?>/">Exit</a></li>
	</ul>