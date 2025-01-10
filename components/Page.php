<article class="shop-page-highlight">
	<div class="shop-center">
		<div class="shop-page">
		<?php
		
		if(!isset($page_result)) {
			header("Location: ../index.php");
			exit;
		}
		
		for($i=0;$i<count($page_result);$i++) {
			if($page_result[$i]['component_image'] !=='') {
				echo "<div class=\"shop-page-image\"><img src=\"".$shop->clean(WEBSITE,'encode')."resources/content/".$shop->clean($page_result[$i]['component_image'],'encode')."\" /></div>";
				} else {
			}
			echo "<div class=\"shop-page-title\">".$shop->clean($page_result[$i]['component_title'],'encode')."</div>";
			echo "<div class=\"shop-page-description\">".$shop->clean($page_result[$i]['component_text'],'html')."</div>";
		}
		?>
		</div>
	</div>
</article>