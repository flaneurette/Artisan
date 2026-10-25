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
			<?php
			if(isset($menu)) {
				$k=0;
				for($j=count($menu);$j>=0;$j--){		
					if($j % 4== 0) { 
				?>	<div><ul class="shop-floor-links">
					<?php 
						for($i=$k;$i<count($menu)-$j;$i++) {
							echo "<li><a href=\"".$shop->clean(WEBSITE,'encode')."category/".$db->clean($menu[$i]['category.name'],'encode')."/\">";
							echo  $shop->clean($shop->clean($menu[$i]['category.name'],'encode'),'url');
							echo "</a></li>";
							$k++;
						}
					?>
					</ul>
					</div>
					<?php
					}
				}
			}
			?>
		</div>
	</div>
	<div class="copyright">
		<p>All rights reserved &copy; <?php echo $shop->clean($_SERVER["HTTP_HOST"],'encode');?></p>
	</div>
</footer>