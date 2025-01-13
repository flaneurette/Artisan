<article class="shop-search">
	<div class="shop-search-box">
		<div class="shop-search-box-0"></div>
		<div class="shop-search-box-1">
		<form name="shop-search" action="<?php echo $db->clean(WEBSITE,'encode');?>search/" method="post" />
			<input type="text" name="search"/><input type="submit" name="" value="Search" class="search-btn" />
		</form>
		</div>
		<div class="shop-search-box-2">
			<a href="<?php echo $db->clean(WEBSITE,'encode');?>cart/">
				<span class="material-symbols-outlined" id="shop-cart-symbol">
					shopping_basket
				</span>
			</a>
		</div>
	</div>
</article>