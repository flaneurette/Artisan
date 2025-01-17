<footer class="footer">
	<div class="shop-floor">
		<div class="shop-floor-center">
			<div>
			<a href="<?php echo $shop->clean(WEBSITE,'encode');?>"><div id="logo-footer"></div></a>
					<ul>
					<?php 
					if(isset($pages)) {
						for($i=0;$i<count($pages);$i++){
							echo "<li><a href=\"".$shop->clean(WEBSITE,'encode')."pages".$shop->clean($pages[$i]['page_name'],'encode')."\">";
							echo  $shop->clean($shop->clean($pages[$i]['page_name'],'encode'),'url');
							echo "</a></li>";
						}
					}
					?>
					<img id="payment-logo" src="<?php echo $shop->clean(WEBSITE,'encode');?>assets/images/payment.png"/>
					</ul>
			</div>
			<div>
					<ul>
					<?php 
					if(isset($menu)) {
						for($i=0;$i<count($menu);$i++){
							echo "<li><a href=\"".$shop->clean(WEBSITE,'encode')."category/".$db->clean($menu[$i]['category.name'],'encode')."/\">";
							echo  $shop->clean($shop->clean($menu[$i]['category.name'],'encode'),'url');
							echo "</a></li>";
						}
					}
					?>
					</ul>
			</div>
		</div>
	</div>
	<div class="copyright">
		<p>All rights reserved &copy; <?php echo $shop->clean($_SERVER["HTTP_HOST"],'encode');?></p>
	</div>
</footer>