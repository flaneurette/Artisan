<nav class="nav">
	<ul class="navigation" id="navigation">
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