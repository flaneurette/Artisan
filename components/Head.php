<header class="header">
	<a href="<?php echo $shop->clean(WEBSITE,'encode');?>"><div id="logo"></div></a>
	<div id="mobile-menu">
		<span class="material-symbols-outlined">
			<a href="#" onclick="javascript:document.getElementById('mobile-nav').style='display:block';">menu</a>
		</span>
	</div>
	<div id="mobile-nav">
		<nav>
			<ul id="mobile-navigation">
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
		</nav>
	</div>
</header>